<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Fork_Server_Client
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server_Client
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 */
abstract class CLI_Fork_Server_Client_Abstract
{
	protected $port		= NULL;

	public function __construct( $port = NULL )
	{
		if( !is_null( $port ) );
			$this->setPort( $port );
	}
	
	abstract function getRequest();
	
	protected function getResponse()
	{
		$socket = stream_socket_client( "tcp://127.0.0.1:".$this->port, $errno, $errstr, 30 );
		if( !$socket )
			die( $errstr.' ('.$errno.')<br />\n' );

		$request	= $this->getResponse();
		$buffer		= "";
		fwrite( $socket, $request );
		while( !feof( $socket ) )
			$buffer	.= fgets( $socket, 1024 );
		fclose( $socket );
		return $buffer;
	}

	public function setPort( $port )
	{
		$this->port	= $port;
	}
}
?>