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
function str_starts( $string, $with, $case = false )
{
	$length	= strlen( $with );
	if( $length )
	{
		$start	= substr( $string, 0, $length );
		if( !$case )
		{
			$start	= strtolower( $start );
			$with	= strtolower( $with );
		}
		return $start == $with;
	}
	return false;
}		
?>
