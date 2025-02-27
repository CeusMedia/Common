<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Calls Object or Class Methods using Reflection.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Obj;

use CeusMedia\Common\Alg\Obj\Factory as ObjectFactory;
use BadMethodCallException;
use InvalidArgumentException;
use ReflectionException;
use ReflectionObject;
use RuntimeException;

/**
 *	Calls Object or Class Methods using Reflection.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MethodFactory
{
	protected array $arguments;

	protected ?string $method		= NULL;

	protected ?object $object		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		object|NULL		$object			Object to call method on
	 *	@param		string|NULL		$method			Name of method to call
	 *	@param		array			$arguments		List of method arguments
	 */
	public function __construct( ?object $object = NULL, ?string $method = NULL, array $arguments = [] )
	{
		if( NULL !== $object )
			$this->setObject( $object );
		if( NULL !== $method )
			$this->setMethod( $method, $arguments );
	}

	/**
	 *	Call set method with arguments on set object.
	 *	@access		public
	 *	@param		boolean			$checkMethod		Flag: check if methods exists by default, disable for classes using __call
	 *	@param		boolean			$allowProtected		Flag: allow invoking protected and private methods (PHP 5.3.2+), default: no
	 *	@return		mixed			Result of called method
	 *	@throws		ReflectionException
	 */
	public function call( bool $checkMethod = TRUE, bool $allowProtected = FALSE )
	{
		if( NULL === $this->method )
			throw new RuntimeException( 'No method set' );
		return $this->callMethod( $this->method, $this->arguments, $checkMethod, $allowProtected );
	}

	/**
	 *	Call method with arguments on prior set object.
	 *	@access		public
	 *	@param		string			$name				Name of method to call on object
	 *	@param		array			$arguments			List of method arguments
	 *	@return		mixed			Result of called Method
	 *	@throws		RuntimeException					if neither object not class is set
	 *	@throws		BadMethodCallException				if an invalid Method is called
	 *	@throws		ReflectionException
	 */
	public function callMethod( string $name, array $arguments = [], bool $checkMethod = TRUE, bool $allowProtected = FALSE )
	{
		if( NULL === $this->object )
			throw new RuntimeException( 'Neither object nor class set' );
		return self::staticCallObjectMethod( $this->object, $name, $arguments, $checkMethod, $allowProtected );
	}


	/**
	 *	Set class and invocation arguments of object to call method on.
	 *	@access		public
	 *	@param		string		$name			Name of class to set
	 *	@param		array		$arguments		Class arguments for invocation
	 *	@return		self
	 *	@throws		ReflectionException
	 */
	public function setClass( string $name, array $arguments = [] ): self
	{
		if( !class_exists( $name ) )
			throw new RuntimeException( 'Class "'.$name.'" has not been loaded' );
		return $this->setObject( Factory::createObject( $name, $arguments ) );
	}

	/**
	 *	Set method to call and its arguments.
	 *	@access		public
	 *	@param		string		$method			Name of method to call
	 *	@param		array		$arguments		List of arguments on method call
	 *	@return		self
	 */
	public function setMethod( string $method, array $arguments = [] ): self
	{
		$this->method		= $method;
		$this->arguments	= $arguments;
		return $this;
	}

	/**
	 *	Set object to call method on.
	 *	@access		public
	 *	@param		object		$object			Object to call method on
	 *	@return		self
	 */
	public function setObject( object $object ): self
	{
		$this->object	= $object;
		return $this;
	}

	//  --  STATIC  --  //

	/**
	 *	Calls a Method from a Class or Object with Method Parameters and Object Parameters if a Class is given.
	 *	@access		public
	 *	@static
	 *	@param		string|object	$mixed				Class Name or Object
	 *	@param		string			$methodName			Name of Method to call
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@param		array			$classParameters	List of Parameters for Object Construction if Class is given
	 *	@param		boolean			$checkMethod		Flag: check if methods exists by default, disable for classes using __call
	 *	@param		boolean			$allowProtected		Flag: allow invoking protected and private methods (PHP 5.3.2+), default: no
	 *	@return		mixed			Result of called Method
	 */
/*	public static function staticCall( $mixed, string $methodName, array $methodParameters = [], array $classParameters = [], bool $checkMethod = TRUE, bool $allowProtected = FALSE )
	{
		if( is_object( $mixed ) )
			return self::staticCallObjectMethod( $mixed, $methodName, $methodParameters, $checkMethod, $allowProtected );
		return self::staticCallClassMethod( $mixed, $methodName, $classParameters, $methodParameters, $checkMethod, $allowProtected );
	}*/

	/**
	 *	Creates an instance of a class using Reflection.
	 *	@access		public
	 *	@static
	 *	@param		string			$className			Name of Class
	 *	@param		string			$methodName			Name of Method to call
	 *	@param		array			$classParameters	List of Parameters for Object Construction
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@param		boolean			$checkMethod		Flag: check if methods exists by default, disable for classes using __call
	 *	@param		boolean			$allowProtected		Flag: allow invoking protected and private methods (PHP 5.3.2+), default: no
	 *	@return		mixed			Result of called Method
	 *	@throws		ReflectionException
	 *	@throws		RuntimeException
	 */
	public static function staticCallClassMethod( string $className, string $methodName, array $classParameters = [], array $methodParameters = [], bool $checkMethod = TRUE, bool $allowProtected = FALSE )
	{
		if( !class_exists( $className ) )
			throw new RuntimeException( 'Class "'.$className.'" has not been loaded' );
		$object		= ObjectFactory::createObject( $className, $classParameters );
		return self::staticCallObjectMethod( $object, $methodName, $methodParameters, $checkMethod, $allowProtected );
	}

	/**
	 *	Calls Class or Object Method.
	 *	@access		public
	 *	@static
	 *	@param		object			$object				Object to call Method of
	 *	@param		string			$methodName			Name of Method to call
	 *	@param		array			$parameters			List of Parameters for Method Call
	 *	@param		boolean			$checkMethod		Flag: check if methods exists by default, disable for classes using __call
	 *	@param		boolean			$allowProtected		Flag: allow invoking protected and private methods (PHP 5.3.2+), default: no
	 *	@return		mixed			Result of called Method
	 *	@throws		InvalidArgumentException			if no object is given
	 *	@throws		BadMethodCallException				if an invalid Method is called
	 *	@throws		ReflectionException
	 */
	public static function staticCallObjectMethod( object $object, string $methodName, array $parameters = [], bool $checkMethod = TRUE, bool $allowProtected = FALSE )
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Invalid object' );

		//  get Object Reflection
		$reflection	= new ReflectionObject( $object );
		//  called Method is not existing
		if( $checkMethod && !$reflection->hasMethod( $methodName ) ){
			//  prepare Exception Message
			$message	= 'Method '.$reflection->getName().'::'.$methodName.' is not existing';
			//  throw Exception
			throw new BadMethodCallException( $message );
		}

		if( $reflection->hasMethod( $methodName ) ){
			$method		= $reflection->getMethod( $methodName );
		}
		else{
			$method		= $reflection->getMethod( '__call' );
			$parameters	= [
				$methodName,
				$parameters,
			];
		}
		if( $allowProtected && version_compare( PHP_VERSION, '5.3.2' ) >= 0 )
			//  @see http://php.net/manual/de/reflectionmethod.setaccessible.php
			$method->setAccessible( TRUE );
		//  if Method Parameters are set
		if( $parameters )
			//  invoke Method with Parameters
			return $method->invokeArgs( $object, $parameters );
		//  else invoke Method without Parameters
		return $method->invoke( $object );
	}
}
