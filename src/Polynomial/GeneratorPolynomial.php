<?php
namespace id009\QRGenerator\Polynomial;

/**
 * Class for generating generator polynomial
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class GeneratorPolynomial
{
	/**
	 * Returns generator polynomial for given error correction length
	 * 
	 * @param int $errorCorrectionLength
	 * @return id009\QRGenerator\Polynomial\Polynomial
	 */
	public static function build($errorCorrectionLength)
	{
		$polynomial = new Polynomial(array(1));
		for ($i = 0; $i < $errorCorrectionLength; $i++){
			$polynomial = $polynomial->multiply(new Polynomial(array(1,LogAntilog::getCoefficient($i))));
		}
		
		return $polynomial;
	}
}

?>