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
if( !function_exists( "str_split" ) )
{
	function str_split( $string, $length = 1, $array = false )
	{
		if( !is_array( $array ) )
			$array	= array();
		if( strlen( $string ) <= $length )
		{
			$array[]	= $string;
			return $array;
		}
		$array[]	= substr( $string, 0, $length );
		$string	= substr( $string, $length );
		return str_split( $string, $length, $array );
	}		
}
?>
