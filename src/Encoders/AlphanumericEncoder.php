<?php
namespace id009\QRGenerator\Encoders;

/**
 * String encoder for alphanumeric type of data
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class AlphanumericEncoder extends AbstractEncoder
{
	/**
	 * Data mode in binary according to specification
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::binDatamode
	 */
	protected  $binDatamode = '0010';
	
	/**
	 * Data mode
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::dataMode
	 */
	protected  $dataMode = 'Alphanumeric';
	
	/**
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::encodeData()
	 */
	public function encodeData ($data)
	{
		$length = mb_strlen($data, "UTF-8");
		$this->length = $length;
		$binary = $this->binDatamode.$this->getBin($length, $this->getDataLengthInBits());

		$i = 0;	
		while($i + 1 < $length){
			$binary .= $this->getBin($this->getCharacterCode($data[$i])*45+$this->getCharacterCode($data[$i+1]), 11);
			
			$i += 2;
		}
		
		if ($i < $length){
			$binary .= $this->getBin($this->getCharacterCode($data[$i]), 6);
		}
		return $binary;
	}
	
	/**
	 * Overrided function to convert characters according to Alphanumeric specification
	 * 
	 * @param string $character
	 * @return int
	 */
	protected function getCharacterCode($character)
	{
		$code  = parent::getCharacterCode($character);
		
		if (parent::getCharacterCode('0') <= $code && $code <= parent::getCharacterCode('9'))
			return $code - parent::getCharacterCode('0');
		else if (parent::getCharacterCode('A') <= $code && $code <= parent::getCharacterCode('Z'))
			return $code - parent::getCharacterCode('A') + 10;
		else {
			switch($character){
				case ' ': return 36;
				case '$': return 37;
				case '%': return 38;
				case '*': return 39;
				case '+': return 40;
				case '-': return 41;
				case '.': return 42;
				case '/': return 43;
				case ':': return 44;
				default:
					throw new \RuntimeException('Illegal character');
			}			
		}
	}
	
	/**
	 * @see id009\QRGenerator\Encoders\AbstractEncoder::isSuitable()
	 */
	public function isSuitable($data)
	{
		for ($i = 0; $i < strlen($data); $i++){
			$character = ord($data[$i]);
			if (!(ord('0') <= $character && $character <= ord('9')) && 
				!(ord('A') <= $character && $character <= ord('Z')) && 
				false === strpos(" $%*+-./:", $data)){
					return false;
				}
		}
		
		return true;	
	}
}
?>