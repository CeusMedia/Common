<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Collects event bindings and handles calls of triggered events.
 *
 *	Copyright (c) 2015-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Event;

use CeusMedia\Common\ADT\Collection\Dictionary;
use InvalidArgumentException;

/**
 *	Collects event bindings and handles calls of triggered events.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Handler
{

	/**	@var	Dictionary		$events		List of bound events */
	protected Dictionary $events;

	/**	@var	array			$stopped		List of currently running events not to propagate anymore */
	protected array $stopped	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->events	= new Dictionary();
	}

	/**
	 *	Bind event.
	 *	@access		public
	 *	@param		string				$key		Event key, e.g. "start.my"
	 *	@param		callable|Callback	$callback	Callback function or object
	 *	@return		int					Number of events by key
	 */
	public function bind( string $key, callable|Callback $callback ): int
	{
		if( is_callable( $callback ) )
			$callback	= new Callback( $callback );
		if( !is_array( $list = $this->events->get( $key ) ) )
			$list	= [];
		$list[]	= [$key, $callback];
		$this->events->set( $key, $list );
		return count( $this->getBoundEvents( $key ) );
	}

	/**
	 *	Returns list of bound events by event key.
	 *	@access		public
	 *	@param		string		$key		Event key, eg. "start"
	 *	@param		boolean		$nested		Flag: list events with namespace, like "start.my"
	 *	@return		array
	 */
	public function getBoundEvents( string $key, bool $nested = FALSE ): array
	{
		$events	= [];
		if( $this->events->get( $key ) )
			foreach( $this->events->get( $key) as $event )
				$events[]	= $event;
		if( $nested )
			foreach( $this->events->getAll( $key.'.' ) as $list )
				foreach( $list as $event )
					$events[]	= $event;
		return $events;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function isBound( string $key ): bool
	{
		return $this->events->has( $key );
	}

	/**
	 *	Removed event key from stop list making it callable again.
	 *	@access		protected
	 *	@param		string		$key		Event key, e.g. "start"
	 *	@return		bool
	 */
	protected function removeStopMark( string $key ): bool
	{
		$index	= array_search( $key, $this->stopped, TRUE );
		if( $index !== FALSE ){
			unset( $this->stopped[$index] );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Notes to stop further propagation of event by its key.
	 *	@access		public
	 *	@param		string		$key		Event key
	 *	@return		bool
	 */
	public function stopEvent( string $key ): bool
	{
		if( $this->isBound( $key) && !in_array( $key, $this->stopped, TRUE ) ){
			$this->stopped[]	= $key;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Builds event data object and handles call of triggered event.
	 *	@access		public
	 *	@param		string			$key		Event trigger key
	 *	@param		object|NULL		$caller		Object which triggered event
	 *	@param		array			$arguments	Data for event on trigger
	 *	@return		int
	 */
	public function trigger( string $key, ?object $caller = NULL, array $arguments = [] ): int
	{
		$events	= $this->getBoundEvents( $key, TRUE );
		if( 0 === count( $events ) )
			return 0;
		$counter	= 0;
		$this->removeStopMark( $key );
		foreach( $events as $callback ){
			if( in_array( $key, $this->stopped, TRUE ) )
				continue;
			$counter++;
			$event	= new Data( $this );
			$event->key			= $callback[0];
			$event->trigger		= $key;
			$event->caller		= $caller;
			$event->data		= $callback[1]->getData();
			$event->arguments	= $arguments;
			$result				= call_user_func( $callback[1]->getCallback(), $event );
//			if( $result === FALSE )
//				return FALSE;
		}
		return $counter;
	}
}
