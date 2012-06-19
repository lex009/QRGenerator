<?php
namespace id009\QRGenerator;

use id009\QRGenerator\Encoders\Factory\EncoderFactory;
use id009\QRGenerator\Polynomial\LogAntilog;
use id009\QRGenerator\Polynomial\GeneratorPolynomial;
use id009\QRGenerator\Polynomial\Polynomial;

/**
 * Class for generating and keeping QR Code data.
 * 
 * QR Data is kept in private $matrix field after {@link generate()} function has been called. 
 * It can be retrieved by appropriate getter function
 * <code>
 *  $encoder = new Encoders\AlphanumericEncoder();
 * 
 * 	$code  = new Code(1, "Q")
 * 	$matrix = $code->setEncoder($encoder)
 * 				   ->setData("HELLO WORLD")
 * 			       ->generate()
 *                 ->getMatrix();
 * </code>
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class Code
{
	/**
	 * Version of code.
	 * @var int
	 */
	private $version;
	
	/**
	 *  Error correction level
	 *  @var string 
	 */
	private $errorCorrectionLevel;
	
	/**
	 * Type of data
	 * @var string
	 */
	private $dataMode;
	
	/**
	 * Reed-Solomon blocks
	 * @var array
	 */
	private $rsBlocks;
	
	/**
	 * Encoder for current data type
	 * @var id009\QRGenerator\Encoders\AbsrtactEncoder 
	 */
	private $encoder;
	
	/**
	 * Keeps data blocks in decimal format
	 * @var array
	 */
	private $decimalDataBlocks = array();
	
	/**
	 * Keeps data blocks in binary format
	 * @var array
	 */
	private $binaryDataBlocks = array();
	
	/**
	 * Keeps decimal data
	 * @var array
	 */
	private $decimalData = array();
	
	/**
	 * Keeps binary data
	 * @var array
	 */
	private $binaryData = array();
	
	/**
	 * Matrix of data
	 * @var array
	 */
	private $matrix;
	
	/**
	 * Length of matrix's side
	 * @var int
	 */
	private $sideLength;
	
	/**
	 * Contructor
	 * 
	 * @param int $version
	 * @param string $errorCorrectionLevel
	 * 
	 * @api
	 */
	public function __construct($version, $errorCorrectionLevel)
	{
		$this->version = $version;
		$this->errorCorrectionLevel = $errorCorrectionLevel;
		LogAntilog::init();
	}

	/**
	 * Getter for the matrix
	 * 
	 * @return array
	 * 
	 * @api
	 */
	public function getMatrix()
	{
		return $this->matrix;
	}
	
	/**
	 * Getter for the matrix's side length 
	 * 
	 * @return int
	 * 
	 * @api
	 */
	function getSideLength()
	{
		return $this->sideLength;
	}
	
	/**
	 * Setter for $encoder.
	 * Takes data mode from encoder and sets $dataMode
	 * Sets $version for encoder
	 * 
	 * @param id009\QRGenerator\Encoders\AbstractEncoder $encoder
	 * @return id009\QRGenerator\Code
	 */
	public function setEncoder(Encoders\AbstractEncoder $encoder)
	{
		$this->encoder = $encoder;
		$this->dataMode = $encoder->getDataMode();
		$this->encoder->setVersion($this->version);
		
		return $this;
	}
	
	/**
	 * Encodes user's data, paddings for it it and error correction codes 
	 *
	 * @param string $data Data to be encoded in QR Code
	 * @return id009\QRGenerator\Code
	 * 
	 * @api
	 */
	public function setData($data)
	{
		$encoder = $this->encoder;
		
		$binaryData = $encoder->encodeData($data);
		
		$this->rsBlocks = RSBlock::getRSBlocks($this->version, $this->errorCorrectionLevel);
		
		$totalDataCount = 0;
		foreach ($this->rsBlocks as $rsBlock){
			$totalDataCount += $rsBlock->getDataBlocksCount();
		}
		
		if ($encoder->getLength() > $totalDataCount * 8)
			throw new \Exception('Data too long');
		
		if ($encoder->getLength() + 4 <= $totalDataCount * 8)
			$binaryData .= '0000';
		
		while (strlen($binaryData) % 8 != 0)
			$binaryData .= '0';
		
		while(true){
			if (strlen($binaryData) >= $totalDataCount * 8)
				break;
			
			$binaryData .= '11101100';
			
			if (strlen($binaryData) >= $totalDataCount * 8)
				break;
			
			$binaryData .= '00010001';
		}
		
		$this->binaryDataBlocks = str_split($binaryData, 8);
		
		foreach ($this->binaryDataBlocks as $block){
			$this->decimalDataBlocks[] = bindec($block);
		}
		
		$this->createECData();
		
		return $this;
	}

	/**
	 * Fills data into the matrix with the best mask pattern
	 * 
	 * @return id009\QRGenerator\Code
	 * 
	 * @api
	 */
	public function generate()
	{
		$minPenalty = 0;
		$pattern = 0;
		
		for ($i = 0; $i < 8; $i++){
			$this->fillMatrix($i);
			$penalty = Penalty::calculate($this);
			if ($i == 0 || $minPenalty > $penalty){
				$minPenalty = $penalty;
				$pattern = $i;
			}
		}
		
		return $this->fillMatrix($pattern);
	}
	
	/**
	 * Fills data into the matrix with given mask pattern
	 * 
	 * @param int $maskPattern
	 * @return id009\QRGenerator\Code
	 */
	private function fillMatrix($maskPattern)
	{
		$this->createEmptyMatrix()
			 ->fillPositionProbe(0,0)
			 ->fillPositionProbe($this->sideLength - 7, 0)
			 ->fillPositionProbe(0, $this->sideLength -7)
			 ->fillPositionAdjustments()
			 ->addBlackPixel()
			 ->fillTimingPattern()
			 ->fillTypeInfo($maskPattern);
			 
		if ($this->version >= 7)
			$this->fillVersionInfo();
		
		
		$this->fillData($maskPattern);
		
		return $this;
	}

	/**
	 * Prepares empty matrix
	 * 
	 * @return id009\QRGenerator\Code
	 */
	private function createEmptyMatrix()
	{
		$this->sideLength = $this->version * 4 + 17;
		
		for ($i = 0; $i < $this->sideLength; $i++){
			$this->matrix[$i] = array_fill(0, $this->sideLength, null);
		}
		
		return $this;
	}
	
	/**
	 * Fills position probe data into the matrix in given position
	 * 
	 * @param int $row
	 * @param int $column
	 * @return id009\QRGenerator\Code
	 */
	private function fillPositionProbe($row, $column)
	{
		for ($r = -1; $r <= 7; $r++){
			for ($c = -1; $c <= 7; $c++){
				if (($row + $r <= -1) || ($row + $r >= $this->sideLength) || ($column + $c <= -1) || ($column + $c >= $this->sideLength)){
					if (isset($matrix[$row+$r][$column+$c])) $matrix[$row+$r][$column+$c] = 0;
					continue;
				}
					
				if ( ((0 <= $r && $r <= 6) && ($c == 0 || $c == 6)) ||
					 ((0 <= $c && $c <= 6) && ($r == 0 || $r == 6)) ||
					 ((2 <= $r && $r <= 4) && (2 <= $c && $c <= 4))
				   )
					$this->matrix[$row+$r][$column+$c] = 1;
				else 
					$this->matrix[$row+$r][$column+$c] = 0;	
			}
		}
		
		return $this;
	}
	
	/**
	 * Adds required black pixel
	 * 
	 * @return id009\QRGenerator\Code
	 */
	private function addBlackPixel()
	{
		$this->matrix[$this->sideLength-8][8] = 1;
		
		return $this;
	}
	
	/**
	 * Fills timing data into the matrix
	 * 
	 * @return id009\QRGenerator\Code
	 */
	private function fillTimingPattern()
	{
		for ($r = 8; $r < $this->sideLength - 8; $r++){
			if (null !== $this->matrix[$r][6]) continue;
			$this->matrix[$r][6] = (int) ($r % 2 == 0);
		}
		
		for ($c = 8; $c < $this->sideLength - 8; $c++){
			if (null !== $this->matrix[6][$c]) continue;
			$this->matrix[6][$c] = (int) ($c % 2 == 0);
		}
		
		return $this;
	}
	
	/**
	 * Fills position adjustments data into the matrix for higher versions
	 * 
	 * @return id009\QRGenerator\Code
	 */
	private function fillPositionAdjustments()
	{
		$positions = Specification::$positionAdjustments[$this->version];
		
		for ($i = 0; $i < count($positions); $i++){
			for ($j = 0; $j < count($positions); $j++){
				$row = $positions[$i];
				$col = $positions[$j];
				
				if (null !== $this->matrix[$row][$col]) continue;
				
				for ($r = -2; $r <= 2; $r++){
					for ($c = -2; $c <= 2; $c++){
						if ($r == -2 || $r == 2 || $c == -2 || $c == 2 || ($r == 0 && $c ==0))
							$this->matrix[$row+$r][$col+$c] = 1;
						else 
							$this->matrix[$row+$r][$col+$c] = 0;
					}
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Fills version information into the matrix
	 * 
	 * @return id009\QRGenerator\Code
	 */
	private function fillVersionInfo()
	{
		$bits = str_split(Specification::$versionBits[$this->version]);
		
		for ($i = 0; $i < 18; $i++){
			$this->matrix[floor($i / 3)][$i % 3 + $this->sideLength - 11] = $bits[$i];
			$this->matrix[$i % 3 +$this->sideLength - 11][floor($i / 3)] = $bits[$i];
		}
		
		return $this;
	}
	
	/**
	 * Fills information about verion and mask pattern into the matrix
	 * 
	 * @param int $maskPattern
	 * @return id009\QRGenerator\Code
	 */
	private function fillTypeInfo($maskPattern)
	{
		$bits = str_split(Specification::$typeBits[$this->errorCorrectionLevel][$maskPattern]);
		for ($i = 0; $i < 15; $i++){
			if ($i < 7) $this->matrix[$this->sideLength-1 - $i][8] = $bits[$i];
			else if ($i >= 7) $this->matrix[8][$this->sideLength - 8 + ($i - 7)] = $bits[$i];
		}
		
	for ($i = 0; $i < 17; $i++){
			if ($i < 6) $this->matrix[8][$i] = $bits[$i];
			else if ($i == 6) continue;
			else if (6 < $i && $i < 9) $this->matrix[8][$i] = $bits[$i - 1];
			else if ($i == 9) $this->matrix[7][8] = $bits[$i - 1];
			else if ($i == 10) continue;
			else $this->matrix[16 - $i][8] = $bits[$i - 2];
		}
		
		return $this;
	}
	
	/**
	 * Fills user's data into the matrix with given mask pattern
	 * 
	 * @param int $maskPattern
	 * @return id009\QRGenerator\Code
	 */
	private function fillData($maskPattern)
	{
		$mask = Specification::getMaskPattern($maskPattern);
		$inc = -1;
		$row = $this->sideLength - 1;
		$bitIndex = 0;
		
		for ($col = $this->sideLength - 1; $col > 0; $col -= 2){
			if ($col == 6) $col--;
			
			while (true){
				for ($c = 0; $c < 2; $c++){
					if (null === $this->matrix[$row][$col - $c]){
						if (isset($this->binaryData[$bitIndex]))		
						$currentBit = $this->binaryData[$bitIndex];
						else $currentBit = 0;
						
						if ($mask($row, $col - $c)){
							$currentBit = !$currentBit;
						}
						
						$this->matrix[$row][$col - $c] = (int) $currentBit;
						
						$bitIndex++;
					}
				}
				
				$row += $inc;
				
				if ($row < 0 || $this->sideLength <= $row){
					$row -= $inc;
					$inc = -$inc;
					break;
				}
			}
		}
	} 
	
	/**
	 * Creates error correction data
	 */
	private function createECData()
	{
		$offset = 0;
		$maxDataCount = 0;
		$maxECCount = 0;
		
		$userData = array();
		$ecData = array();
		
		for ($r = 0; $r < count($this->rsBlocks); $r++){
			$userData[$r] = array();
			$ecData[$r] = array();
			
			$dataCount = $this->rsBlocks[$r]->getDataBlocksCount();
			$ecCount = $this->rsBlocks[$r]->getTotalBlocksCount() - $dataCount;
			
			$maxDataCount = max($maxDataCount, $dataCount);
			$maxECCount = max($maxECCount, $ecCount);
			
			for ($i = 0; $i < $dataCount; $i++){
				$userData[$r][$i] = $this->decimalDataBlocks[$i+$offset];
			}
			
			$offset += $dataCount;
			
			$generatorPolynomial =  GeneratorPolynomial::build($ecCount);
			$messagePolynomial = new Polynomial($userData[$r], $generatorPolynomial->getLength() - 1);
			$modPolynomial = $messagePolynomial->mod($generatorPolynomial);

			for ($i = 0; $i < $generatorPolynomial->getLength() - 1; $i++){
				$modIndex = $i + $modPolynomial->getLength() - ($generatorPolynomial->getLength() - 1);
				$ecData[$r][$i] = $modIndex >= 0 ? $modPolynomial->getCoefficient($modIndex) : 0;
			}
		}
		
		for ($i = 0; $i < $maxDataCount; $i++){
			for ($r = 0; $r < count($this->rsBlocks); $r++){
				if ($i < count($userData[$r]))
					$this->decimalData[] = $userData[$r][$i];
			}
		}
		
		for ($i = 0; $i < $maxECCount; $i++){
			for ($r = 0; $r < count($this->rsBlocks); $r++){
				if ($i < count($ecData[$r]))
					$this->decimalData[] = $ecData[$r][$i];
			}
		}
		
		foreach ($this->decimalData as $decimal){
			$this->binaryData = array_merge($this->binaryData, str_split($this->encoder->getBin($decimal, 8)));
		}
		
	}
}
?>