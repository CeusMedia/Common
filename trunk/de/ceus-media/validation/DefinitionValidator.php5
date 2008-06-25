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
	protected $labels	= array();
	/**	@var		array		$_syntax_keys	Keys of Syntax Validator */
	protected $syntax_keys	= array(
		"class",
		"mandatory",
		"minlength",
		"maxlength"
		);
	/**	@var		array		$_messages		Error Messages */
	protected $messages	= array(
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
		$this->cv	= new CountValidator;
		$this->sv	= new SemanticValidator;
	}

	/**
	 *	Sets Field Labels for Messages.
	 *	@access		public
	 *	@param		array		$labels		Labels of Fields 
	 *	@return		void
	 */
	public function setLabels( $labels )
	{
		$this->labels	= $labels;
	}
	
	/**
	 *	Sets Messages.
	 *	@access		public
	 *	@param		array		$messages	Messages for Errors
	 *	@return		void
	 */
	public function setMessages( $messages )
	{
		$this->messages	= $messages;	
	}
	
	/**
	 *	Validates Syntax against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@return		array
	 */
	public function validateSyntax( $field, $data, $value )
	{
		$errors	= array();
		if( strlen( $value ) )
		{
			if( isset( $data['syntax']['class'] ) && !$this->cv->validate2( $value, 'class', $data['syntax']['class'] ) )
				$errors[]	= $this->handleSyntaxError( $field, 'class', $value );
			if( isset( $data['syntax']['minlength'] ) && $data['syntax']['minlength'] && !$this->cv->validate2( $value, 'minlength', $data['syntax']['minlength'] ) )
				$errors[]	= $this->handleSyntaxError( $field, 'minlength', $value, $data['syntax']['minlength'] );
			if( isset( $data['syntax']['maxlength'] ) && $data['syntax']['maxlength'] && !$this->cv->validate2( $value, 'maxlength', $data['syntax']['maxlength'] ) )
				$errors[]	= $this->handleSyntaxError( $field, 'maxlength', $value, $data['syntax']['maxlength'] );
		}
		else if( $data['syntax']['mandatory'] )
			$errors[]	= $this->handleSyntaxError( $field, 'mandatory', $value );
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
	public function validateSemantics( $field, $data, $value )
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
				$method = "return \$this->sv->".$semantic['predicate']."(".$param.");";
				if( !eval( $method ) )
					$errors[]	= $this->handleSemanticError( $field, $semantic['predicate'], $value, $semantic['edge'] );
			}
		}
		return $errors;
	}
	
	/**
	 *	Returns Label of Field.
	 *	@access		private
	 *	@param		string		$field		Field
	 *	@return		string
	 */
	protected function getLabel( $field )
	{
		if( isset( $this->labels[$field] ) )
			return $this->labels[$field];
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
	protected function handleSyntaxError( $field, $key, $value, $edge = false )
	{
		$msg	= $this->messages['syntax'][$key];
		$msg	= str_replace( "%validator%", $key, $msg );
		$msg	= str_replace( "%label%", $this->getLabel( $field ), $msg );
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
	protected function handleSemanticError( $field, $predicate, $value, $edge = false )
	{
		$msg	= $this->messages['semantic'][$predicate];
		$msg	= str_replace( "%validator%", $predicate, $msg );
		$msg	= str_replace( "%label%", $this->getLabel( $field ), $msg );
		$msg	= str_replace( "%field%", $field, $msg );
		$msg	= str_replace( "%value%", $value, $msg );
		$msg	= str_replace( "%edge%", $edge, $msg );
		return $msg;
	}
}
?>