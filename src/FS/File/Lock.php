<?php
/**
 *	Editor for Files.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	....
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 */
class FS_File_Lock
{

	protected $fileName;
	protected $expiration			= 0;
	protected $sleep				= 0.1;
	protected $timeout				= 2;

	public function __construct( $fileName, $expiration = 0, $timeout = 2, $sleep = 0.1 )
	{
		$this->fileName		= $fileName;
		$this->setExpiration( $expiration );
		$this->setTimeout( $timeout );
		$this->setSleep( $sleep );
	}

	public function getExpiration()
	{
		return $this->expiration;
	}

	public function getSleep()
	{
		return $this->sleep;
	}

	public function getTimeout()
	{
		return $this->timeout;
	}

	public function isLocked()
	{
		if( file_exists( $this->fileName ) )
		{
			if( !$this->expiration )
				return TRUE;
			if( $this->expiration >= time() - filemtime( $this->fileName ) )
				return TRUE;
			unlink( $this->fileName );
		}
		return FALSE;
	}

	public function lock( $strict = TRUE )
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

	public function unlock()
	{
		if( $this->isLocked() ){
			@unlink( $this->fileName );
			return TRUE;
		}
		return FALSE;
	}

	public function setExpiration( $expiration = 0 )
	{
		$this->expiration	= abs( (int) $expiration );
	}

	public function setSleep( $sleep = 0.1 )
	{
		$this->sleep	= abs( (float) $sleep );
	}

	public function setTimeout( $timeout = 2 )
	{
		$this->timeout	= abs( (float) $timeout );
	}
}
