<?php
/**
 *	Validator for defined Fields.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Validation;

use ArrayObject;
use InvalidArgumentException;

/**
 *	Validator for defined Fields.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DefinitionValidator
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
	public function __construct( $predicateClass = NULL, $validatorClass = NULL )
	{
		$predicateClass		??= Predicates::class;
		$validatorClass		??= PredicateValidator::class;
		$this->validator	= new $validatorClass( $predicateClass );
	}

	/**
	 *	Validates Syntax against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		array		$definition		Field Definition
	 *	@param		string		$value			Value to validate
	 *	@return		array
	 */
	public function validate( array $definition, string $value ): array
	{
		$errors		= [];
		if( !empty( $definition['syntax'] ) ){
			$syntax		= new ArrayObject( $definition['syntax'] );

			if( !strlen( $value ) ){
				if( $syntax['mandatory'] )
					$errors[]	= ['isMandatory', NULL];
				return $errors;
			}

			if( $syntax['class'] )
				if( !$this->validator->isClass( $value, $syntax['class'] ) )
					$errors[]	= ['isClass', $syntax['class']];

			$predicates	= [
				'maxlength'	=> 'hasMaxLength',
				'minlength'	=> 'hasMinLength',
			];
			foreach( $predicates as $key => $predicate )
				if( $syntax[$key] )
					if( !$this->validator->validate( $value, $predicate, $syntax[$key] ) )
						$errors[]	= [$predicate, $syntax[$key]];
		}

		if( !empty( $definition['semantic'] ) ){
			foreach( $definition['semantic'] as $semantic ){
				$semantic	= new ArrayObject( $semantic );
				$param	= strlen( $semantic['edge'] ) ? $semantic['edge'] : NULL;
				if( !$this->validator->validate( $value, $semantic['predicate'], $param ) )
					$errors[]	= [$semantic['predicate'], $param];
			}
		}
		return $errors;
	}
}
