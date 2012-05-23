<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
class Net_RPC_JSON_Caller{
	
	protected $messages	= array();

	public function __construct( $host, $path ){
		$this->client	= new Net_RPC_JSON_Client( $host, $path );
	}
	
	public function __call( $method, $arguments ){
		$message	= $this->client->request( $method, $arguments );
		$this->messages[]	= $message;
		if( !empty( $message->stdout ) )
			print( $message->stdout );
		if( $message->status == 'error' ){
			if( !empty( $message->serial ) ){
				$e	= unserialize( $message->serial );
				throw $e;
			}
		}
		return $message->data;
	}

	public function getLast(){
		return array_pop( $this->getMessages() );
	}

	public function getMessages(){
		return $this->messages;
	}
}
?>