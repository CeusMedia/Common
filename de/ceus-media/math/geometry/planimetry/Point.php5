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
 *	@version			0.1
 *	@todo			Code Documentation
 */
class Point extends Planimetry
{
	var $_x;
	var $_y;
	
	public function __construct( $x, $y )
	{
		$this->setX( $x );
		$this->setY( $y );
	}

	function setX()
	{
		$this->_x = $x;
	}
	
	function setY()
	{
		$this->_y = $y;
	}
	
	function getX()
	{
		return $this->_x;
	}

	function getY()
	{
		return $this->_y;
	}
}
?>