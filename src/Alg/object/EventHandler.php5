<?php
/**
 *	Handles Callbacks on Object or Class Methods for triggered Events.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		alg.object
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
import( 'de.ceus-media.alg.object.MethodFactory' );
/**
 *	Handles Callbacks on Object or Class Methods for triggered Events.
 *	@category		cmClasses
 *	@package		alg.object
 *	@uses			Alg_Object_MethodFactory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Object_EventHandler
{
	/**	@var		array			$callbacks			Map of registered Callback Methods on Events */
	protected $callbacks			= array();
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
	 *	@return		void
	 */
	public function addCallback( $eventName, $mixed, $methodName, $methodParameters = array(), $classParameters = array() )
	{
		if( is_object( $mixed ) )
			$this->addObjectCallback( $eventName, $mixed, $methodName, $methodParameters );
		else
			$this->addClassCallback( $eventName, $mixed, $methodName, $methodParameters, $classParameters );
	}

	/**
	 *	Registers a Method to call on an Event.
	 *	@access		public
	 *	@param		string			$eventName			Name of the Event
	 *	@param		string			$class				Name of Class with Method to call
	 *	@param		string			$methodName			Name of Object Method to call on Event
	 *	@param		array			$methodParameters	List of Parameters for Method Call		
	 *	@return		void
	 */
	public function addClassCallback( $eventName, $class, $methodName, $methodParameters = array(), $classParameters = array() )
	{
		if( !is_string( $class ) )
			throw new InvalidArgumentException( 'Not a class name given' );
		$object	= Alg_Object_Factory::createObject( $class, $classParameters );
		$this->addObjectCallback(  $eventName, $object, $methodName, $methodParameters );
	}

	/**
	 *	Registers a Method to call on an Event.
	 *	@access		public
	 *	@param		string			$eventName			Name of the Event
	 *	@param		object			$object				Object with Method to call
	 *	@param		string			$methodName			Name of Object Method to call on Event
	 *	@param		array			$methodParameters	List of Parameters for Method Call
	 *	@return		void
	 */
	public function addObjectCallback( $eventName, $object, $methodName, $methodParameters = array() )
	{
		if( !is_object( $object ) )
			throw new InvalidArgumentException( 'Not an object given' );
		$this->callbacks[$eventName][]	= array(
			'object'			=> $object,
			'methodName'		=> $methodName,
			'methodParameters'	=> $methodParameters
		);
	}

	/**
	 *	Returns total number of handled Event Callback Method Calls.
	 *	@access		public
	 *	@return		int			Number of handled Event Callback Method Calls
	 */
	public function getCounter()
	{
		return $this->counter;
	}

	/**
	 *	Trigger all registered Callbacks for an Event.
	 *	@access		public
	 *	@public		string		$eventName		Name of Event to trigger
	 *	@return		int			Number of handled Callbacks
	 */
	public function handleEvent( $eventName )
	{
		$counter	= 0;
		if( !array_key_exists( $eventName, $this->callbacks ) )
			return $counter;
		foreach( $this->callbacks[$eventName] as $callback )
		{
			extract( $callback );
			$factory	= new Alg_Object_MethodFactory;												//  build a new Method Factory
			$factory->callObjectMethod( $object, $methodName, $methodParameters );
			$counter++;																				//  increase Callback Counter
			$this->counter++;																		//  increase total Callback Counter
		}
		return $counter;
	}
}
?>