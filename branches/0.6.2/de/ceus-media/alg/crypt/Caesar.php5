<?php
/**
 *	Caesar Encryption.
 *	@package		alg.crypt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.4.2005
 *	@version		0.6
 */
/**
 *	Caesar Encryption.
 *	@package		alg.crypt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.4.2005
 *	@version		0.6
 */
class Alg_Crypt_Caesar
{
	/**
	 *	Realizes Encryption/Decryption of a text with the normal/inversed Key.
	 *	@access		public
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	protected static function crypt( $string, $key )
	{
		for( $i=0; $i<strlen( $string ); $i++ )
		{
			$char = ord( $string[$i] );
			if( $char > 64 && $char < 91 )
			{
				$char += $key;
				if( $char > 90 )
					$char -= 26;
				else if( $char < 65 )
					$char += 26;
			}
			else if( $char > 96 && $char < 123 )
			{
				$char += $key;
				if ($char > 122)
					$char -= 26;
				else if( $char < 97 )
					$char += 26;
			}
			$string[$i] = chr( $char );
		}
		return $string;
	}

	/**
	 *	Decrypts a String.
	 *	@access		public
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	public static function decrypt( $string, $key )
	{
		return self::crypt( $string, -1 * $key );
	}

	/**
	 *	Encrypts a String.
	 *	@access		public
	 *	@param		string		$string		String to be encrypted
	 *	@param		int			$key		Rotation Key
	 *	@return		string
	 */
	public static function encrypt( $string, $key )
	{
		return self::crypt( $string, $key );
	}
}
?>