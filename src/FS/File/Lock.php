<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for Files.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use RuntimeException;

/**
 *	....
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 */
class Lock
{
	protected $fileName;
	protected $expiration			= 0;
	protected $sleep				= 0.1;
	protected $timeout				= 2;

	public function __construct( string $fileName, ?float $expiration = 0, ?float $timeout = 2, ?float $sleep = 0.1 )
	{
		$this->fileName		= $fileName;
		if( !is_null( $expiration ) )
			$this->setExpiration( $expiration );
		if( !is_null( $timeout ) )
			$this->setTimeout( $timeout );
		if( !is_null( $sleep ) )
			$this->setSleep( $sleep );
	}

	public function getExpiration(): float
	{
		return $this->expiration;
	}

	public function getSleep(): float
	{
		return $this->sleep;
	}

	public function getTimeout(): float
	{
		return $this->timeout;
	}

	public function isLocked(): bool
	{
		if( file_exists( $this->fileName ) ){
			if( !$this->expiration )
				return TRUE;
			if( $this->expiration >= time() - filemtime( $this->fileName ) )
				return TRUE;
			unlink( $this->fileName );
		}
		return FALSE;
	}

	public function lock( bool $strict = TRUE ): bool
	{
		$start		= microtime( TRUE );
		$timeout	= $start + $this->timeout;
		while( $this->isLocked() ){
			if( $this->timeout && microtime( TRUE ) >= $timeout ){
				if( !$strict )
					return FALSE;
				throw new RuntimeException( 'File "'.$this->fileName.'" could not been locked' );
			}
			usleep( $this->sleep * 1000000 );
		}
		touch( $this->fileName );
		return TRUE;
	}

	public function unlock(): bool
	{
		if( !$this->isLocked() )
			return FALSE;
		@unlink( $this->fileName );
		return TRUE;
	}

	public function setExpiration( float $expiration = 0 ): self
	{
		$this->expiration	= abs( $expiration );
		return $this;
	}

	public function setSleep( float $sleep = 0.1 ): self
	{
		$this->sleep	= abs( $sleep );
		return $this;
	}

	public function setTimeout( float $timeout = 2 ): self
	{
		$this->timeout	= abs( $timeout );
		return $this;
	}
}
