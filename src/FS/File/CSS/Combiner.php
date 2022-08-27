<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Combines CSS Files imported in one CSS File.
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use Exception;
use RuntimeException;

/**
 *	Combines CSS Files imported in one CSS File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Combiner
{
	/**	@var		string		$prefix			Prefix of combined File Name */
	public $prefix					= "";

	/**	@var		string		$suffix			Suffix of combined File Name */
	public $suffix					= ".combined";

	/**	@var		string		$importPattern	Pattern of imported CSS Files */
	public $importPattern			= '#^@import (url\(\s*)?["\'](.*)["\'](\s*\))?;?$#i';

	/**	@var		array		$statistics		Statistical Data */
	public $statistics				= [];

	/**
	 *	Combines all CSS Files imported in Style File, saves Combination File and returns File URI of Combination File.
	 *	@access		public
	 *	@param		string		$fileUri		...
	 *	@return		string
	 *	@throws		Exception
	 */
	public function combineFile( string $fileUri ): string
	{
		$pathName	= dirname( realpath( $fileUri ) )."/";
		$fileBase	= preg_replace( "@\.css@", "", basename( $fileUri  ));

		if( !file_exists( $fileUri ) )
			throw new Exception( "Style File '".$fileUri."' is not existing." );

		$this->statistics	= [];
		$content	= file_get_contents( $fileUri );

		$content	= $this->combineString( $pathName, $content );
		$fileName	= $this->prefix.$fileBase.$this->suffix.".css";
		$fileUri	= $pathName.$fileName;
		$fileUri	= str_replace( "\\", "/", $fileUri );

		file_put_contents( $fileUri, $content );
		return $fileUri;
	}

	/**
	 *	Combines all CSS Files imported in CSS String and returns Combination String;
	 *	@access		public
	 *	@param		string		$path				Style Path
	 *	@param		string		$content			...
	 *	@param		boolean		$throwException		Flag: throw on error, default: no
	 *	@return		string
	 */
	public function combineString( string $path, string $content, bool $throwException = FALSE ): string
	{
		$listLines	= [];
		$listImport	= [];
		$this->statistics['sizeOriginal']	= 0;
		$this->statistics['sizeCombined']	= 0;
		$this->statistics['sizeCompressed']	= 0;
		$this->statistics['numberFiles']	= 0;
		$this->statistics['filesFound']		= [];
		$this->statistics['filesSkipped']	= [];
		$content	= preg_replace( '/\/\*.+\*\//sU', '', $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line ){
			$line	= trim( $line );
			if( !$line )
				continue;
			if( preg_match( $this->importPattern, $line ) ){
				preg_match_all( $this->importPattern, $line, $matches );
				$fileName	= $matches[2][0];
				$this->statistics['filesFound'][]	= $fileName;

				if( !file_exists( $path.$fileName ) ){
					if( $throwException )
						throw new RuntimeException( 'CSS File "'.$fileName.'" is not existing.' );
					$this->statistics['filesSkipped'][] = $fileName;
					continue;
				}

				$content	= file_get_contents( $path.$fileName );
				$content	= $this->reviseStyle( $content );
				$this->statistics['numberFiles']	++;
				$this->statistics['sizeOriginal']	+= strlen( $content );
//			$depth	= substr
				if( substr_count( $fileName, "/" ) )
					$content	= preg_replace( "@(\.\./){1}([^\.])@i", "\\2", $content );
				$listImport[]	= "/*  --  ".$fileName."  --  */";
				$listImport[]	= $content."\n";
			}
			else
				$listLines[]	= $line;
		}
		$content	= implode( "\n", $listImport ).implode( "\n", $listLines );
		$this->statistics['sizeCombined']	= strlen( $content );
		return $content;
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
	 *	Callback Method for additional Modifications before Combination.
	 *	@access		protected
	 *	@param		string		$content		Content of Style File
	 *	@return		string		Revised Content of Style File
	 */
	protected function reviseStyle( string $content ): string
	{
		return $content;
	}

	/**
	 *	Sets Prefix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of combined File Name
	 *	@return		self
	 */
	public function setPrefix( string $prefix ): self
	{
		if( trim( $prefix ) )
			$this->prefix	= $prefix;
		return $this;
	}

	/**
	 *	Sets Suffix of combined File Name.
	 *	@access		public
	 *	@param		string		$suffix			Suffix of combined File Name
	 *	@return		self
	 */
	public function setSuffix( string $suffix ): self
	{
		if( trim( $suffix ) )
			$this->suffix	= $suffix;
		return $this;
	}
}
