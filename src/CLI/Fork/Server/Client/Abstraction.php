<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Fork\Server\Client;

use RuntimeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server_Client
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Abstraction
{
	protected ?int $port		= NULL;

	public function __construct( ?int $port = NULL )
	{
		if( !is_null( $port ) )
			$this->setPort( $port );
	}

	public function setPort( int $port ): self
	{
		$this->port	= $port;
		return $this;
	}

	abstract function getRequest(): string;

	protected function getResponse(): string
	{
		if( NULL === $this->port )
			throw new RuntimeException( 'No port defined' );

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
}
