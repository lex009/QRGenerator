<?php
namespace id009\QRGenerator\Encoders;

use id009\QRGenerator\Specification;

/**
 * Abstract data encoder
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
abstract class AbstractEncoder
{
	/**
	 * Version of QR Code
	 * @var int
	 */
	protected $version;
	
	/**
	 * Name of data mode
	 * @see id009\QRGenerator\Specification
	 * @var string 
	 */
	protected $dataMode;
	
	/**
	 * Code for data mode
	 * @var string
	 */	
	protected $binDatamode;
	
	/**
	 * Length
	 * @var int
	 */
	protected $length;
	
	/**
	 * Encodes given data
	 * 
	 * @param string $data
	 * @return string
	 */
	abstract public function encodeData($data);
	
	/**
	 * Returns whether encoder is suitable to encode given data
	 * 
	 * @param string $data
	 * @return boolean
	 */
	abstract public function isSuitable($data);
	
	/**
	 * Returns ASCII code for given character
	 * 
	 * @param string $character
	 * @return int
	 */
	protected function getCharacterCode($character)
	{
		return ord($character[0]);
	}
	
	/**
	 * Returns count of bits used to encode data length
	 * 
	 * @return int
	 */
	protected function getDataLengthInBits()
	{
		
		if (1 <= $this->version && 10 > $this->version){
			return Specification::$dataLengthInBits['1-9'][$this->dataMode];
		} else if ($this->version < 27){
			return Specification::$dataLengthInBits['10-26'][$this->dataMode];
		} else if ($this->version < 41) {
			return Specification::$dataLengthInBits['27-40'][$this->dataMode];
		}
	}
	
	/**
	 * Returns binary of given number with specified length in bits
	 * 
	 * @param int $num
	 * @param int $length 
	 * @return string
	 */
	public function getBin($num, $length)
	{
		$bin = decbin($num);
		
		return substr(str_repeat(0, $length), 0, $length - strlen($bin)).$bin; 
	}
	
	/**
	 * Returns length of data
	 * 
	 *  @return int
	 */
	public function getLength()
	{
		return $this->length;
	}
	
	/**
	 * Setter for $version
	 * 
	 * @param int
	 */
	public function setVersion($version)
	{
		$this->version = $version;	
	}
	
	/**
	 * Getter for $dataMode
	 * 
	 * @return srting
	 */
	public function getDataMode()
	{
		return $this->dataMode;	
	}

}

?>