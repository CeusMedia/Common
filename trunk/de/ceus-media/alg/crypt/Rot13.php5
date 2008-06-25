<?php
/**
 *	Wrapper of ROT13 Functions
 *	@package		alg.crypt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.02.2008
 *	@version		0.6
 */
/**
 *	Wrapper of ROT13 Functions
 *	@package		alg.crypt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.02.2008
 *	@version		0.6
 */
class Alg_Crypt_Rot13
{
	/**
	 *	Encrypts a String with ROT13.
	 *	@access		public
	 *	@param		string		$string		String to be encrypted.
	 *	@returns	string
	 */
	public static function encrypt( $string )
	{
		return str_rot13( $string );
	}

	/**
	 *	Decrypts a String encrypted with ROT13.
	 *	@access		public
	 *	@param		string		$string		String to be decrypted.
	 *	@returns	string
	 */
	public static function decrypt( $string )
	{
		return str_rot13( $string );
	}
}
?>