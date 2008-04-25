<?php
/**
 *	Converts a String into UTF-8.
 *	@package		
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
/**
 *	Converts a String into UTF-8.
 *	@package		
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
class Alg_StringUnicoder
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string		String to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( $string, $force = false )
	{
		$this->string	= self::convertToUnicode( $string, $force );
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	static function isUnicode( $string )
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
	static function convertToUnicode( $string, $force = false )
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
		return $this->string();
	}
	
	/**
	 *	Returns unicoded String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		return $this->string();
	}
}
?>