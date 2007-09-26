<?php
/**
 *	@package		math
 *	@subpackage		geometry
 *	@extends		Planimetry
 *	@uses			Triangle
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version			0.1
 */
/**
 *	@package		math
 *	@subpackage		geometry
 *	@extends		Planimetry
 *	@uses			Triangle
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version			0.1
 *	@todo			Code Documentation
 */

import( 'de.ceus-media.math.geometry.planimetry.Planimetry' );
class Rectangle extends Planimetry
{
	public function __construct( $a, $b )
	{
		$this->_a = $a;
		$this->_b = $b;
	}
	
	function volume()
	{
		return $this->_a * $this->_b;	
	}
	
	function outline()
	{
		return 2 *( $this->_a + $this->_b );
	}
	
	function diagonal()
	{
		$t = new Triangle();
		$c = $t->pythagoras( $this->_a, $this->_b );
		return $c;
	}
}
?>