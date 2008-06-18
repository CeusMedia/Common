<?php
/**
 *	Make a string's first character lowercase.
 *	@package		functions
 *	@param			string		$string		String to be lcfirsted
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.06.2007
 *	@version		0.6
 */
if( !function_exists( 'lcfirst' ) )
{
	function lcfirst( $string )
	{
		$string[0] = strtolower( $string[0] );
		return $string;
	}
}
?>