<?php
namespace id009\QRGenerator;

use id009\QRGenerator\Factory\CodeFactory;
use id009\QRGenerator\Renderers\AbstractRenderer;

/**
 * Generator class
 * 
 * @author Alex Belyaev <a.v.belyaev@gmail.com>
 */
class Generator
{
	/**
	 * Keeps renderer
	 * @var id009\Renderers\AbstractRenderer
	 */
	protected $renderer;
	
	/**
	 * Sets renderer
	 * 
	 * @param id009\Renderers\AbstractRenderer $renderer
	 * @return id009\QRGenerator\Generator
	 */
	public function setRenderer(AbstractRenderer $renderer)
	{
		$this->renderer = $renderer;
		
		return $this;
	}
	
	/**
	 * Generates code
	 * 
	 * @param string $data Data for encoding in QR Code
	 * @param string $errorCorrectionLevel
	 * @return id009\QRGenerator\Generator
	 */
	public function generate($data, $errorCorrectionLevel)
	{
		$code = CodeFactory::getCode($data, $errorCorrectionLevel);
		$this->renderer->setCode($code);
		
		return $this;
	}
	
	/**
	 * Saves generated QR Code into given file
	 * 
	 * @param string $file
	 */
	public function save($file)
	{
		$this->renderer->render($file);
	}
	
	/**
	 * Sends generated QR Code to browser
	 * 
	 * @param string $fileName
	 */
	public function send($fileName)
	{
		header($this->renderer->getContentHeader());
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		echo $this->renderer->render();
	}
}

?>