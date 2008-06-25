<?php
import( 'de.ceus-media.alg.StringUnicoder' );
/**
 *	Converts a File into UTF-8.
 *	@package		
 *	@uses			Alg_StringUnicoder
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
/**
 *	Converts a File into UTF-8.
 *	@package		
 *	@uses			Alg_StringUnicoder
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
class File_Unicoder
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public function __construct( $fileName, $force = false )
	{
		return self::convertToUnicode( $fileName, $force = false );
	}

	/**
	 *	Check whether a String is encoded into UTF-8.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@return		bool
	 */
	public static function isUnicode( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );
		$string		= file_get_contents( $fileName );
		$unicoded	= Alg_StringUnicoder::convertToUnicode( $string );
		return $unicoded == $string;
	}

	/**
	 *	Converts a String to UTF-8.
	 *	@access		public
	 *	@param		string		$fileName	Name of File to unicode
	 *	@param		bool		$force		Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		bool
	 */
	public static function convertToUnicode( $fileName, $force = false )
	{
		if( !(!$force && self::isUnicode( $fileName ) ) )
		{
			$string		= file_get_contents( $fileName );
			$unicoded	= Alg_StringUnicoder::convertToUnicode( $string );
			return (bool) file_put_contents( $fileName, $unicoded );
		}
		return false;
	}
	
#	public function convert()
#	{
#	
#	}
}
?>