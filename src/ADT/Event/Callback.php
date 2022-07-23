<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Data class for triggered events.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Event;

/**
 *	Data class for triggered events.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Callback
{
	/**	@var		callback	$callback	Anonymous function or callback to call when event is triggered */
	protected $callback;

	/**	@var		mixed		$data		Data to bind to event */
	protected $data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		callback	$callback	Anonymous function or callback to call when event is triggered
	 *	@param		mixed		$data		Data to bind to event
	 *	@return		void
	 */
	public function __construct( $callback, $data = NULL )
	{
		$this->callback	= $callback;
		$this->data		= $data;
	}

	/**
	 *	Returns bound callback.
	 *	@access		public
	 *	@return		callback
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 *	Returns bound data.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getData()
	{
		return $this->data;
	}
}
