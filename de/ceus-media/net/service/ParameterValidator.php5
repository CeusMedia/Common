<?php
/**
 *	Validator for Service Parameters.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.01.2008
 *	@version		0.6
 */
/**
 *	Validator for Service Parameters.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.01.2008
 *	@version		0.6
 */
class Net_Service_ParameterValidator
{
	/**
	 *	Validates a Parameter Value from Request by calling Validator Methods for Parameter Rules and throwing Exceptions.
	 *	@access		public
	 *	@param		array		$rules			Parameter Rules
	 *	@param		string		$value			Parameter Value from Request
	 *	@return		void
	 */
	public static function validateParameterValue( $rules, $value )
	{
		try
		{
			foreach( $rules as $ruleName => $ruleValue )
			{
				if( $ruleValue )
					self::callMethod( "check".ucFirst( $ruleName ), $value, $ruleValue );
			}
		}
		catch( InvalidArgumentException $e )
		{
			throw new InvalidArgumentException( $ruleName );
		}
	}
	
	/**
	 *	Calls Validator Method and throws Exception if Validation failed.
	 *	@access		protected
	 *	@param		string		$method			Validation Method to call
	 *	@param		string		$value			Value to validate
	 *	@param		string		$measure		Measure to validate against
	 *	@return		bool
	 */
	protected static function callMethod( $method, $value, $measure = NULL )
	{
		if( !method_exists( __CLASS__, $method ) )
			throw new BadMethodCallException( "Service Parameter Validator Method '".$method."' is not existing." );
		if( !self::$method( $value, $measure ) )
			throw new InvalidArgumentException( $method );
		return true;
	}
	
	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMandatory( $value )
	{
		if( is_string( $value ) && strlen( $value ) )
			return TRUE;
		else if( is_array( $value ) && count( $value ) )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMinlength( $value, $measure )
	{
		if( strlen( $value ) >= $measure )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMaxlength( $value, $measure )
	{
		if( strlen( $value ) <= $measure )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkPreg( $value, $measure )
	{
		return preg_match( $measure, $value );
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkType( $value, $measure )
	{
		$type	= gettype( $value );
		if( $type == $measure )
			return TRUE;
		switch( $measure )
		{
			case 'boolean':	return $value == NULL;
		}
		return FALSE;
	}
}
?>