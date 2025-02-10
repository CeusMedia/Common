<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Fork\Server;

use RuntimeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Fork_Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Dynamic extends Abstraction
{
	protected ?string $scriptFile		= NULL;

	public function setScriptFile( string $scriptFile ): self
	{
		$this->scriptFile	= $scriptFile;
		return $this;
	}

	protected function handleRequest( string $request ): string
	{
		if( NULL === $this->scriptFile || 0 === strlen( trim( $this->scriptFile ) ) )
			throw new RuntimeException( 'No script for a dynamic server set' );
		if( !file_exists( $this->scriptFile ) )
			throw new RuntimeException( 'Script for a dynamic server is not existing' );

		return require_once( $this->scriptFile );
	}
}
