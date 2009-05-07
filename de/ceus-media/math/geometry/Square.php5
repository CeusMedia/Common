<?php
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Geometry_Square
{
	public function __construct( $a )
	{
		$this->a = $a;
	}

	public function volume()
	{
		return pow( $this->a, 2 );	
	}

	public function outline()
	{
		return 4 * $this->a;
	}
	
	public function diagonal()
	{
		$c = $this->a * sqrt( 2 );
		return $c;
	}
}
?>