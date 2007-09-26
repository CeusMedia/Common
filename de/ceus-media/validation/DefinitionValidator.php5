<?php
import( 'de.ceus-media.validation.CountValidator' );
import( 'de.ceus-media.validation.SemanticValidator' );
/**
 *	Validator for defined Fields.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.1
 */
/**
 *	Validator for defined Fields.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.1
 */
class DefinitionValidator
{
	/**	@var		array		$_labels			Field Labels */
	var $_labels	= array();
	/**	@var		array		$_syntax_keys	Keys of Syntax Validator */
	var $_syntax_keys	= array(
		"class",
		"mandatory",
		"minlength",
		"maxlength"
		);
	/**	@var		array		$_messages		Error Messages */
	var $_messages	= array(
		'syntax'	=> array(
			'class'		=> "The Value of Field '%label%' is not correct.",
			'mandatory'	=> "Field '%label%' is mandatory.",
			'minlength'	=> "Minimal Length of Field '%label%' is %edge%.",
			'maxlength'	=> "Maximal Length of Field '%label%' is %edge%.",
			),
		'semantic'	=> array(
			'hasValue'	=> "Field '%label%' muss have a value.",
			'isGreater'	=> "Field '%label%' must be greater than %edge%.",
			'isLess'	=> "Field '%label%' must be less than %edge%.",
			'isAfter'	=> "Field '%label%' must be after %edge%.",
			'isBefore'	=> "Field '%label%' must be before %edge%.",
			'isPast'	=> "Field '%label%' must be in past.",
			'isFuture'	=> "Field '%label%' must be in future.",
			'isURL'		=> "Field '%label%' must be a vaild URL.",
			'isEmail'	=> "Field '%label%' must be a valid eMail address.",
			'isPreg'	=> "Field '%label%' is not valid.",
			'isEreg'	=> "Field '%label%' is not valid.",
			'isEregi'	=> "Field '%label%' is not valid.",
			'isEregi'	=> "Das Feld '%label%' ist nicht korrekt.",
			),
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_cv	= new CountValidator;
		$this->_sv	= new SemanticValidator;
	}

	/**
	 *	Sets Field Labels for Messages.
	 *	@access		public
	 *	@param		array		$labels		Labels of Fields 
	 *	@return		void
	 */
	function setLabels( $labels )
	{
		$this->_labels	= $labels;
	}
	
	/**
	 *	Sets Messages.
	 *	@access		public
	 *	@param		array		$messages	Messages for Errors
	 *	@return		void
	 */
	function setMessages( $messages )
	{
		$this->_messages	= $messages;	
	}
	
	/**
	 *	Validates Syntax against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@return		array
	 */
	function validateSyntax( $field, $data, $value )
	{
		$errors	= array();
		if( strlen( $value ) )
		{
			if( isset( $data['syntax']['class'] ) && !$this->_cv->validate2( $value, 'class', $data['syntax']['class'] ) )
				$errors[]	= $this->_handleSyntaxError( $field, 'class', $value );
			if( isset( $data['syntax']['minlength'] ) && $data['syntax']['minlength'] && !$this->_cv->validate2( $value, 'minlength', $data['syntax']['minlength'] ) )
				$errors[]	= $this->_handleSyntaxError( $field, 'minlength', $value, $data['syntax']['minlength'] );
			if( isset( $data['syntax']['maxlength'] ) && $data['syntax']['maxlength'] && !$this->_cv->validate2( $value, 'maxlength', $data['syntax']['maxlength'] ) )
				$errors[]	= $this->_handleSyntaxError( $field, 'maxlength', $value, $data['syntax']['maxlength'] );
		}
		else if( $data['syntax']['mandatory'] )
			$errors[]	= $this->_handleSyntaxError( $field, 'mandatory', $value );
		return $errors;
	}
	
	/**
	 *	Validates Semantics against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@return		array
	 */
	function validateSemantics( $field, $data, $value )
	{
		$errors	= array();
		if( isset( $data['semantic'] ) )
		{
			foreach( $data['semantic'] as $semantic )
			{
				$param	= array( "'".$value."'" );
				if( strlen( $semantic['edge'] ) )
					$param[]	= "'".$semantic['edge']."'";
				$param	= implode( ",", $param );
				$method = "return \$this->_sv->".$semantic['predicate']."(".$param.");";
				if( !eval( $method ) )
					$errors[]	= $this->_handleSemanticError( $field, $semantic['predicate'], $value, $semantic['edge'] );
			}
		}
		return $errors;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns Label of Field.
	 *	@access		private
	 *	@param		string		$field		Field
	 *	@return		string
	 */
	function _getLabel( $field )
	{
		if( isset( $this->_labels[$field] ) )
			return $this->_labels[$field];
		return $field;
	}

	/**
	 *	Generated Message for Syntax Error.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$key		Validator Key
	 *	@param		string		$value		Value to validate
	 *	@param		string		[$edge]		At least accepted Value
	 *	@return		string
	 */
	function _handleSyntaxError( $field, $key, $value, $edge = false )
	{
		$msg	= $this->_messages['syntax'][$key];
		$msg	= str_replace( "%validator%", $key, $msg );
		$msg	= str_replace( "%label%", $this->_getLabel( $field ), $msg );
		$msg	= str_replace( "%field%", $field, $msg );
		$msg	= str_replace( "%value%", $value, $msg );
		$msg	= str_replace( "%edge%", $edge, $msg );
		return $msg;
	}

	/**
	 *	Generated Message for Semantics Error.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$predicate	Validator Predicate
	 *	@param		string		$value		Value to validate
	 *	@param		string		[$edge]		At least accepted Value
	 *	@return		string
	 */
	function _handleSemanticError( $field, $predicate, $value, $edge = false )
	{
		$msg	= $this->_messages['semantic'][$predicate];
		$msg	= str_replace( "%validator%", $predicate, $msg );
		$msg	= str_replace( "%label%", $this->_getLabel( $field ), $msg );
		$msg	= str_replace( "%field%", $field, $msg );
		$msg	= str_replace( "%value%", $value, $msg );
		$msg	= str_replace( "%edge%", $edge, $msg );
		return $msg;
	}
}
?>