<?php
/**
 *	Container to compose Objects and delegate Calls to their Methods.
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
 *	Container to compose Objects and delegate Calls to their Methods.
 *	@category		cmClasses
 *	@package		Alg.Object
 *	@uses			Alg_Object_Factory
 *	@uses			Alg_Object_MethodFactory
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Object_Delegation
{
	protected $delegableObjects	= array();
	protected $delegableMethods	= array();

	/**
	 *	Composes an Object by its Class Name and Construction Parameters.
	 *	@access		public
	 *	@param		string		$className		Name of Class
	 *	@param		array		$parameters		List of Construction Parameters
	 *	@return		int							Number of all added Objects
	 */
	public function addClass( $className, $parameters = array() )
	{
		$object	= Alg_Object_Factory::createObject( $className, $parameters );
		$this->addObject( $object );
	}

	/**
	 *	Composes an Object.
	 *	@access		public
	 *	@param		object		$object			Object
	 *	@return		int							Number of all added Objects
	 *	@throws		InvalidArgumentException	if no object given
	 */
	public function addObject( $object )
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Not an object given' );
		$reflection	= new ReflectionObject( $object );
		$methods	= $reflection->getMethods();
		foreach( $methods as $method )
		{
			if( in_array( $method->name, $this->delegableMethods ) )
				throw new RuntimeException( 'Method "'.$method->name.'" is already set' );
			$this->delegableMethods[]	= $method->name;
		}
		$this->delegableObjects[]	= $object;
	}

	/**
	 *	Interceptor to call delegable Method of added Objects.
	 *	@access		public
	 *	@param		string		$methodName		Name of Method delegate within added Object
	 *	@param		array		$arguments		List of Parameters for Method Call
	 *	@return		mixed						Result of delegated Method Call
	 *	@throws		BadMethodCallException		if no such Method is delegable
	 */
	public function __call( $methodName, $arguments = array() )
	{
		foreach( $this->delegableObjects as $object )
		{
			$reflection	= new ReflectionObject( $object );
			if( !$reflection->hasMethod( $methodName ) )
				continue;
			$method	= $reflection->getMethod( $methodName );
			if( !$method->isPublic() )
				continue;
			$factory	= new Alg_Object_MethodFactory;
			return $factory->call( $object, $methodName, $arguments );
		}
		throw new BadMethodCallException( 'Method "'.$methodName.'" is not existing in added objects' );
	}
}
?>