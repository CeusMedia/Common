<?php
/**
 *	Validator for Service Parameters.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.01.2008
 *	@version		0.6
 */
/**
 *	Validator for Service Parameters.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.01.2008
 *	@version		0.6
 */
class ParameterValidator
{
	protected $rules	= array();
	
	public function validateFieldValue( $rules, $value )
	{
		try
		{
			foreach( $rules as $ruleName => $ruleValue )
			{
				if( $ruleValue )
					$this->callMethod( "check".ucFirst( $ruleName ), $value, $ruleValue );
			}
		}
		catch( InvalidArgumentException $e )
		{
			throw new InvalidArgumentException( $ruleName );
		}
	}
	
	protected function callMethod( $method, $value, $measure = false)
	{
		if( !method_exists( $this, $method ) )
			throw new BadMethodCallException( "Service Parameter Validator Method '".$method."' is not existing." );
		if( !$this->$method( $value, $measure ) )
			throw new InvalidArgumentException( $method );
		return true;
	}
	
	protected function checkMandatory( $value )
	{
		if( strlen( $value ) )
			return true;
	}

	protected function checkMinlength( $value, $measure )
	{
		if( strlen( $value ) >= $measure )
			return true;
	}

	protected function checkMaxlength( $value, $measure )
	{
		if( strlen( $value ) <= $measure )
			return true;
	}

	protected function checkPreg( $value, $measure )
	{
		return preg_match( $measure, $value );
	}
}
?>