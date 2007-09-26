<?php
/**
 *	@package		math
 *	@subpackage		geometry
 *	@extends		Planimetry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version			0.1
 */
/**
 *	@package		math
 *	@subpackage		geometry
 *	@extends		Planimetry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			0.1
 *	@todo			Code Documentation
 */
import( 'de.ceus-media.math.geometry.planimetry.Planimetry' );
class Circle extends Planimetry
{
	public function __construct( $radius )
	{
		$this->_radius = $radius;
	}
	
	function volume()
	{
		$value = M_PI * pow( $this->_radius, 2 );
		return $value;
	}
	
	function outline()
	{
		$value = 2 * M_PI * $this->_radius;
		return $value;
	}
}
?>