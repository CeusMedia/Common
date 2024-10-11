<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Handles Callbacks on Object or Class Methods for triggered Events.
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

use InvalidArgumentException;
use ReflectionException;
use RuntimeException;

/**
 *	Handles Callbacks on Object or Class Methods for triggered Events.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class EventHandler
{
	/**	@var		array			$callbacks			Map of registered Callback Methods on Events */
	protected $callbacks			= [];

	/**	@var		int				$counter			Number of handled Event Callback Method Calls */
	protected $counter				= 0;

	/**
	 *	Registers a Method to call on an Event.
	 *	@access		public
	 *	@param		string			$eventName			Name of the Event
	 *	@param		string|object	$mixed				Class or Object with Method to call
	 *	@param		string			$methodName			Name of Object Method to call on Event
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@param		array			$classParameters	List of Parameters for Object Construction (if a Class Name is given)
	 *	@return		self
	 *	@throws		ReflectionException
	 */
	public function addCallback( string $eventName, $mixed, string $methodName, array $methodParameters = [], array $classParameters = [] ): self
	{
		if( is_object( $mixed ) )
			$this->addObjectCallback( $eventName, $mixed, $methodName, $methodParameters );
		else
			$this->addClassCallback( $eventName, $mixed, $methodName, $methodParameters, $classParameters );
		return $this;
	}

	/**
	 *	Registers a Method to call on an Event.
	 *	@access		public
	 *	@param		string			$eventName			Name of the Event
	 *	@param		string			$className			Name of Class with Method to call
	 *	@param		string			$methodName			Name of Object Method to call on Event
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@param		array			$classParameters	List of Parameters for Object Construction (if a Class Name is given)
	 *	@return		self
	 *	@throws		ReflectionException
	 *	@throws		RuntimeException
	 */
	public function addClassCallback( string $eventName, string $className, string $methodName, array $methodParameters = [], array $classParameters = [] ): self
	{
		$object	= Factory::createObject( $className, $classParameters );
		$this->addObjectCallback(  $eventName, $object, $methodName, $methodParameters );
		return $this;
	}

	/**
	 *	Registers a Method to call on an Event.
	 *	@access		public
	 *	@param		string			$eventName			Name of the Event
	 *	@param		object			$object				Object with Method to call
	 *	@param		string			$methodName			Name of Object Method to call on Event
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@return		self
	 */
	public function addObjectCallback( string $eventName, object $object, string $methodName, array $methodParameters = [] ): self
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Not an object given' );
		$this->callbacks[$eventName][]	= (object) [
			'object'			=> $object,
			'methodName'		=> $methodName,
			'methodParameters'	=> $methodParameters
		];
		return $this;
	}

	/**
	 *	Returns total number of handled Event Callback Method Calls.
	 *	@access		public
	 *	@return		int			Number of handled Event Callback Method Calls
	 */
	public function getCounter(): int
	{
		return $this->counter;
	}

	/**
	 *	Trigger all registered Callbacks for an Event.
	 *	@access		public
	 *	@public		string		$eventName		Name of Event to trigger
	 *	@return		int			Number of handled Callbacks
	 *	@throws		ReflectionException
	 */
	public function handleEvent( string $eventName ): int
	{
		$counter	= 0;
		if( !array_key_exists( $eventName, $this->callbacks ) )
			return $counter;
		foreach( $this->callbacks[$eventName] as $callback ) {
			//  build a new Method Factory
			$factory	= new MethodFactory( $callback->object );
			$factory->setMethod( $callback->methodName, $callback->methodParameters )->call();
			//  increase Callback Counter
			$counter++;
			//  increase total Callback Counter
			$this->counter++;
		}
		return $counter;
	}
}
