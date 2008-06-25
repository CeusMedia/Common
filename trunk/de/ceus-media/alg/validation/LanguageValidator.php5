<?php
/**
 *	Validator for Languages (ISO).
 *	@package		alg.validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Validator for Languages (ISO).
 *	@package		alg.validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Alg_Validation_LanguageValidator
{
	/**	@var		string		$allowed		Array of allowed Languages */
	protected $allowed;
	/**	@var		string		$default		Default Language */
	protected $default;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$allowed		List of allowed Languages
	 *	@param		string		$default		Default Language
	 *	@return		void
	 */
	public function __construct( $allowed, $default = NULL )
	{
		if( !is_array( $allowed ) )
			throw new InvalidArgumentException( 'First Argument must be an Array.' );
		if( !count( $allowed ) )
			throw new RangeException( 'At least one Language must be allowed.' );
		$this->allowed	= $allowed;
		if( $default )
		{
			if( !in_array( $default, $allowed ) )
				throw new Exception( 'Default Language must be an allowed Language.' );
			$this->default	= $default;
		}
		else
			$this->default = $this->allowed[0];
	}

	/**
	 *	Returns prefered allowed and accepted Language.
	 *	@access		public
	 *	@param		string	$language		Language to prove
	 *	@return		string
	 */
	public function getLanguage( $language )
	{
		$pattern		= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		if( !$language )
			return $this->default;
		$accepted	= preg_split( '/,\s*/', $language );
		$curr_lang	= $this->default;
		$curr_qual	= 0;
		foreach( $accepted as $accept)
		{
			if( !preg_match ( $pattern, $accept, $matches) )
				continue;
			$lang_code = explode ( '-', $matches[1] );
			$lang_quality =  isset( $matches[2] ) ? (float)$matches[2] : 1.0;
			while (count ($lang_code))
			{
				if( in_array( strtolower( join( '-', $lang_code ) ), $this->allowed ) )
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
	
	/**
	 *	Validates Language statically and returns valid Language.
	 *	@access		public
	 *	@param		string		$language		Language to validate
	 *	@param		array		$allowed		List of allowed Languages
	 *	@param		string		$default		Default Language
	 *	@return		string
	 */
	public static function validate( $language, $allowed, $default = NULL )
	{
		$validator	= new Alg_Validation_LanguageValidator( $allowed, $default );
		$language	= $validator->getLanguage( $language );
		return $language;
	}
}
?>