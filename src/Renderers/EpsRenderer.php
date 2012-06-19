<?php
namespace id009\QRGenerator\Renderers;

/**
 * EPS Renderer
 * Renders QR Code in eps format
 * 
 * <code>
 * $renderer = new EpsRenderer()
 * $file = $renderer->setTitle('title')
 *                  ->setCreator('creator')
 *                  ->setForegroundColor(0xffffff)
 *                  ->setBackgroundColor(0x000000)
 *                  ->render();
 * 
 * header($renderer->getContentHeader());
 * echo $file;
 * </code>
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class EpsRenderer extends AbstractRenderer
{
	/**
	 * Creator name to put in file header
	 * @var string
	 */
	protected $creator = 'id009 QRGenerator';
	
	/**
	 * Title to put in file header
	 * @var string
	 */
	protected $title = 'QR Code';
	
	/**
	 * Sets title
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		
		return $this;
	}
		
	/**
	 * Sets creator
	 * 
	 * @param string $creator
	 */
	public function setCreator($creator)
	{
		$this->creator = $creator;
		
		return $this;
	}
	
	/**
	 * @see id009\QRGenerator\Renderers\AbstractRenderer::render()
	 */
	public function render($file = null)
	{
		$size = ($this->calculateCodeSize() + $this->padding * 2) * $this->padding;
		$date = new \DateTime();
		$date = $date->format("Y-m-d");
		
		$eps = 
		'%!PS-Adobe EPSF-3.0'."\n".
    	'%%Creator: '.$this->creator."\n".
		'%%Title: '.$this->title."\n".
		'%%CreationDate: '.$date."\n".
		'%%DocumentData: Clean7Bit'."\n".
		'%%LanguageLevel: 2'."\n".
		'%%Pages: 1'."\n".
		'%%BoundingBox: 0 0 '.$size.' '.$size."\n";
		
		$r = round((($this->foregroundColor & 0xff0000) >> 16) / 255, 5);
		$g = round((($this->foregroundColor & 0x0000ff)) / 255, 5);
		$b = round((($this->foregroundColor & 0x00ff00) >> 8) / 255, 5);
		
		$color = $r." ".$g." ".$b;
		
		$eps .= $this->scale." ".$this->scale." scale \n";
		$eps .= $this->padding." ".$this->padding." translate \n";
		$eps .= $color." setrgbcolor \n";
		$eps .= '/F { rectfill } def'."\n";
		
		$matrix = $this->code->getMatrix();
		$length = $this->code->getSideLength();
		
		for ($r = 0; $r < $length; $r++){
			for ($c = 0; $c < $length; $c++){
				if ($matrix[$r][$c]){
					$x = $c;
					$y = $length - 1 - $r;
					
					$eps .= $x." ".$y." 1 1 F\n";
				}
			}
		}
		
		$eps .= "%%EOF";
		
		if (null === $file){
			return $eps;
		} else {
			$handle = fopen($file, 'w');
			fwrite($handle, $eps);
			fclose($handle);
			
			return $handle;
		}
	}

	/**
	 * @see id009\QRGenerator\Renderers\AbstractRenderer::getContentHeader()
	 */
	public function getContentHeader(){
		return "Content-Type: application/postscript";
	}
}

?>