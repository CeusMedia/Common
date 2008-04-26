<?php
/**
 *	...
 *	@package		functions
 *	@access			public
 *	@param			string		$integer	...
 *	@param			int			$length		...
 *	@param			bool		$reverse		...
 *	@return			array
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.06.2006
 *	@version		0.4
 */
function getBits( $integer, $length = 8, $reverse = false )
{
	$bin	= decbin( $integer );
	$bin	= str_pad( $bin, $length, "0", STR_PAD_LEFT );
	$array	= str_split( $bin );
	if( !$reverse )
		$array	= array_reverse( $array );
	return $array;
}
?>