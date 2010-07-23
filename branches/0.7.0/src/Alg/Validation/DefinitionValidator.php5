<?php
/**
 *	Validator for defined Fields.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.08.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.alg.validation.PredicateValidator' );
/**
 *	Validator for defined Fields.
 *	@category		cmClasses
 *	@package		Alg.Validation
 *	@extends		Alg_Validation_PredicateValidator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.08.2006
 *	@version		$Id$
 */
class Alg_Validation_DefinitionValidator
{
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
	 *	Validates Syntax against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$fieldKey	Field Key in Definition
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@param		string		$fieldName	Field Name in Form
	 *	@return		array
	 */
	public function validate( $definition, $value )
	{
		if( !is_array( $definition ) )
			throw new InvalidArgumentException( 'Definition must be an array, '.gettype( $definition ).' given' );
		$errors		= array();
		if( !empty( $definition['syntax'] ) )
		{
			$syntax		= new ArrayObject( $definition['syntax'] );

			if( !strlen( $value ) )
			{
				if( $syntax['mandatory'] )
					$errors[]	= array( 'isMandatory', NULL );
				return $errors;
			}

			if( $syntax['class'] )
				if( !$this->validator->isClass( $value, $syntax['class'] ) )
					$errors[]	= array( 'isClass', $syntax['class'] );

			$predicates	= array(
				'maxlength'	=> 'hasMaxLength',
				'minlength'	=> 'hasMinLength',
			);
			foreach( $predicates as $key => $predicate )
				if( $syntax[$key] )
					if( !$this->validator->validate( $value, $predicate, $syntax[$key] ) )
						$errors[]	= array( $predicate, $syntax[$key] );
		}
	
		if( !empty( $definition['semantic'] ) )
		{
			foreach( $definition['semantic'] as $semantic )
			{
				$semantic	= new ArrayObject( $semantic );
				$param	= strlen( $semantic['edge'] ) ? $semantic['edge'] : NULL;
				if( !$this->validator->validate( $value, $semantic['predicate'], $param ) )
					$errors[]	= array( $semantic['predicate'], $param );
			}
		}
		return $errors;
	}
}
?>