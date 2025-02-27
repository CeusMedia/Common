<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Property File Editor.
 *	This Implementation keeps the File Structure of original File completely alive.
 *	All Line Feeds and Comments will be kept.
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

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

/**
 *	Property File Editor.
 *	This Implementation keeps the File Structure of original File completely alive.
 *	All Line Feeds and Comments will be kept.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Documentation
 */
class Editor extends Reader
{
	/**	@var		array		$added			Added Properties */
	protected array $added			= [];

	/**	@var		array		$renamed		Renamed Properties */
	protected array $renamed			= [];

	/**	@var		array		$deleted		Deleted Properties */
	protected array $deleted			= [];

	/**
	 *	Activates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		bool
	 */
	public function activateProperty( string $key, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			if( $this->isActiveProperty( $key, $section ) )
				throw new LogicException( 'Key "'.$key.'" is already active' );
			unset( $this->disabled[$section][array_search( $key, $this->disabled[$section] )] );
		}
		else{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			if( $this->isActiveProperty( $key ) )
				throw new LogicException( 'Key "'.$key.'" is already active' );
			unset( $this->disabled[array_search( $key, $this->disabled )] );
		}
		return is_int( $this->write() );
	}

	/**
	 *	Adds a new Property with Comment.
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		mixed		$value			Value of new Property
	 *	@param		string|NULL	$comment		Comment of new Property
	 *	@param		boolean		$state			Activity state of new Property
	 *	@param		string|NULL	$section		Section to add Property to
	 *	@return		bool
	 */
	public function addProperty( string $key, $value, ?string $comment = '', bool $state = TRUE, ?string $section = NULL ): bool
	{
		if( $section && !in_array( $section, $this->sections ) )
			$this->addSection( $section );
		$key = ( $state ? '' : $this->signDisabled ).$key;
		$this->added[] = [
			'key'		=> $key,
			'value'		=> $value,
			'comment'	=> $comment ?? '',
			'section'	=> $section,
		];
		return is_int( $this->write() );
	}

	/**
	 *	Adds a new Section.
	 *	@access		public
	 *	@param		string		$sectionName	Name of new Section
	 *	@return		bool
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@throws		IoException			if file is not writable
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function addSection( string $sectionName ): bool
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		$lines		= FileReader::loadArray( $this->file );
		$lines[]	= '['.$sectionName.']';
		if( !in_array( $sectionName, $this->sections ) )
			$this->sections[] = $sectionName;
		$result		= FileWriter::saveArray( $this->file->getPathName(), $lines );
		$this->read();
		return is_int( $result );
	}

	/**
	 *	Returns a build Property line.
	 *	@access		private
	 *	@param		string		$key			Key of  Property
	 *	@param		mixed		$value			Value of Property
	 *	@param		string|NULL	$comment		Comment of Property
	 *	@return		string
	 */
	private function buildLine( string $key, $value, ?string $comment = NULL ): string
	{
		$content	= '"'.addslashes( $value ).'"';
		if( $this->reservedWords && is_bool( $value ) )
			$content	= $value ? "yes" : "no";

		$breaksKey		= 4 - floor( strlen( $key ) / 8 );
		$breaksValue	= 4 - floor( strlen( $content ) / 8 );
		if( $breaksKey < 1 )
			$breaksKey = 1;
		if( $breaksValue < 1 )
			$breaksValue = 1;
		$line	= $key.str_repeat( "\t", $breaksKey ).'= '.$content;
		if( $comment )
			$line	.= str_repeat( "\t", $breaksValue ).'; '.$comment;
		return $line;
	}

	/**
	 *	Deactivates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		bool
	 */
	public function deactivateProperty( string $key, ?string $section = NULL): bool
	{
		if( $this->usesSections() ){
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			if( !$this->isActiveProperty( $key, $section ) )
				throw new LogicException( 'Key "'.$key.'" is already inactive' );
			$this->disabled[$section][] = $key;
		}
		else{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			if( !$this->isActiveProperty( $key ) )
				throw new LogicException( 'Key "'.$key.'" is already inactive' );
			$this->disabled[] = $key;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Deletes a  Property.
	 *	Alias for removeProperty.
	 *	@access		public
	 *	@param		string		$key			Key of Property to be deleted
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		bool
	 */
	public function deleteProperty( string $key, ?string $section = NULL ): bool
	{
		return $this->removeProperty( $key, $section );
	}

	/**
	 *	Removes a  Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property to be removed
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		bool
	 */
	public function removeProperty( string $key, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			$this->deleted[$section][] = $key;
		}
		else{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->deleted[] = $key;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Removes a Section
	 *	@access		public
	 *	@param		string		$section		Key of Section to remove
	 *	@return		bool
	 */
	public function removeSection( string $section ): bool
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		if( !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
		$index	= array_search( $section, $this->sections);
		unset( $this->sections[$index] );
		return is_int( $this->write() );
	}

	/**
	 *	Renames a Property Key.
	 *	@access		public
	 *	@param		string		$key			Key of Property to rename
	 *	@param		string		$new			New Key of Property
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		bool
	 */
	public function renameProperty( string $key, string $new, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			$this->properties[$section][$new]	= $this->properties[$section][$key];
			if( isset( $this->disabled[$section][$key] ) )
				$this->disabled [$section][$new]		= $this->disabled[$section][$key];
			if( isset( $this->comments[$section][$key] ) )
				$this->comments [$section][$new]	= $this->comments[$section][$key];
			$this->renamed[$section][$key] = $new;
		}
		else{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->properties[$new]	= $this->properties[$key];
			if( isset( $this->disabled[$key] ) )
				$this->disabled[$new]		= $this->disabled[$key];
			if( isset( $this->comments[$key] ) )
				$this->comments[$new]	= $this->comments[$key];
			$this->renamed[$key]	= $new;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Renames as Section.
	 *	@access		public
	 *	@param		string		$oldSection		Key of Section to rename
	 *	@param		string		$newSection		New Key of Section
	 *	@return		bool
	 */
	public function renameSection( string $oldSection, string $newSection ): bool
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		$content	= FileReader::load( $this->file->getPathName() );
		$regexp		= "/(.*)(".preg_quote( '['.$oldSection.']', '/' ).")(.*)/si";
		$content	= preg_replace( $regexp, "$1[".$newSection."]$3", $content );
		$result		= FileWriter::save( $this->file->getPathName(), $content );
		$this->added	= [];
		$this->deleted	= [];
		$this->renamed	= [];
		$this->read();
		return is_int( $result );
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string|NULL	$comment		Comment of Property to set
	 *	@param		string|NULL	$section		Key of Section
	 *	@return		bool
	 */
	public function setComment( string $key, ?string $comment, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in Section "'.$section.'".' );
			$this->comments[$section][$key] = $comment;
		}
		else{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->comments[$key] = $comment;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		mixed		$value			Value of Property
	 *	@param		string|NULL	$section		Key of Section
	 *	@return		bool
	 */
	public function setProperty( string $key, $value, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( $this->hasSection( $section ) && $this->hasProperty( $key, $section ) )
				$this->properties[$section][$key] = $value;
			else
				$this->addProperty( $key, $value, NULL, TRUE, $section );
		}
		else{
			if( $this->hasProperty( $key ) )
				$this->properties[$key] = $value;
			else
				$this->addProperty( $key, $value, NULL, TRUE );
		}
		return is_int( $this->write() );
	}

	/**
	 *	Writes manipulated Content to File.
	 *	@access		protected
	 *	@return		integer			Number of written bytes
	 */
	protected function write(): int
	{
		$file		= new FileWriter( $this->file->getPathName() );
		$newLines	= [];
		$currentSection	= '';
		foreach( $this->lines as $line ){
			if( $this->usesSections() && preg_match( $this->patternSection, $line ) ){
				$lastSection = $currentSection;
#				$newAdded = [];
				if( $lastSection ){
					foreach( $this->added as $nr => $property ){
						if( $property['section'] == $lastSection ){
							if( !trim( $newLines[count($newLines)-1] ) )
								array_pop( $newLines );
							$newLines[]	= $this->buildLine( $property['key'], $property['value'], $property['comment'] );
							$newLines[]	= '';
							unset( $this->added[$nr] );
						}
#						else $newAdded[] = $property;
					}
				}
				$currentSection =  substr( trim( $line ), 1, -1 );
				if( !in_array( $currentSection, $this->sections ) )
					continue;
			}
			else if( preg_match( $this->patternProperty, $line ) ){
				$pos		= strpos( $line, '=' );
				$key		= trim( substr( $line, 0, $pos ) );
				$pureKey	= preg_replace( $this->patternDisabled, '', $key );
				$parts		= explode(  '//', trim( substr( $line, $pos+1 ) ) );
				if( count( $parts ) > 1 )
					$comment = trim( $parts[1] );
				if( $this->usesSections() ){
					if( in_array( $currentSection, $this->sections ) ){
						if( isset( $this->deleted[$currentSection] ) && in_array( $pureKey, $this->deleted[$currentSection] ) )
							unset( $line );
						else if( isset( $this->renamed[$currentSection] ) && in_array( $pureKey, array_keys( $this->renamed[$currentSection] ) ) ){
							$newKey	= $key	= $this->renamed[$currentSection][$pureKey];
							if( !$this->isActiveProperty( $newKey, $currentSection) )
								$key = $this->signDisabled.$key;
							$comment	= $this->comments[$currentSection][$newKey] ?? '';
							$line = $this->buildLine( $key, $this->properties[$currentSection][$newKey], $comment );
						}
						else{
							if( $this->isActiveProperty( $pureKey, $currentSection ) && preg_match( $this->patternDisabled, $key ) )
								$key = substr( $key, 1 );
							else if( !$this->isActiveProperty( $pureKey, $currentSection ) && !preg_match( $this->patternDisabled, $key ) )
								$key = $this->signDisabled.$key;
							$comment	= $this->comments[$currentSection][$pureKey] ?? '';
							$line = $this->buildLine( $key, $this->properties[$currentSection][$pureKey], $comment );
						}
					}
					else
						unset( $line );
				}
				else{
					if( in_array( $pureKey, $this->deleted ) )
						unset( $line);
					else if( in_array( $pureKey, array_keys( $this->renamed ) ) ){
						$newKey	= $key	= $this->renamed[$pureKey];
						if( !$this->isActiveProperty( $newKey ) )
							$key = $this->signDisabled.$key;
						$line = $this->buildLine( $newKey, $this->properties[$newKey], $this->comments[$newKey] );
					}
					else{
						if( $this->isActiveProperty( $pureKey ) && preg_match( $this->patternDisabled, $key ) )
							$key = substr( $key, 1 );
						else if( !$this->isActiveProperty( $pureKey) && !preg_match( $this->patternDisabled, $key ) )
							$key = $this->signDisabled.$key;
						$line = $this->buildLine( $key, $this->properties[$pureKey], $this->getComment( $pureKey ) );
					}
				}
			}
			if( isset( $line ) )
				$newLines[] = $line;
		}
		foreach( $this->added as $property ){
			$newLine	= $this->buildLine( $property['key'], $property['value'], $property['comment'] );
			$newLines[]	= $newLine;
		}
		$result			= $file->writeArray( $newLines );
		$this->added	= [];
		$this->deleted	= [];
		$this->renamed	= [];
		$this->read();
		return $result;
	}
}
