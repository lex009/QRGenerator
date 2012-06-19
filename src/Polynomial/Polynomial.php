<?php
namespace id009\QRGenerator\Polynomial;

/**
 * Class for polynomial
 * 
 * @author AlexBelyaev <a.v.belyaev@gmail.com>
 */
class Polynomial
{
	/**
	 * Polynomial coefficients
	 * @var array
	 */
	protected $coefficients;
	
	/**
	 * Constructor
	 * 
	 * @param array $coefficients
	 * @param int $shift
	 */
	public function __construct($coefficients, $shift = 0)
	{
		$offset = 0;
		
		while ($offset < count($coefficients) && $coefficients[$offset] == 0)
			$offset++;
		
		for ($i = 0; $i < count($coefficients) - $offset; $i++){
			$this->coefficients[$i] = $coefficients[$i+$offset];
		}
		
		$this->coefficients = array_pad($this->coefficients,  count($coefficients) - $offset + $shift, 0);
	}
	
	/**
	 * Getter for polynomial's coefficients
	 * 
	 * @param int $index
	 * @return int
	 */
	public function getCoefficient($index)
	{
		return $this->coefficients[$index];
	}
	
	/**
	 * Reutrns length of polynomial
	 * 
	 * @return int
	 */
	public function getLength()
	{
		return count($this->coefficients);
	}
	
	/**
	 * Multiplies polynomnials
	 * 
	 * @param id009\QRGenerator\Polynomial\Polynomial $polynomial
	 * @return id009\QRGenerator\Polynomial\Polynomial
	 */
	public function multiply(Polynomial $polynomial)
	{
		$coefficients = array_fill(0, $this->getLength() + $polynomial->getLength() - 1, 0);
		
		for ($i = 0; $i < $this->getLength(); $i++){
			for ($j = 0; $j < $polynomial->getLength(); $j++){
				$coefficients[$i+$j] ^= LogAntilog::getCoefficient(LogAntilog::getExponent($this->getCoefficient($i)) + LogAntilog::getExponent($polynomial->getCoefficient($j)));
			}
		}
		
		return new Polynomial($coefficients);
	}
	
	/**
	 * Devides polynomials
	 * 
	 * @param id009\QRGenerator\Polynomial\Polynomial $polynomial
	 * @return id009\QRGenerator\Polynomial\Polynomial 
	 */
	public function mod(Polynomial $polynomial)
	{
		if ($this->getLength() - $polynomial->getLength() < 0)
			return $this;
		
		$ratio = LogAntilog::getExponent($this->getCoefficient(0)) - LogAntilog::getExponent($polynomial->getCoefficient(0));
		$coefficients = $this->coefficients;
		
		for ($i = 0; $i < $polynomial->getLength(); $i++){
			$coefficients[$i] ^= LogAntilog::getCoefficient(LogAntilog::getExponent($polynomial->getCoefficient($i)) + $ratio);
		}
		
		$newPolynomial = new Polynomial($coefficients);
		
		return $newPolynomial->mod($polynomial); 
	}
}

?>