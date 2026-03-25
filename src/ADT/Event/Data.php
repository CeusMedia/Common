<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Data class for triggered events.
 *
 *	Copyright (c) 2015-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Event;

/**
 *	Data class for triggered events.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Data
{
	/**	@var	array			$arguments		Data given by trigger */
	public array $arguments 	= [];

	/**	@var	object|NULL		$caller			Object which triggered bound event */
	public ?object $caller		= NULL;

	/**	@var	mixed|NULL		$data			Data bound on event */
	public $data;

	/**	@var	Handler			$handler		Reference to event handler instance */
	protected Handler $handler;

	/**	@var	string|NULL		$key			Name bound event, e.g. "start.my"  */
	public ?string $key			= NULL;

	/**	@var	string|NULL		$trigger		Name of trigger, e.g. "start" */
	public ?string $trigger		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Handler		$handler		Event handler instance
	 *	@return		void
	 */
	public function __construct( Handler $handler )
	{
		$this->handler	= $handler;
	}

	/**
	 *	Stop propagation of this event.
	 *	@access		public
	 *	@return		void
	 */
	public function stop(): void
	{
		$this->handler->stopEvent( $this->trigger );
	}
}
