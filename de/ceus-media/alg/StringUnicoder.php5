<?php
/**
 *	Converts a String into UTF-8.
 *	@package		alg
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
/**
 *	Converts a String into UTF-8.
 *	@package		alg
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
class Alg_StringUnicoder
{
	/**	@var		string		$string		Unicoded String */
	protected $string;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string		String to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( $string, $force = FALSE )
	{
		$this->string	= self::convertToUnicode( $string, $force );
	}
	
	/**
	 *	Returns unicoded String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->string;
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isUnicode( $string )
	{
		$unicoded	= utf8_encode( utf8_decode( $string ) );
		return $unicoded == $string;
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@param		string		$string		String to be converted
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		string
	 */
	public static function convertToUnicode( $string, $force = FALSE )
	{
		if( !( !$force && self::isUnicode( $string ) ) )
			$string	= utf8_encode( $string );
		return $string;
	}
	
	/**
	 *	Returns unicoded String.
	 *	@access		public
	 *	@return		string
	 */
	public function getString()
	{
		return $this->string;
	}
}
?>