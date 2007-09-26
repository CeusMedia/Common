<?php
class Validation_Exception extends Exception
{
}

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
		catch( Validation_Exception $e )
		{
			throw new Validation_Exception( $ruleName );
		}
	}
	
	protected function callMethod( $method, $value, $measure = false)
	{
		if( !method_exists( $this, $method ) )
			throw new Exception( "Service Parameter Validator Method '".$method."' is not existing." );
		if( !$this->$method( $value, $measure ) )
			throw new Validation_Exception( $method );
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