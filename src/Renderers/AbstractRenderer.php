<?php
namespace id009\QRGenerator\Renderers;

use id009\QRGenerator\Code;

/**
 * Abstract renderer 
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
abstract class AbstractRenderer
{
	/**
	 * Scale for the code
	 * @var int 
	 */	
	protected $scale = 1;
	
	/**
	 * Padding of the code
	 * @var int
	 */
	protected $padding = 0;
	
	/**
	 * The code
	 * @var id009\QRGenerator\Code
	 */
	protected $code;
	
	/**
	 * Background color
	 * @var int
	 */
	protected $backgroundColor = 0xffffff;
	
	/**
	 * Foreground color
	 * @var int
	 */
	protected $foregroundColor = 0x000000;
	
	/**
	 * Indicates whether background must be transparent
	 * @var boolean
	 */
	protected $transparent = true;
	
	/**
	 * Renders the code.
	 * It must return file content or save it to the specified file and return file name
	 * 
	 * @param string $file
	 * @return string
	 */
	abstract public function render($file = null);
	
	/**
	 * Returns content type to use in header()
	 * 
	 * @return string
	 */
	abstract public function getContentHeader();
	
	/**
	 * Sets code object	 
	 *  
	 * @param id009\QRGenerator\Code $code
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setCode(Code $code)
	{
		$this->code = $code;
		
		return $this;
	}
	
	/**
	 * Sets whether backgound must be transparent or not
	 * 
	 * @param boolean $transparent
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setTransparent($transparent)
	{
		$this->transparent = $transparent;
		
		return $this;
	}
	
	/**
	 * Sets background color in hex format
	 * 
	 * @param int $color;
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setBackgroundColor($color)
	{
		$this->backgroundColor = $color;
		$this->transparent = false;
		
		return $this;
	}
	
	/**
	 * Sets foreground color in hex format
	 * 
	 * @param int $color
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setForegroundColor($color)
	{
		$this->foregroundColor = $color;
		
		return $this;
	}
	
	/**
	 * Sets padding
	 * 
	 * @var int $padding
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setPadding($padding)
	{
		if ($padding < 1)
			throw new \RuntimeException("Padding must be greater then zero");
		
		$this->padding = $padding;
		
		return $this;
	}
	
	/**
	 * Sets scale
	 * 
	 * @var int $scale
	 * @return id009\QRGenerator\Renderers\AbstractRenderer
	 */
	public function setScale($scale)
	{
		if ($scale < 1)
			throw new \RuntimeException("Scale must be greater or equals one");
		
		$this->scale = $scale;
		
		return $this;
	}
	
	/**
	 * Returns size of QR Code without padding
	 * 
	 * @return int
	 */
	protected function calculateCodeSize()
	{
		return $this->code->getSideLength() * $this->scale;
	}

}

?>