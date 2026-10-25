<?php

declare(strict_types=1);

/**
 *	Validator for Predicates on Strings.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Validation;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use ReflectionMethod;
use ReflectionNamedType;

/**
 *	Validator for Predicates on Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PredicateValidator
{
	/**	@var		object		Predicate Class Instance */
	protected object $validator;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$predicateClassName		Class Name of Predicate Class
	 *	@return		void
	 */
	public function __construct( ?string $predicateClassName = NULL )
	{
		$predicateClassName	??= Predicates::class;
		$this->validator	= new $predicateClassName();
	}

	/**
	 *	Indicates whether a String is of a specific Character Class.
	 *	@access		public
	 *	@param		mixed		$value		String to be checked
	 *	@param		string		$class		Key of Character Class
	 *	@return		bool
	 */
	public function isClass( mixed $value, string $class ): bool
	{
		$method	= "is".ucFirst( $class );
		if( !method_exists( $this->validator, $method ) )
			throw new BadMethodCallException( 'Predicate "'.$method.'" is not defined.' );
		return $this->callPredicate( $method, [$value, $method] );
	}

	/**
	 *	Indicates whether a String validates against a Predicate.
	 *	@access		public
	 *	@param		mixed		$value		String to be checked
	 *	@param		string		$predicate	Method Name of Predicate
	 *	@param		mixed		$argument	Argument for Predicate
	 *	@return		bool
	 */
	public function validate( mixed $value, string $predicate, mixed $argument = NULL ): bool
	{
		if( !method_exists( $this->validator, $predicate ) )
			throw new BadMethodCallException( 'Predicate "'.$predicate.'" is not defined.' );
		try
		{
			return $this->callPredicate( $predicate, [$value, $argument] );
		}
		catch( InvalidArgumentException $e )
		{
			return false;
		}
		catch( Exception $e )
		{
			throw $e;
		}
	}

	//  --  PROTECTED  --  //

	/**
	 *	Calls predicate method, casting scalar arguments to the declared parameter types.
	 *	Values to validate are often strings (from forms or config files) while predicates may expect integers.
	 *	@access		protected
	 *	@param		string		$method		Method Name of Predicate
	 *	@param		array		$arguments	List of arguments
	 *	@return		bool
	 */
	protected function callPredicate( string $method, array $arguments ): bool
	{
		$parameters	= ( new ReflectionMethod( $this->validator, $method ) )->getParameters();
		$arguments	= array_slice( $arguments, 0, count( $parameters ) );
		foreach( $arguments as $nr => $argument ){
			$type	= $parameters[$nr]->getType();
			if( NULL === $argument || !is_scalar( $argument ) || !$type instanceof ReflectionNamedType )
				continue;
			$arguments[$nr]	= match( $type->getName() ){
				'string'	=> (string) $argument,
				'int'		=> (int) $argument,
				'float'		=> (float) $argument,
				'bool'		=> (bool) $argument,
				default		=> $argument,
			};
		}
		return (bool) $this->validator->$method( ...$arguments );
	}
}
