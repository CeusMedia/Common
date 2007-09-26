<?php
/**
 *	Validator for Languages (ISO).
 *	@package	validation
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		12.08.2005
 *	@version		0.1
 */
/**
 *	Validator for Languages (ISO).
 *	@package	validation
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		12.08.2005
 *	@version		0.1
 */
class LanguageValidator
{
	/**	@var	string	$_allowed		Array of allowed Languages */
	var $_allowed;
	/**	@var	string	$_default		Default Language */
	var $_default;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$allowed		Array of allowed Languages
	 *	@param		string		$default		Default Language
	 *	@return		void
	 */
	public function __construct( $allowed, $default = false )
	{
		if( !is_array( $allowed ) )
			trigger_error( "First Argument must be an Array.", E_USER_ERROR );
		if( !count( $allowed ) )
			trigger_error( "At least one Language must be allowed.", E_USER_ERROR );
		$this->_allowed	= $allowed;
		if( $default )
		{
			if( !in_array( $default, $allowed ) )
				trigger_error( "Default Language must be an allowed Language.", E_USER_ERROR );
			else
				$this->_default	= $default;
		}
		else
			$this->_default = $this->_allowed[0];
	}

	/**
	 *	Returns prefered allowed and accepted Language.
	 *	@access		public
	 *	@param		string	$language		Language to prove
	 *	@return		string
	 */
	function getLanguage( $language )
	{
		$pattern		= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		if( !$language )
			return $this->_default;
		$accepted	= preg_split( '/,\s*/', $language );
		$curr_lang	= $this->_default;
		$curr_qual	= 0;
		foreach( $accepted as $accept)
		{
			if( !preg_match ( $pattern, $accept, $matches) )
				continue;
			$lang_code = explode ( '-', $matches[1] );
			$lang_quality =  isset( $matches[2] ) ? (float)$matches[2] : 1.0;
			while (count ($lang_code))
			{
				if( in_array( strtolower( join( '-', $lang_code ) ), $this->_allowed ) )
				{
					if( $lang_quality > $curr_qual )
					{
						$curr_lang	= strtolower( join( '-', $lang_code ) );
						$curr_qual	= $lang_quality;
						break;
					}
				}
				array_pop ($lang_code);
			}
		}
		return $curr_lang;
	}
}
?>