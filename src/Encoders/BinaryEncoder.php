<?php
namespace id009\QRGenerator\Encoders;

/**
 * Encoder for binary type of data
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class BinaryEncoder extends AbstractEncoder
{
	/**
	 * Data mode in binary according to specification
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::binDatamode
	 */
	protected  $binDatamode = '0100';
	
	/**
	 * Data mode
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::dataMode
	 */
	protected  $dataMode = 'Binary';
	
	/**
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::encodeData()
	 */
	public function encodeData($data)
	{
		$this->length = strlen($data);
		$binary = $this->binDatamode.$this->getBin($this->length, $this->getDataLengthInBits());
		
		for ($i = 0; $i < $this->length; $i++){
			$binary .= $this->getBin(ord($data[$i]), 8);
		}
		
		return $binary;
	}
	
	/**
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::isSuitable()
	 */
	public function isSuitable($data)
	{	
		return true;	
	}
}

?>