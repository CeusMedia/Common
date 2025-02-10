<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Container to compose Objects and delegate Calls to their Methods.
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Obj;

use BadMethodCallException;
use InvalidArgumentException;
use ReflectionException;
use ReflectionObject;
use RuntimeException;

/**
 *	Container to compose Objects and delegate Calls to their Methods.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Delegation
{
	protected array $delegableObjects	= [];
	protected array $delegableMethods	= [];

	/**
	 *	Composes an Object by its Class Name and Construction Parameters.
	 *	@access		public
	 *	@param		string		$className		Name of Class
	 *	@param		array		$parameters		List of Construction Parameters
	 *	@return		int							Number of all added Objects
	 *	@throws		ReflectionException
	 *	@throws		RuntimeException
	 */
	public function addClass( string $className, array $parameters = [] ): int
	{
		$object	= Factory::createObject( $className, $parameters );
		$this->addObject( $object );
		return count( $this->delegableObjects );
	}

	/**
	 *	Composes an Object.
	 *	@access		public
	 *	@param		object		$object			Object
	 *	@return		int							Number of all added Objects
	 *	@throws		InvalidArgumentException	if no object given
	 */
	public function addObject( object $object ): int
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Not an object given' );
		$reflection	= new ReflectionObject( $object );
		$methods	= $reflection->getMethods();
		foreach( $methods as $method ){
			if( $method->isConstructor() )
				continue;
			if( in_array( $method->name, $this->delegableMethods ) )
				throw new RuntimeException( 'Method "'.$method->name.'" is already set' );
			$this->delegableMethods[]	= $method->name;
		}
		$this->delegableObjects[]	= $object;
		return count( $this->delegableObjects );
	}

	/**
	 *	Interceptor to call delegable Method of added Objects.
	 *	@access		public
	 *	@param		string		$methodName		Name of Method delegate within added Object
	 *	@param		array		$arguments		List of Parameters for Method Call
	 *	@return		mixed						Result of delegated Method Call
	 *	@throws		BadMethodCallException		if no such Method is delegable
	 *	@throws		ReflectionException
	 */
	public function __call( string $methodName, array $arguments = [] )
	{
		foreach( $this->delegableObjects as $object ){
			$reflection	= new ReflectionObject( $object );
			if( !$reflection->hasMethod( $methodName ) )
				continue;
			$method	= $reflection->getMethod( $methodName );
			if( !$method->isPublic() )
				continue;
			return MethodFactory::staticCallObjectMethod( $object, $methodName, $arguments );
		}
		throw new BadMethodCallException( 'Method "'.$methodName.'" is not existing in added objects' );
	}
}
