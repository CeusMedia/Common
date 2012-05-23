<?php
/**
 *	Creates instances of Classes using Reflection.
 *
 *	Copyright (c) 2010 Christian Würker (ceusmedia.com)
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
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Creates instances of Classes using Reflection.
 *	@category		cmClasses
 *	@package		Alg.Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Object_Factory
{
	protected $arguments		= array();

	public function __construct( $arguments = array() )
	{
		if( is_array( $arguments ) )
			$this->setArguments( $arguments );
	}

	public function addArgument( $argument )
	{
		$this->arguments[]	= $argument;
	}

	public function create( $className, $arguments = NULL )
	{
		$factory	= clone $this;
		if( $arguments )
			foreach( $arguments as $argument )
				$factory->addArgument( $argument );
		$arguments	= $factory->getArguments();
		return Alg_Object_Factory::createObject( $className, $arguments );
	}

	public function getArguments()
	{
		return $this->arguments;
	}

	public function setArguments( $arguments = array() )
	{
		if( !is_array( $arguments ) )
			throw new InvalidArgumentException( 'Arguments must be an array' );
		$this->arguments	= array_values( $arguments );
	}

	/**
	 *	Creates an instance of a class using Reflection.
	 *	@access		public
	 *	@static
	 *	@param		string		$className		Name of Class
	 *	@param		array		$arguments		List of Arguments for Contruction
	 *	@return		object
	 */
	public static function createObject( $className, $arguments = array() )
	{
		if( !class_exists( $className ) )
			throw new RuntimeException( 'Class "'.$className.'" has not been loaded' );
		$class	= new ReflectionClass( $className );
		if( $arguments )
			$object	= $class->newInstanceArgs( $arguments );
		else
			$object	= $class->newInstance();
		return $object;		
	}
}
?>