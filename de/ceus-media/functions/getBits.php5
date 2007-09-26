<?php
/**
 *	Implementation of str_split for PHP 4.
 *	@access		public
 *	@param		string	string		String to be splitted
 *	@param		int		length		Length of splitted parts
 *	@return		array
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		07.06.2006
 *	@version		0.4
 */
import( 'de.ceus-media.functions.str_split' );
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