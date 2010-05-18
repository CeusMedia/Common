<?php
/**
 *	Net Service Caller.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		Net.Service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
/**
 *	@category		cmClasses
 *	@package		Net.Service
 *	@uses			Net_Service_Client
 *	@uses			Net_Service_Decoder
 *	@uses			StopWatch
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 *	@todo			Unit Test
 */
class Net_Service_Caller
{
	/**	@var		array		$calls		Array of called Services with Response */
	protected $calls	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		Base URL of Net Service
	 *	@return		void
	 */
	public function __construct( $url )
	{
		$this->client	= new Net_Service_Client( $url );
	}

	/**
	 *	This Method masks Net Service Calls under local Method Calls.
	 *	Catches Method Calls on current Object and requests Service Response of Method Key.
	 *	@access		public
	 *	@param		string		$key		Method Key, Method called on current Object
	 *	@return		mixed
	 */
	public function __call( $key, $arguments )
	{
		$watch		= new StopWatch();
		$arguments	= $this->buildArgumentsForRequest( $arguments );
		$response	= $this->client->post( $key, "php", $arguments );
		$this->calls[]	= array(
			'service'	=> $key,
			'arguments'	=> $arguments,
			'response'	=> serialize( $response ),
			'time'		=> $watch->stop( 6, 0 ),
		);
		return $response;
	}
	
	/**
	 *	Builds Service Call Parameters from Array.
	 *	@access		protected
	 *	@param		array		$arguments	Arguments to build Service Call Parameters from
	 *	@return		array
	 */
	protected function buildArgumentsForRequest( $arguments )
	{
		if( !$arguments )
			return array();
		if( count( $arguments ) == 1 && is_array( $arguments[0] ) )
			return $arguments[0];
		return array( 'argumentsGivenByServiceCaller' => serialize( $arguments ) );
	}
	
	/**
	 *	Returns Array of called Services with Response.
	 *	@access		public
	 *	@return		array
	 */
	public function getCalls()
	{
		return $this->calls;
	}
}
?>