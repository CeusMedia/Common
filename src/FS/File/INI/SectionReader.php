<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for sectioned Ini Files using parse_ini_file.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\INI;

use InvalidArgumentException;
use RuntimeException;

/*
 *	Reader for sectioned Ini Files using parse_ini_file.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SectionReader
{
	/**	@var		array		$data			Array of parsed Properties from File */
	protected array $data		= [];

	/**	@var		string		$fileName		File Name of sectioned Properties File */
	protected string $fileName	= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of sectioned Properties File to Read
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
		$this->read();
	}

	/**
	 *	Returns all Properties or all of a Section as Array.
	 *	@access		public
	 *	@param		string|NULL		$section		Flag: use Sections
	 *	@return		array
	 */
	public function getProperties( ?string $section = NULL ): array
	{
		if( $section && !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' );
		if( $section )
			return $this->data[$section];
		return $this->data;
	}

	/**
	 *	Returns a Property by its Key.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		string
	 */
	public function getProperty( string $section, string $key ): string
	{
		if( !$this->hasProperty( $section, $key ) )
			throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in Section "'.$section.'".' );
		return $this->data[$section][$key];
	}

	/**
	 *	Returns all Sections as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections(): array
	{
		return array_keys( $this->data );
	}

	/**
	 *	Indicated whether a Key is set.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		bool
	 */
	public function hasProperty( string $section, string $key ): bool
	{
		if( !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' );
		return array_key_exists( $key, $this->data[$section] );
	}

	/**
	 *	Indicated whether a Section is set.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	public function hasSection( string $section ): bool
	{
		return in_array( $section, $this->getSections() );
	}

	/**
	 *	Reads section Property File.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		if( !file_exists( $this->fileName ) )
			throw new RuntimeException( 'File "'.$this->fileName.'" is not existing.' );
		$this->data		= parse_ini_file( $this->fileName, true );
	}

	/**
	 *	Alias for 'getProperties'.
	 *	@access		public
	 *	@param		string|NULL		$section		Flag: use Sections
	 *	@return		array
	 */
	public function toArray( ?string $section = NULL ): array
	{
		return $this->getProperties( $section );
	}
}
