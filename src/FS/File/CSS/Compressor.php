<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Compresses CSS Files.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use Exception;

/**
 *	Compresses CSS Files.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Compressor
{
	/**	@var		string			$prefix			Prefix of compressed File Name */
	public $prefix		= "";

	/**	@var		array			$statistics		Statistical Data */
	public $statistics	= [];

	/**	@var		string			$suffix			Suffix of compressed File Name */
	public $suffix		= ".min";

/*	static public function compressFile( $fileName, $oneLine = FALSE ){
		return self::compressString( FileReader::load( $fileName ), $oneLine );
	}
*/

	public function compress( string $string, bool $oneLine = FALSE ): string
	{
		$this->statistics	= [];
		$this->statistics['before']	= strlen( $string );
		$string	= self::compressString( $string, $oneLine );
		$this->statistics['after']	= strlen( $string );
		return $string;
	}

	/**
	 *	Reads and compresses a CSS File and returns Length of compressed File.
	 *	@access		public
	 *	@param		string		$fileUri		Full URI of CSS File
	 *	@return		string
	 *	@throws		Exception					if file is not existing
	 */
	public function compressFile( string $fileUri ): string
	{
		if( !file_exists( $fileUri ) )
			throw new Exception( "Style File '".$fileUri."' is not existing." );

		$content	= self::compressString( file_get_contents( $fileUri ) );
		$pathName	= dirname( $fileUri );
		$styleFile	= basename( $fileUri );
		$styleName	= preg_replace( "@\.css$@", "", $styleFile );
		$fileName	= $this->prefix.$styleName.$this->suffix.".css";
		$fileUri	= $pathName."/".$fileName;
		$fileUri	= str_replace( "\\", "/", $fileUri );
		file_put_contents( $fileUri, $content );
		return $fileUri;
	}

	static public function compressSheet( CssSheet $sheet, bool $oneLine = FALSE ): string
	{
		$converter	= new Converter( $sheet );
		return self::compressString( $converter->toString(), $oneLine );
	}

  /**
   * @param string $string
   * @param bool $oneLine
   * @return string
   */
	static public function compressString( string $string, bool $oneLine = FALSE ): string
	{
		//  remove comments
		$string	= preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string );
		//  remove space after colons
		$string	= str_replace( ': ', ':', $string );
		//  remove whitespace
		$string	= str_replace( ["\r\n", "\r", "\n", "\t", '  ', '    '], '', $string );
		//  remove spaces after selectors
		$string	= preg_replace( '@\s*\{\s*@s', "{", $string );
		//  remove spaces after selectors
		$string	= preg_replace( '@\s*\}@s', "}", $string );
		//  remove leading and trailing space
		return trim( $string );
	}

	/**
	 *	Returns statistical Data of last Combination.
	 *	@access		public
	 *	@return		array
	 */
	public function getStatistics(): array
	{
		return $this->statistics;
	}

	/**
	 *	Sets Prefix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of compressed File Name
	 *	@return		self
	 */
	public function setPrefix( string $prefix ): self
	{
		if( trim( $prefix ) )
			$this->prefix	= $prefix;
		return $this;
	}

	/**
	 *	Sets Suffix of compressed File Name.
	 *	@access		public
	 *	@param		string		$suffix			Suffix of compressed File Name
	 *	@return		self
	 */
	public function setSuffix( string $suffix ): self
	{
		if( trim( $suffix ) )
			$this->suffix	= $suffix;
		return $this;
	}
}
