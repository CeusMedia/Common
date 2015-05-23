<?php
/**
 *	Calls Object or Class Methods using Reflection.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Alg.Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Calls Object or Class Methods using Reflection.
 *	@category		cmClasses
 *	@package		Alg.Object
 *	@uses			Alg_Object_Factory
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Object_MethodFactory
{
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
	public static function call( $mixed, $methodName, $methodParameters = array(), $classParameters = array(), $checkMethod = TRUE, $allowProtected = FALSE )
	{
		if( is_object( $mixed ) )
			return self::callObjectMethod( $mixed, $methodName, $methodParameters, $checkMethod, $allowProtected );
		return self::callClassMethod( $mixed, $methodName, $classParameters, $methodParameters, $checkMethod, $allowProtected );
	}

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
	 */
	public static function callClassMethod( $className, $methodName, $classParameters = array(), $methodParameters = array(), $checkMethod = TRUE, $allowProtected = FALSE )
	{
		if( !class_exists( $className ) )
			throw new RuntimeException( 'Class "'.$className.'" has not been loaded' );
		$object		= Alg_Object_Factory::createObject( $className, $classParameters );
		return self::callObjectMethod( $object, $methodName, $methodParameters, $checkMethod, $allowProtected );
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
	 */
	public static function callObjectMethod( $object, $methodName, $parameters = array(), $checkMethod = TRUE, $allowProtected = FALSE )
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Invalid object' );

		$reflection	= new ReflectionObject( $object );												//  get Object Reflection
		if( $checkMethod && !$reflection->hasMethod( $methodName ) )								//  called Method is not existing
		{
			$message	= 'Method '.$reflection->getName().'::'.$methodName.' is not existing';		//  prepare Exception Message
			throw new BadMethodCallException( $message );											//  throw Exception
		}

		if( $reflection->hasMethod( $methodName ) )
		{
			$method		= $reflection->getMethod( $methodName );
		}
		else{
			$method		= $reflection->getMethod( '__call' );
			$parameters	= array(
				$methodName,
				$parameters
			);
		}
		if( $allowProtected && version_compare( PHP_VERSION, '5.3.2' ) >= 0 )
			$method->setAccessible( TRUE );
		if( $parameters )																			//  if Method Parameters are set
			return $method->invokeArgs( $object, $parameters );										//  invoke Method with Parameters
		return $method->invoke( $object );															//  else invoke Method without Parameters
	}
}
?>
