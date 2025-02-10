<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for sectioned Ini Files using parse_ini_file.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\INI;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use InvalidArgumentException;

/**
 *	Editor for sectioned Ini Files using parse_ini_file.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SectionEditor extends SectionReader
{
	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string		$section		Section to add
	 *	@return		bool
	 */
	public function addSection( string $section ): bool
	{
		if( $this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is already existing.' );
		$this->data[$section] = [];
		return is_int( $this->write() );
	}

	/**
	 *	Builds uniformed indent between Keys and Values.
	 *	@access		protected
	 *	@param		string		$key			Key of Property
	 *	@param		int			$tabs			Amount to Tabs to indent
	 *	@return		string
	 */
	protected function fillUp( string $key, int $tabs = 5 ): string
	{
		$key_breaks	= $tabs - floor( strlen( $key ) / 8 );
		if( $key_breaks < 1 )
			$key_breaks = 1;
		return $key.str_repeat( "\t", $key_breaks );
	}

	/**
	 *	Removes a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@return		bool
	 */
	public function removeProperty( string $section, string $key ): bool
	{
		if( !$this->hasProperty( $section, $key ) )
			throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in Section "'.$section.'".' );
		unset( $this->data[$section][$key] );
		return is_int( $this->write() );
	}

	/**
	 *	Removes a Section.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	public function removeSection( string $section ): bool
	{
		if( !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' );
		unset( $this->data[$section] );
		return is_int( $this->write() );
	}

	/**
	 *	Sets a Property.
	 *	@access		public
	 *	@param		string		$section		Section of Property
	 *	@param		string		$key			Key of Property
	 *	@param		string		$value			Value of Property
	 *	@return		bool
	 */
	public function setProperty( string $section, string $key, string $value ): bool
	{
		if( !$this->hasSection( $section ) )
			$this->addSection( $section );
		$this->data[$section][$key]	= $value;
		return is_int( $this->write() );
	}

	/**
	 *	Writes sectioned Property File and returns Number of written Bytes.
	 *	@access		public
	 *	@return		int
	 */
	public function write(): int
	{
		$lines		= [];
		$sections	= $this->getSections();
		foreach( $sections as $section ){
			$lines[]	= "[".$section."]";
			foreach( $this->data[$section] as $key => $value )
				$lines[]	= $this->fillUp( $key )."=".$value;
		}
		$result	= FileWriter::saveArray( $this->fileName, $lines );
		$this->read();
		return $result;
	}
}
