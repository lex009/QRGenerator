<?php
namespace id009\QRGenerator\Factory;

use id009\QRGenerator\Specification;
use id009\QRGenerator\Code;

/**
 * Factory for QR Code
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class CodeFactory
{
	/**
	 * Returns generated QR Code
	 * 
	 * @param string $data
	 * @param string $errorCorrectionLevel
	 * @return id009\QRGenerator\Code
	 */
	public static function getCode($data, $errorCorrectionLevel)
	{
		$encoder = CodeFactory::getEncoder($data);
		$version = CodeFactory::getMinVersion($data, $errorCorrectionLevel, $encoder);
		
		$code = new Code($version, $errorCorrectionLevel);
		$code->setEncoder($encoder)
			 ->setData($data)
			 ->generate();
		
		return $code;
	}
	
	/**
	 * Returns mininal version suitable to keep given data with given error correction level
	 * 
	 * @param string $data
	 * @param string $errorCorrectionLevel
	 * @return int
	 */
	private static function getMinVersion($data, $errorCorrectionLevel, $encoder)
	{
		$dataMode = $encoder->getDataMode();

		for ($i = 0; $i < 10; $i++){
			if (strlen($data) <= Specification::$maxLength[$i+1][$errorCorrectionLevel][$dataMode]){
				$version = $i+1;
				break;
			}
		}
		
		return $version;
	}
	
	/**
	 * Returns encoder suitable to encode given data
	 * 
	 * @param string $data
	 * @return string
	 */
	private static function getEncoder($data)
	{
		$dataModes = Specification::$allowedDatamodes;
		foreach ($dataModes as $mode){
			$encoderClass = 'id009\QRGenerator\Encoders\\'.$mode.'Encoder';
			if (!class_exists($encoderClass))
				throw new \RuntimeException('Missing encoder for given data mode. Encoder class name must start from data mode name');
			
			$encoder = new $encoderClass();
			if ($encoder->isSuitable($data)) return $encoder;
		}
	}
	
}

?>