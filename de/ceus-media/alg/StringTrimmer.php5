<?php
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
/**
 *	Trimmer for Strings, supporting cutting to the right and central cutting for too long Strings.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
class Alg_StringTrimmer
{
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trim( $string, $length = 0, $mask = "..." )
	{
		$string	= trim( $string );
		if( !( $length && strlen( $string ) > $length ) )
			return $string;
		$string	= substr( $string, 0, $length - strlen( $mask ) );
		$string	.= $mask;
		return $string;
	}
	
	/**
	 *	Trims String and cuts to the right if too long, also adding a mask string.
	 *	@access		public
	 *	@param		string		$string		String to be trimmed
	 *	@param		int			$length		Length of String to be at most
	 *	@param		string		$mask		Mask String to append after cut.
	 *	@return		string
	 */
	public static function trimCentric( $string, $length = 0, $mask = "..." )
	{
		if( strlen( $mask ) >= $length )
			throw new InvalidArgumentException( 'Lenght must be greater than '.strlen( $mask ) );

		$string	= trim( $string );
		if( !( $length && strlen( $string ) > $length ) )
			return $string;

		$range	= ( $length - strlen( $mask ) ) / 2;
		$left	= substr( $string, 0, ceil( $range ) );
		$right	= substr( $string, -floor( $range ) );

		return $left.$mask.$right;
	}
}
?>