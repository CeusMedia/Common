<?php
/**
 *	Class for encoding PHP File.
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
 *	@package		CeusMedia_Common_FS_File_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\PHP;

use CeusMedia\Common\FS\File\Editor as FileEditor;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use RuntimeException;

/**
 *	Class for encoding PHP File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Encoder
{
	/**	@var		string		$incodePrefix		Prefix of inner Code Wrapper */
	protected $incodePrefix		= "";

	/**	@var		string		$incodeSuffix		Suffix of inner Code Wrapper */
	protected $incodeSuffix		= "";

	/**	@var		string		$outcodePrefix		Prefix of outer Code Wrapper */
	protected $outcodePrefix	= "";

	/**	@var		string		$outcodeSuffix		Suffix of outer Code Wrapper */
	protected $outcodeSuffix	= "";

	/**	@var		string		$filePrefix			Prefix of compressed PHP File */
	public $filePrefix			= "code.";

	/**	@var		string		$fileSuffix			Suffix of compressed PHP File */
	public $fileSuffix			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 * 	@return		void
	 */
	public function __construct()
	{
		$this->incodePrefix		= "?".">";
		$this->incodeSuffix		= "<"."?";
		$this->outcodePrefix	= "<"."? print( '<xmp>'.gzinflate(base64_decode('";
		$this->outcodePrefix	= "<"."? eval( gzinflate(base64_decode('";
		$this->outcodeSuffix	= "')));?".">";
	}

	/**
	 *	Returns decoded and stripped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	public function decode( string $php ): string
	{
		$code	= substr( $php, strlen( $this->outcodePrefix) , -strlen( $this->outcodeSuffix ) );
		$php 	= $this->decodeHash( $code );
		return $php;
	}

	/**
	 *	Decodes an encoded PHP File.
	 *	@access		public
	 *	@param		string		$fileName		...
	 *	@param		boolean		$overwrite		...
	 * 	@return		boolean
	 */
	public function decodeFile( string $fileName, bool $overwrite = FALSE ): bool
	{
		if( file_exists( $fileName ) ){
			if( $this->isEncoded( $fileName ) ){
				$file	= new FileEditor( $fileName );
				$php	= $file->readString();
				$code	= $this->encode( $php );
				$dirname	= dirname( $fileName );
				$basename	= basename( $fileName );
				$target	= $dirname."/".substr( $basename, strlen( $this->filePrefix) , -strlen( $this->fileSuffix ) );
				if( $fileName == $target && !$overwrite )
					throw new RuntimeException( 'File cannot be overwritten, use Parameter "overwrite".' );
				$file->writeString( $code );
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 *	Returns Hash decoded PHP Content.
	 *	@access		protected
	 *	@param		string		$code		Encoded PHP Content
	 * 	@return		string
	 */
	protected function decodeHash( string $code ): string
	{
		$php	= gzinflate( base64_decode( $code ) );
		$php	= substr( $php, strlen( $this->incodePrefix) , -strlen( $this->incodeSuffix ) );
		return $php;
	}

	/**
	 *	Returns encoded and wrapped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	public function encode( string $php ): string
	{
		$code	= $this->encodeHash( $php );
		$php	= $this->outcodePrefix.$code.$this->outcodeSuffix;
		return $php;
	}

	/**
	 *	Encodes a PHP File.
	 *	@access		public
	 *	@param		string		$fileName		...
	 *	@param		boolean		$overwrite		...
	 * 	@return		bool
	 */
	public function encodeFile( string $fileName, bool $overwrite = FALSE ): bool
	{
		if( !file_exists( $fileName ) )
			return FALSE;
		if( $this->isEncoded( $fileName ) )
			return TRUE;
		$php		= FileReader::load( $fileName );
		$code		= $this->encode( $php );
		$dirname	= dirname( $fileName );
		$basename	= basename( $fileName );
		$target		= $dirname."/".$this->filePrefix.$basename.$this->fileSuffix;
		if( $fileName == $target && !$overwrite )
			throw new RuntimeException( 'File cannot be overwritten, use Parameter "overwrite".' );
//		copy( $fileName, "#".$fileName );
		return (bool) FileWriter::save( $target, $code );
	}

	/**
	 *	Returns encoded PHP Content.
	 *	@access		protected
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	protected function encodeHash( string $php ): string
	{
		return base64_encode( gzdeflate( $this->incodePrefix.$php.$this->incodeSuffix ) );
	}

	/**
	 *	Indicated whether a PHP File ist encoded.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to be checked
	 * 	@return		bool
	 */
	public function isEncoded( string $fileName ): bool
	{
		if( file_exists( $fileName ) ){
			$fp	= fopen( $fileName, "r" );
			$code	= fgets( $fp, strlen( $this->outcodePrefix ) );
			if( $code == $this->outcodePrefix )
				return TRUE;
		}
		return FALSE;
	}
}
