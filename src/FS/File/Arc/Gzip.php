<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base gzip File implementation.
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
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Arc;

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Editor as FileEditor;
use RuntimeException;

/**
 *	Base gzip File implementation.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Gzip extends FileEditor
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 *	@throw		RuntimeException
	 *	@throws		FileNotExistingException	if check and file is not existing, not readable or given path is not a file
	 */
	public function __construct( string $fileName )
	{
		if( !function_exists( "gzcompress" ) )
			throw new RuntimeException( "Gzip Extension is not available." );
		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString(): string
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		return gzuncompress( parent::readString() );
	}

	/**
	 *	Writes a String to the File.
	 *	@access		public
	 *	@param		string		$string			String to write to File
	 *	@return		integer|boolean		Number of written bytes or FALSE on fail
	 *	@throws		IoException			if strict and file is not writable
	 *	@throws		IoException			if strict and fallback file creation failed
	 *	@throws		IoException			if number of written bytes does not match content length
	 */
	public function writeString( string $string, bool $strict = TRUE ): int|bool
	{
		/** @noinspection PhpComposerExtensionStubsInspection */
		return parent::writeString( gzcompress( $string ), $strict );
	}
}
