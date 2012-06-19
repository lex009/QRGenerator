<?php
namespace id009\QRGenerator;

/**
 * Class for calculation penalty for QR Code
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class Penalty
{
	/**
	 * Calculates penalty for code
	 * 
	 * @param id009\QRGenerator\Code Code object that keeps filled data matrix
	 */
	public static function calculate(Code $code)
	{
		$length = $code->getSideLength();
		$matrix = $code->getMatrix();
		$penalty = 0;
		
		//First rule
		for($row = 0; $row < $length; $row++){
			for ($col = 0; $col < $length; $col++){
				$sameBitCount = 0;
				$currentBit = $matrix[$row][$col];
				
				for ($r = -1; $r <= 1; $r++){
					if ($row + $r < 0 || $row + $r >= $length) continue;
					
					for ($c = -1; $c <= 1; $c++){
						if ($col + $c < 0 || $col + $c >= $length) continue;
						
						if ($r == 0 && $c == 0) continue;
						
						if ($currentBit == $matrix[$row+$r][$col+$c]) $sameBitCount++;
					}
				}
				
				if ($sameBitCount > 5) $penalty += (3 + $sameBitCount - 5);
			}
		}
		
		//Second rule
		for ($row = 0; $row < $length - 1; $row++){
			for ($col = 0; $col < $length - 1; $col++){
				$count = 0;
				
				if ($matrix[$row][$col] == 1) $count++;
				if ($matrix[$row + 1][$col] == 1) $count++;
				if ($matrix[$row][$col + 1] == 1) $count++;
				if ($matrix[$row + 1][$col + 1] == 1) $count++;
				
				if ($count == 0 || $count == 4) $penalty += 3;
			}
		}
		
		//Third rule
		for ($row = 0; $row < $length; $row++){
			for ($col = 0; $col < $length - 6; $col++){
				if (
					$matrix[$row][$col]   && 
				   !$matrix[$row][$col+1] &&
					$matrix[$row][$col+2] &&
					$matrix[$row][$col+3] &&
					$matrix[$row][$col+4] &&
				   !$matrix[$row][$col+5] &&
					$matrix[$row][$col+6]
				) $penalty += 40;
			}
		}
		
		for ($col = 0; $col < $length; $col++){
			for ($row = 0; $row < $length - 6; $row++){
				if (
				   !$matrix[$row][$col]     &&
					$matrix[$row + 1][$col] &&
					$matrix[$row + 2][$col] &&
					$matrix[$row + 3][$col] &&
					$matrix[$row + 4][$col] &&
				   !$matrix[$row + 5][$col] &&
					$matrix[$row + 6][$col]
				) $penalty += 40;
			}
		}
		
		//Fourth rule
		$setBits = 0;
		
		for ($row = 0; $row < $length; $row++){
			for ($col = 0; $col < $length; $col++){
				if ($matrix[$row][$col]) $setBits++;
			}
		}
		
		$ratio = abs(100 * $setBits / $length / $length - 50) / 5;
		$penalty += $ratio * 10;
		
		return $penalty;
	}	
}

?>