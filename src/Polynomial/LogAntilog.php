<?php
namespace id009\QRGenerator\Polynomial;

/**
 * Class for keeping Log Antilog table
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class LogAntilog
{
	/**
	 * Log table
	 * @var array
	 */
	public static $logTable = array();
	
	/**
	 * Antilog table
	 * @var array
	 */
	public static $antilogTable = array();
	
	/**
	 * Initializes tables
	 */
	public static function init()
	{
		for ($i = 0; $i < 8; $i++){
			self::$antilogTable[] = 1 << $i;
		}
		
		for ($i = 8; $i < 256; $i++){
			self::$antilogTable[] = self::$antilogTable[$i-4] ^ self::$antilogTable[$i-5] ^ self::$antilogTable[$i-6] ^ self::$antilogTable[$i - 8];
		}
		
		for ($i = 0; $i < 255; $i++){
			self::$logTable[self::$antilogTable[$i]] = $i;
		}
	}
	
	/**
	 * Returns exponent for polynomial coefficient
	 * 
	 * @param int $log
	 * @return int
	 */
	public static function getExponent($log)
	{
		if ($log < 1)
			throw new \RuntimeException('Invalid coefficient given');
		return self::$logTable[$log];
	}
	
	/**
	 * Return coefficient for given exponent
	 * 
	 * @param int $exponent
	 * @return int
	 */
	public static function getCoefficient($exponent)
	{
		while ($exponent < 0)
			$exponent += 255;
		
		while ($exponent >= 256)
			$exponent -= 255;
		
		return self::$antilogTable[$exponent];
	}
}


?>