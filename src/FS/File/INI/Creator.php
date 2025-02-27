<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for File in .ini-Format.
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
 *	Builder for File in .ini-Format.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Creator
{
	/**	@var	array					$data			Data of Ini File */
	protected array $data				= [];

	/**	@var	string|NULL				$currentSection	Current working Section */
	protected ?string $currentSection	= NULL;

	/**	@var	bool					$useSections	Flag: use Sections within Ini File */
	protected bool $useSections			= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$useSections	Flag: use Sections within Ini File
	 *	@return		void
	 */
	public function __construct( bool $useSections = FALSE )
	{
		$this->useSections = $useSections;
	}

	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		string|NULL	$comment		Comment of Property (optional)
	 *	@return		void
	 */
	public function addProperty( string $key, string $value, ?string $comment = NULL )
	{
		if( !$this->useSections ){
			$this->data[$key]['key']		= $key;
			$this->data[$key]['value']		= $value;
			$this->data[$key]['comment']	= $comment;
		}
		else if( $this->currentSection )
			$this->addPropertyToSection( $key, $value, $this->currentSection, $comment );
		else
			throw new InvalidArgumentException( 'No section given' );
	}

	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		string		$section		Name of new Section
	 *	@param		string|NULL	$comment		Comment of Property (optional)
	 *	@return		void
	 */
	public function addPropertyToSection( string $key, string $value, string $section, ?string $comment = NULL )
	{
		$this->data[$section][$key]['key']		= $key;
		$this->data[$section][$key]['value']	= $value;
		$this->data[$section][$key]['comment']	= $comment;
	}

	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string		$section		Name of new Section
	 *	@return		void
	 */
	public function addSection( string $section )
	{
		if ( !( isset( $this->data[$section] ) && is_array( $this->data[$section] ) ) )
			$this->data[$section]	= [];
		$this->currentSection	= $section;
	}

	/**
	 *	Returns a build Property line.
	 *	@access		protected
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Value of Property
	 *	@param		string|NULL	$comment		Comment of Property
	 *	@return		string
	 */
	protected function buildLine( string $key, string $value, ?string $comment = NULL ): string
	{
		$breaksKey		= 4 - floor( strlen( $key ) / 8 );
		$breaksValue	= 4 - floor( strlen( $value ) / 8 );
		if( $breaksKey < 1 )
			$breaksKey = 1;
		if( $breaksValue < 1 )
			$breaksValue = 1;
		$line = $key.str_repeat( "\t", $breaksKey )."=".$value;
		if( $comment )
			$line .= str_repeat( "\t", $breaksValue )."; ".$comment;
		return $line;
	}

	/**
	 *	Creates and writes Settings to File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of new Ini File
	 *	@return		integer
	 */
	public function write( string $fileName ): int
	{
		$lines	= [];
		if( $this->useSections ){
			foreach( $this->data as $section => $sectionPairs ){
				$lines[]	= "[".$section."]";
				foreach ( $sectionPairs as $key => $data ){
					$value		= $data['value'];
					$comment	= $data['comment'];
					$lines[]	= $this->buildLine( $key, $value, $comment);
				}
				$lines[]	= "";
			}
		}
		else{
			foreach( $this->data as $key => $data ){
				$value		= $data['value'];
				$comment	= $data['comment'];
				$lines[]	= $this->buildLine( $key, $value, $comment);
			}
			$lines[]	= "";
		}
		$file		= new FileWriter( $fileName, 0664 );
		return $file->writeArray( $lines );
	}
}
