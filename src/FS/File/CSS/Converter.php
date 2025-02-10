<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Converts CSS between:
 *	- a string representation, typically content from a CSS file
 *	- a list of rules meaning an array representation containing rules and their properties
 *	- a structure out of ADT_CSS_Sheet, ADT_CSS_Rule and ADT_CSS_Property objects
 *	- a file for input and output
 *
 *	Copyright (c) 2011-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use CeusMedia\Common\ADT\CSS\Rule as CssRule;
use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use Exception;

/**
 *	Converts CSS between.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Converter
{
	protected ?CssSheet $sheet	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		CssSheet|NULL	$sheet		Sheet structure
	 *	@return		void
	 */
	public function __construct( ?CssSheet $sheet = NULL )
	{
		if( $sheet )
			$this->fromSheet( $sheet );
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		array			$rules		List of CSS rules
	 *	@return		CssSheet
	 */
	public static function convertArrayToSheet( array $rules ): CssSheet
	{
		$sheet	= new CssSheet;
		foreach( $rules as $selector => $properties )
			$sheet->addRule( new CssRule( $selector, $properties ) );
		return $sheet;
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		array			$rules		List of CSS rules
	 *	@return		string
	 */
	public static function convertArrayToString( array $rules ): string
	{
		return self::convertSheetToString( self::convertArrayToSheet( $rules ) );
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		CssSheet		$sheet		CSS structure
	 *	@return		array
	 */
	public static function convertSheetToArray( CssSheet $sheet ): array
	{
		$level0	= [];
		foreach( $sheet->getRules() as $rule ){
			$level1	= [];
			foreach( $rule->getProperties() as $property )
				$level1[$property->getKey()]	= $property->getValue();
			$level0[$rule->getSelector()]	= $level1;
		}
		return $level0;
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		CssSheet		$sheet		CSS structure
	 *	@return		string
	 */
	public static function convertSheetToString( CssSheet $sheet ): string
	{
		$lines	= [];
		foreach( $sheet->getRules() as $rule ){
			$lines[]	= $rule->getSelector() . ' {';
			foreach( $rule->getProperties() as $property )
				$lines[]	= "\t".$property->getKey().': '.$property->getValue().';';
			$lines[]	= "\t".'}';
		}
		$lines[]	= '';
		return implode( "\n", $lines );
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		string			$css		CSS string
	 *	@return		array
	 *	@throws		Exception
	 */
	public static function convertStringToArray( string $css ): array
	{
		return self::convertSheetToArray( Parser::parseString( $css ) );
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		string			$css		CSS structure
	 *	@return		CssSheet
	 *	@throws		Exception
	 */
	public static function convertStringToSheet( string $css ): CssSheet
	{
		return Parser::parseString( $css );
	}

	/**
	 *	Reads sheet from array.
	 *	@access		public
	 *	@param		array			$rules		List of CSS rules
	 *	@return		self
	 */
	public function fromArray( array $rules ): self
	{
		$this->sheet	= self::convertArrayToSheet( $rules );
		return $this;
	}

	/**
	 *	Reads sheet from file.
	 *	@access		public
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@return		self
	 *	@throws		Exception
	 */
	public function fromFile( string $fileName ): self
	{
		$this->sheet	= Parser::parseFile( $fileName );
		return $this;
	}

	/**
	 *	Reads sheet.
	 *	@access		public
	 *	@param		CssSheet		$sheet		CSS structure
	 *	@return		self
	 */
	public function fromSheet( CssSheet $sheet ): self
	{
		$this->sheet	= $sheet;
		return $this;
	}

	/**
	 *	Reads sheet from string.
	 *	@access		public
	 *	@param		string			$string		CSS structure
	 *	@return		self
	 *	@throws		Exception
	 */
	public function fromString( string $string ): self
	{
		$this->sheet	= Parser::parseString( $string );
		return $this;
	}

	/**
	 *	Returns current sheet as list of rules.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		return Converter::convertSheetToArray( $this->sheet );
	}

	/**
	 *	Writes sheet into file and returns number of written bytes.
	 *	@access		public
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@return		integer			Number of bytes written.
	 */
	public function toFile( string $fileName ): int
	{
		return FileWriter::save( $fileName, Converter::convertSheetToString( $this->sheet ) );
	}

	/**
	 *	Returns current sheet.
	 *	@access		public
	 *	@return		CssSheet
	 */
	public function toSheet(): CssSheet
	{
		return $this->sheet;
	}

	/**
	 *	Returns current sheet as CSS string.
	 *	@access		public
	 *	@return		string
	 */
	public function toString(): string
	{
		return Converter::convertSheetToString( $this->sheet );
	}
}
