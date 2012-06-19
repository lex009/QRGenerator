<?php
namespace id009\QRGenerator\Renderers;

/**
 * PNG Renderer
 * Renders QR Code in png format
 * 
 * <code>
 * $renderer = new EpsRenderer()
 * $file = $renderer->setForegroundColor(0xffffff)
 *                  ->setBackgroundColor(0x000000)
 * 					->render();
 * 
 * header($renderer->getContentHeader());
 * echo $file;
 * </code>
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class PngRenderer extends AbstractRenderer
{
	/**
	 * @see id009\QRGenerator\Renderers\AbstractRenderer::render()
	 */
	public function render($file = null)
	{
		$length = $this->code->getSideLength();
		$imgSize = $length + $this->padding * 2;
		$canvas = imagecreate($imgSize, $imgSize);
		
		$setBitColor = imagecolorallocate($canvas, ($this->foregroundColor & 0xff0000) >> 16, ($this->foregroundColor & 0x00ff00) >> 8, ($this->foregroundColor & 0x0000ff));
		if ($this->transparent)
			$emptyBitColor = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
		else
			$emptyBitColor = imagecolorallocate($canvas, ($this->backgroundColor & 0xff0000) >> 16, ($this->backgroundColor & 0x00ff00) >> 8, ($this->backgroundColor & 0x0000ff));
		
		imagefill($canvas, 0, 0, $emptyBitColor);
		
		$matrix = $this->code->getMatrix();
		for ($r = 0; $r < $length; $r++){
			for ($c = 0; $c < $length; $c++){
				if ($matrix[$r][$c]){
					$y = $this->padding + $r;
					$x = $this->padding + $c;
					imagesetpixel($canvas, $x, $y, $setBitColor);
				}
			}
		}
		
		$resultSize = $this->calculateCodeSize() + $this->padding * 2 * $this->scale;
		$imageResult = imagecreate($resultSize, $resultSize);
		
		imagecopyresized($imageResult, $canvas, 0, 0, 0, 0, $resultSize, $resultSize, $imgSize, $imgSize);
		
		@imagedestroy($canvas);
		
		if (null === $file){
			ob_start();
			imagepng($imageResult);
			$renderedCode = ob_get_contents();
			ob_end_flush();
			
			return $renderedCode;
		} else {
			imagepng($imageResult, $file);
			
			return $file;
		}
	}
	
	/**
	 * @see id009\QRGenerator\Renderers\AbstractRenderer::getContentHeader()
	 */
	public function getContentHeader()
	{
		return 'Content-Type: image/png';
	}
}

?>