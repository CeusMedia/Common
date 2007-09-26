<?php
/**
 *	Complex Number with base operations.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
/**
 *	Complex Number with base operations.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
class ComplexNumber
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed	$real		Real part of the complex number
	 *	@param		mixed	$image		Imaginary part of the complex number
	 *	@return		void
	 */
	public function __construct( $real, $image )
	{
		$this->_real	= $real;
		$this->_image	= $image;
	}
	
	/**
	 *	Returns the real party of the complex number.
	 *	@access		public
	 *	@return		mixed
	 */
	function getRealPart()
	{
		return $this->_real;
	}
	
	/**
	 *	Returns the iimaginary party of the complex number.
	 *	@access		public
	 *	@return		mixed
	 */
	function getImagePart()
	{
		return $this->_image;
	}
	
	/**
	 *	Addition of complex numbers.
	 *	@access		public
	 *	@param		ComplexNumber	$complex		Complex number to be added
	 *	@return		ComplexNumber
	 */
	function add( $complex )
	{
		$a	= $this->getRealPart();
		$b	= $this->getImagePart();
		$c	= $complex->getRealPart();
		$d	= $complex->getImagePart();
		$real	= $a + $c;
		$image	= $b + $d;
		$result = new ComplexNumber ($real, $image);
		return new ComplexNumber ($real, $image);
	}
	
	/**
	 *	Substraction of complex numbers.
	 *	@access		public
	 *	@param		ComplexNumber	$complex		Complex number to be subtracted
	 *	@return		ComplexNumber
	 */
	function sub ($complex)
	{
		$a	= $this->getRealPart();
		$b	= $this->getImagePart();
		$c	= $complex->getRealPart();
		$d	= $complex->getImagePart();
		$real	= $a - $c;
		$image	= $b - $d;
		$result = new ComplexNumber ($real, $image);
		return new ComplexNumber ($real, $image);
	}
	
	/**
	 *	Multiplication of complex numbers.
	 *	@access		public
	 *	@param		ComplexNumber	$complex		Complex number to be multiplied
	 *	@return		ComplexNumber
	 */
	function mult ($complex)
	{
		$a	= $this->getRealPart();
		$b	= $this->getImagePart();
		$c	= $complex->getRealPart();
		$d	= $complex->getImagePart();
		$real	= $a * $c - $b * $d;
		$image	= $a * $d + $b * $c;
		return new ComplexNumber ($real, $image);
	}
	
	/**
	 *	Division of complex numbers.
	 *	@access		public
	 *	@param		ComplexNumber	$complex		Complex number to be divised by
	 *	@return		ComplexNumber
	 */
	function div ($complex)
	{
		$a	= $this->getRealPart();
		$b	= $this->getImagePart();
		$c	= $complex->getRealPart();
		$d	= $complex->getImagePart();
		$real	= ($a * $c + $b * $d) / ($c * $c + $d * $d);
		$image	= ($b * $c - $a * $d) / ($c * $c + $d * $d);
		$result = new ComplexNumber ($real, $image);
		return new ComplexNumber ($real, $image);
	}

	/**
	 *	Returns the complex number as a representative string.
	 *	@access		public
	 *	@return		mixed
	 */
	function toString ()
	{
		$code = $this->getRealPart();
		if ($this->_image >= 0)
			$code .= "+".$this->getImagePart()."i";
		else
			$code .= "".$this->getImagePart()."i";
		return $code;
	}
}
?>