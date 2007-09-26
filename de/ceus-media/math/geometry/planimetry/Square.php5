<?php
/**
 *	@package	math
 *	@subpackage	geometry
 *	@extends	Planimetry
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	math
 *	@subpackage	geometry
 *	@extends	Planimetry
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo		Code Documentation
 */
import( 'de.ceus-media.math.geometry.planimetry.Planimetry' );
class Square extends Planimetry
{
	public function __construct( $a )
	{
		$this->_a = $a;
	}

	function volume()
	{
		return pow( $this->_a, 2 );	
	}

	function outline()
	{
		return 4 * $this->_a;
	}
	
	function diagonal()
	{
		$c = $this->_a * sqrt( 2 );
		return $c;
	}
}
?>