<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	Copyright (c) 2011-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use Exception;
use RuntimeException;

/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	protected ?string $fileName		= NULL;

	protected ?CssSheet $sheet		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$fileName		Relative or absolute file URI
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->setFileName( $fileName );
	}

	/**
	 *	Returns content of CSS file as list of rules.
	 *	@access		public
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	public function getRules(): array
	{
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return Converter::convertSheetToArray( $this->sheet );
	}

	/**
	 *	Returns content of CSS file as sheet structure.
	 *	@access		public
	 *	@return		CssSheet
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	public function getSheet(): CssSheet
	{
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return $this->sheet;
	}

	/**
	 *	Loads a CSS file and returns sheet structure statically.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		CssSheet
	 *	@throws		Exception
	 */
	public static function load( string $fileName ): CssSheet
	{
		return Parser::parseFile( $fileName );
	}

	/**
	 *	Points reader to a CSS file which will be parsed and stored internally.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		self
	 *	@throws		Exception
	 */
	public function setFileName( string $fileName ): self
	{
		$this->fileName	= $fileName;
		$this->sheet	= self::load( $fileName );
		return $this;
	}
}
