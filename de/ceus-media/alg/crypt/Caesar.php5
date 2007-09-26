<?php
/**
 *	Caesar Encryption
 *	
 *	@package	alg
 *	@subpackage	crypt
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.4.2005
 *	@version		0.4
 */
/**
 *	Caesar Encryption
 *
 *	@package	alg
 *	@subpackage	crypt
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.4.2005
 *	@version		0.4
 */
class Caesar
{
	/**	@var	int		_key	Key */
	var $_key;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		key		Key
	 */
	public function __construct ($key)
	{
		$this->_key = $key;
	}

	/**
	 *	Realizes Encryption/Decryption of a text with the normal/inversed Key.
	 *	@access		public
	 *	@param		string	text		Text to be encrypted
	 *	@param		string	key		normal or inversed Key 
	 *	@return		string
	 */
	function _crypt ($text, $key)
	{
		for ($i=0; $i<strlen ($text); $i++)
		{
			$char = ord ($text[$i]);
			if ($char > 64 && $char < 91)
			{
				$char += $key;
				if ($char > 90) $char -= 26;
				else	if ($char < 65) $char += 26;
			}
			else if ($char > 96 && $char < 123)
			{
				$char += $key;
				if ($char > 122) $char -= 26;
				else if ($char < 97) $char += 26;
			}
			$text[$i] = chr($char);
		}
		return $text;
	}

	/**
	 *	Decrypts a text.
	 *	@access		public
	 *	@param		string	text		Text to be encrypted
	 *	@return		string
	 */
	function decrypt ($text)
	{
		return $this->_crypt ($text, -1 * $this->_key);
	}

	/**
	 *	Encrypts a text.
	 *	@access		public
	 *	@param		string	text		Text to be encrypted
	 *	@return		string
	 */
	function encrypt ($text)
	{
		return $this->_crypt ($text, $this->_key);
	}
}
?>