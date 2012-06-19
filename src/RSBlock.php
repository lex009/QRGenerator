<?php
namespace id009\QRGenerator;

use id009\QRGenerator\Specification;

/**
 * Class for keeping Reed-Solomon block
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class RSBlock
{
	private $totalBlocksCount;
	
	private $dataBlocksCount;
	
	/**
	 * @param int $totalBlocksNum
	 * @param int $dataBlocksNum
	 */
	private function __construct($totalBlocksCount, $dataBlocksCount)
	{
		$this->totalBlocksCount = $totalBlocksCount;
		$this->dataBlocksCount = $dataBlocksCount;
	}
	
	/**
	 * @return int
	 */
	public function getTotalBlocksCount()
	{
		return $this->totalBlocksCount;	
	}
	
	/**
	 * @return int
	 */
	public function getDataBlocksCount(){
		return $this->dataBlocksCount;
	}
	
	/**
	 * Returns Reed-Solomon blocks for given code version and error correction level
	 * 
	 * @param int $version
	 * @param string $errorCorrectionLevel
	 * @return array
	 */
	public static function getRSBlocks ($version, $errorCorrectionLevel)
	{
		$rsBlockArr = Specification::$RSBlocks[$version][$errorCorrectionLevel];
		
		$rsBlocksArr = array();
		
		for ($i = 0; $i < count($rsBlockArr) / 3; $i++){
			for ($j = 0; $j < $rsBlockArr[$i*3]; $j++){
				$rsBlocksArr[] = new RSBlock($rsBlockArr[$i*3+1], $rsBlockArr[$i*3+2]);
			}
		}
		
		return $rsBlocksArr;
	}
}

?>