<?php
/**
 *	Validator for defined Fields.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		alg.validation
 *	@extends		Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.08.2006
 *	@version		0.1
 */
import( 'de.ceus-media.alg.validation.PredicateValidator' );
/**
 *	Validator for defined Fields.
 *	@package		alg.validation
 *	@extends		Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.08.2006
 *	@version		0.1
 */
class Alg_Validation_DefinitionValidator
{
	/**	@var		array		$labels			Field Labels */
	protected $labels	= array();
	/**	@var		array		$messages		Error Messages */
	protected $messages	= array(
		'isClass'				=> "The Value of Field '%label%' is not correct.",
		'isMandatory'			=> "Field '%label%' is mandatory.",

		'hasMinLength'			=> "Field '%label%' must be at least %edge% characters long.",
		'hasMaxLength'			=> "Field '%label%' must be at most %edge% characters long.",
		'hasValue'				=> "Field '%label%' must have a value.",
		'hasPasswordStrength'	=> "Field '%label%' must have a stronger password.",

		'isGreater'				=> "Field '%label%' must be greater than %edge%.",
		'isLess'				=> "Field '%label%' must be less than %edge%.",
		'isMinimum'				=> "Field '%label%' must be at least %edge%.",
		'isMaximum'				=> "Field '%label%' must be at most %edge%.",

		'isAfter'				=> "Field '%label%' must be after %edge%.",
		'isBefore'				=> "Field '%label%' must be before %edge%.",
		'isPast'				=> "Field '%label%' must be in past.",
		'isFuture'				=> "Field '%label%' must be in future.",

		'isEmail'				=> "Field '%label%' must be a valid eMail address.",
		'isId'					=> "Field '%prefix%%label%' must be a valid ID.",
		'isURL'					=> "Field '%label%' must be a vaild URL.",

		'isPreg'				=> "Field '%label%' is not valid.",
		'isEreg'				=> "Field '%label%' is not valid.",
		'isEregi'				=> "Field '%label%' is not valid.",
		
		'isAlpha'				=> "Field '%label%' must only contain letters and digits.",
		'isAlphaspace'			=> "Field '%label%' must only contain letters, digits and white spaces.",
		'isAlphasymbol'			=> "Field '%label%' must only contain letters, digits and symbols.",
		'isAlphahyphen'			=> "Field '%label%' must only contain letters, digits and hyphen.",
		'isDotnumeric'			=> "Field '%label%' must be a number (with one dot allowed only).",
		'isDigit'				=> "Field '%label%' must only contain digits.",
		'isFloat'				=> "Field '%label%' must be a floating number.",
		'isLetter'				=> "Field '%label%' must only contain letters.",
		'isNumeric'				=> "Field '%label%' must only contain digits.",

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
		if( $key == "isClass" )
			if( isset( $this->messages["is".ucfirst( $edge )] ) )
				$msg	= $this->messages["is".ucfirst( $edge )];
		$msg	= str_replace( "%validator%", $key, $msg );
		$msg	= str_replace( "%label%", $this->getLabel( $field ), $msg );
		$msg	= str_replace( "%field%", $field, $msg );
		$msg	= str_replace( "%value%", $value, $msg );
		$msg	= str_replace( "%edge%", $edge, $msg );
		$msg	= str_replace( "%prefix%", $prefix, $msg );
		return $msg;
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
		$errors		= array();
		$syntax		= new ArrayObject( $definition['syntax'] );
		$semantics	= isset( $definition['semantic'] ) ? $definition['semantic'] : array();

		if( !strlen( $value ) && $syntax['mandatory'] )
		{
			$errors[]	= $this->handleError( $field, 'isMandatory', $value, NULL, $prefix );
			return $errors;
		}

		if( $syntax['class'] )
			if( !$this->validator->isClass( $value, $syntax['class'] ) )
				$errors[]	= $this->handleError( $field, 'isClass', $value, $syntax['class'], $prefix );

		$predicates	= array(
			'maxlength'	=> "hasMaxLength",
			'minlength'	=> "hasMinLength",
		);
		foreach( $predicates as $key => $predicate )
			if( $syntax[$key] )
				if( !$this->validator->validate( $value, $predicate, $syntax[$key] ) )
					$errors[]	= $this->handleError( $field, $predicate, $value, $syntax[$key], $prefix );

		foreach( $semantics as $semantic )
		{
			$semantic	= new ArrayObject( $semantic );
			$param	= strlen( $semantic['edge'] ) ? $semantic['edge'] : NULL;
			if( !$this->validator->validate( $value, $semantic['predicate'], $param ) )
				$errors[]	= $this->handleError( $field, $semantic['predicate'], $value, $param, $prefix );
		}
		return $errors;
	}
}
?>