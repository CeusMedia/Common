<?php
/**
 *	...
 *	@package		functions
 *	@access			public
 *	@param			string		$string		String to be splitted
 *	@param			int			$length		Length of splitted parts
 *	@return			array
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			07.06.2006
 *	@version		0.4
 */
function str_ends( $string, $with, $case = false )
{
	$length	= strlen( $with );
	if( $length )
	{
		$end	= substr( $string, -$length );
		if( !$case )
		{
			$end	= strtolower( $end );
			$with	= strtolower( $with );
		}
		return $end == $with;
	}
	return false;
}		
?>
