<?php
import( 'de.ceus-media.validation.CountValidator' );
import( 'de.ceus-media.validation.SemanticValidator' );
/**
 *	Validates against Formular Definition.
 *	@package	validation
 *	@uses		countingValidator
 *	@uses		SemanticValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		03.08.2005
 *	@version		0.4
 */
/**
 *	Validates against Formular Definition.
 *	@package	validation
 *	@uses		countingValidator
 *	@uses		SemanticValidator
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		03.08.2005
 *	@version		0.4
 *	@todo		Code Documentation
 *	@deprecated	? Check Definition Validator
 */
class FormularValidator
{
	var $_messages	= array(
		'syntactic'	=> array(
			'class'		=> "<b>Syntax Error: </b>%field% is not of class %class%.",
			'minlength'	=> "<b>Syntax Error: </b>%field% is shorter than %minlength%.",
			'maxlength'	=> "<b>Syntax Error: </b>%field% is longer than %maxlength%.",
			'mandatory'	=> "<b>Syntax Error: </b>%field% is mandatory.",
			),
		'semantic'		=> array(
			'larger'		=> "<b>Semantic Error: </b>%field% is not larger than %larger%.",
			'smaller'		=> "<b>Semantic Error: </b>%field% is not smaller than %smaller%.",
			'past'		=> "<b>Semantic Error: </b>%field% is not in past.",
			'future'		=> "<b>Semantic Error: </b>%field% is not in future.",
			'before'		=> "<b>Semantic Error: </b>%field% is not before %before%.",
			'after'		=> "<b>Semantic Error: </b>%field% is not after %after%.",
			'url'			=> "<b>Semantic Error: </b>%field% is not an URL.",
			'email'		=> "<b>Semantic Error: </b>%field% is not an eMail address.",
			'preg'		=> "<b>Semantic Error: </b>%field% is not valid (preg).",
			'ereg'		=> "<b>Semantic Error: </b>%field% is not valid (ereg).",
			),
		);
	var $_trigger		= E_USER_ERROR;
	var $_errors		= 0;
	
	public function __construct()
	{
		$this->ref	=& new Reference ();
	}

	function validate( $validators, $vars, $labels = array(), $prefix = '')
	{
		$v = new CountValidator;
		$s = new SemanticValidator;
		$this->_errors = 0;
		
		foreach ($validators as $field => $definitions)
		{
			$syntactic	= is_array( $definitions['syntactic'] ) ? $definitions['syntactic'] : array();
			$semantic	= is_array( $definitions['semantic'] ) ? $definitions['semantic'] : array();
			$param_name	= $prefix.$field;
			$field	= isset( $labels[$field] ) ? $labels[$field] : $field;
			foreach( $syntactic as $key => $value )
				if( !$v->validate2( $vars[$param_name], $key, $value ) )
					user_error( $this->_implant( 'syntactic', $field, $key, $value ), $this->_trigger );
			foreach( $semantic as $key => $value )
				if( !$s->validate( $vars[$param_name], $key, $value  ) )
					user_error( $this->_implant( 'semantic', $field, $key, $value ), $this->_trigger );
		}
		return !$this->_errors;
	}
	
	function _implant( $type, $field, $key, $value )
	{
		$this->_errors++;
		$message	= $this->_messages[$type][$key];
		$needle		= array( "%field%", "%".$key."%" );
		$subst		= array( $field, $value );
		$message	= str_replace( $needle, $subst, $message );
		return $message;
	}
	
	function setMessage( $type, $key, $message )
	{
		$this->_messages[$type][$key] = $message;
	}
	
	function setTrigger( $errno )
	{
		$this->_trigger = $errno;
	}
}
?>
