<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Cache to store Data in Files.
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

use CeusMedia\Common\ADT\Cache\Store as CacheStore;
use Countable;
use DirectoryIterator;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Cache to store Data in Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Cache extends CacheStore implements Countable
{
	/**	@var		array		$data			Memory Cache */
	protected $data				= array();

	/**	@var		string		$path			Path to Cache Files */
	protected $path;

	/**	@var		int			$expires		Cache File Lifetime in Seconds */
	protected $expires			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Cache Files
	 *	@param		int			$expires		Seconds until Pairs will be expired
	 *	@return		void
	 */
	public function __construct( string $path, int $expires = 0 )
	{
		$path	.= substr( $path, -1 ) == "/" ? "" : "/";
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path		= $path;
		$this->expires	= $expires;
	}

	/**
	 *	Removes all expired Cache Files.
	 *	@access		public
	 *	@param		int			$expires		Cache File Lifetime in Seconds
	 *	@return		bool
	 */
	public function cleanUp( int $expires = 0 ): bool
	{
		$expires	= $expires !== 0 ? $expires : $this->expires;
		if( !$expires )
			throw new InvalidArgumentException( 'No expire time given or set on construction.' );

		$number	= 0;
		$index	= new DirectoryIterator( $this->path );
		foreach( $index as $entry )
		{
			if( $entry->isDot() || $entry->isDir() )
				continue;
			$pathName	= $entry->getPathname();
			if( substr( $pathName, -7 ) !== ".serial" )
				continue;
			if( $this->isExpired( $pathName, $expires ) )
				$number	+= (int) @unlink( $pathName );
		}
		return $number;
	}

	/**
	 *	Counts all Elements in Cache.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->data );
	}

	/**
	 *	Removes all Cache Files.
	 *	@access		public
	 *	@return		int
	 */
	public function flush(): int
	{
		$index	= new DirectoryIterator( $this->path );
		$number	= 0;
		foreach( $index as $entry )
		{
			if( $entry->isDot() || $entry->isDir() )
				continue;
			if( substr( $entry->getFilename(), -7 ) == ".serial" )
				$number	+= (int) @unlink( $entry->getPathname() );
		}
		$this->data	= array();
		return $number;
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public function get( string $key )
	{
		$uri		= $this->getUriForKey( $key );
		if( !$this->isValidFile( $uri ) )
			return NULL;
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		$content	= Editor::load( $uri );
		$value		= unserialize( $content );
		$this->data[$key]	= $value;
		return $value;
	}

	/**
	 *	Returns URI of Cache File from its Key.
	 *	@access		protected
	 *	@param		string		$key			Key of Cache File
	 *	@return		string
	 */
	protected function getUriForKey( string $key ): string
	{
		return $this->path.base64_encode( $key ).".serial";
	}

	/**
	 *	Indicates whether a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		bool
	 */
	public function has( string $key ): bool
	{
		$uri	= $this->getUriForKey( $key );
		return $this->isValidFile( $uri );
	}

	/**
	 *	Indicates whether a Cache File is expired.
	 *	@access		protected
	 *	@param		string		$uri			URI of Cache File
	 *	@param		integer		$expires		Lifetime in seconds
	 *	@return		bool
	 */
	protected function isExpired( string $uri, int $expires ): bool
	{
		$edge	= time() - $expires;
		clearstatcache();
		return filemtime( $uri ) <= $edge;
	}

	/**
	 *	Indicates whether a Cache File is existing and not expired.
	 *	@access		protected
	 *	@param		string		$uri			URI of Cache File
	 *	@return		bool
	 */
	protected function isValidFile( string $uri ): bool
	{
		if( !file_exists( $uri ) )
			return FALSE;
		if( !$this->expires )
			return TRUE;
		return !$this->isExpired( $uri, $this->expires );
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		bool
	 */
	public function remove( string $key ): bool
	{
		$uri	= $this->getUriForKey( $key );
		unset( $this->data[$key] );
		return @unlink( $uri );
	}

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public function set( string $key, $value )
	{
		$uri		= $this->getUriForKey( $key );
		$content	= serialize( $value );
		$this->data[$key]	= $value;
		Editor::save( $uri, $content );
	}
}
