<?php
import( 'de.ceus-media.alg.validation.PredicateValidator' );
/**
 *	Validator for defined Fields.
 *	@package		alg.validation
 *	@extends		Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.1
 */
/**
 *	Validator for defined Fields.
 *	@package		alg.validation
 *	@extends		Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.1
 */
class Alg_Validation_DefinitionValidator
{
	/**	@var		array		$labels			Field Labels */
	protected $labels	= array();
	/**	@var		array		$messages		Error Messages */
	protected $messages	= array(
		'class'		=> "The Value of Field '%label%' is not correct.",
		'mandatory'	=> "Field '%label%' is mandatory.",
		'minlength'	=> "Minimal Length of Field '%label%' is %edge%.",
		'maxlength'	=> "Maximal Length of Field '%label%' is %edge%.",
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
	);
	/**	@var		Object		Predicate Class Instance */
	protected $validator;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$predicateClass		Class Name of Predicate Class
	 *	@param		string		$validatorClass		Class Name of Predicate Validator Class
	 *	@return		void
	 */
	public function __construct( $predicateClass = "Alg_Validation_Predicates", $validatorClass = "Alg_Validation_PredicateValidator" )
	{
		$this->validator	= new $validatorClass( $predicateClass );
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
	 *	@param		string		$prefix	 	Prefix of Input Field
	 *	@return		array
	 */
	public function validate( $field, $definition, $value, $prefix = "" )
	{
		$errors	= array();
		if( strlen( $value ) )
		{
			if( isset( $definition['syntax']['class'] ) && $definition['syntax']['class'] )
			{
				if( !$this->validator->isClass( $value, $definition['syntax']['class'] ) )
					$errors[]	= $this->handleError( $field, 'class', $value, false, $prefix );
			}
			if( isset( $definition['syntax']['minlength'] ) && $definition['syntax']['minlength'] )
			{
				if( !$this->validator->validate( $value, 'hasMinLength', $definition['syntax']['minlength'] ) )
					$errors[]	= $this->handleError( $field, 'minlength', $value, $definition['syntax']['minlength'], $prefix );
			}
			if( isset( $definition['syntax']['maxlength'] ) && $definition['syntax']['maxlength'] )
			{
				if( !$this->validator->validate( $value, 'hasMaxLength', $definition['syntax']['maxlength'] ) )
					$errors[]	= $this->handleError( $field, 'maxlength', $value, $definition['syntax']['maxlength'], $prefix );
			}
		}
		else if( isset( $definition['syntax']['mandatory']  && $definition['syntax']['mandatory'] )
			$errors[]	= $this->handleError( $field, 'mandatory', $value, false, $prefix );
	
		if( isset( $definition['semantic'] ) )
		{
			foreach( $definition['semantic'] as $semantic )
			{
				$param	= array( "'".$value."'" );
				if( strlen( $semantic['edge'] ) )
					$param[]	= "'".$semantic['edge']."'";
				$param	= implode( ",", $param );
				if( !$this->validator->validate( $value, $semantic['predicate'], $param ) )
					$errors[]	= $this->handleError( $field, $semantic['predicate'], $value, $semantic['edge'], $prefix );
			}
		}
		return $errors;
	}
	
	/**
	 *	Returns Label of Field.
	 *	@access		protected
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
	 *	@access		protected
	 *	@param		string		$field		Field
	 *	@param		string		$key		Validator Key
	 *	@param		string		$value		Value to validate
	 *	@param		string		$edge		At least accepted Value
	 *	@param		string		$prefix	 	Prefix of Input Field
	 *	@return		string
	 */
	protected function handleError( $field, $key, $value, $edge = false, $prefix = "" )
	{
		$msg	= $this->messages[$key];
		$msg	= str_replace( "%validator%", $key, $msg );
		$msg	= str_replace( "%label%", $this->getLabel( $field ), $msg );
		$msg	= str_replace( "%field%", $field, $msg );
		$msg	= str_replace( "%value%", $value, $msg );
		$msg	= str_replace( "%edge%", $edge, $msg );
		$msg	= str_replace( "%prefix%", $prefix, $msg );
		return $msg;
	}
}
?>