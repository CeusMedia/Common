<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Counter for Lines of Code.
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
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder;

use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\FS\File\CodeLineCounter as FileCodeLineCounter;
use CeusMedia\Common\FS\Folder\RecursiveLister as FolderRecursiveLister;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Counter for Lines of Code.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class CodeLineCounter
{
	protected $data	= [];

	public function getData( ?string $key = NULL ): array
	{
		//  no Folder scanned yet
		if( !$this->data )
			throw new RuntimeException( 'Please read a Folder first.' );
		//  no Key set
		if( !$key )
			//  return complete Data Array
			return $this->data;

		//  extract possible Key Prefix
		$prefix	= substr( strtolower( $key ), 0, 5 );
		//  Prefix is valid
		if( array_key_exists( $prefix, $this->data ) ){
			//  extract Key without Prefix
			$key	= substr( $key, 5 );
			//  invalid Key
			if( !array_key_exists( $key, $this->data[$prefix] ) )
				throw new InvalidArgumentException( 'Invalid Data Key.' );
			//  return Value for prefixed Key
			return $this->data[$prefix][$key];
		}
		//  prefixless Key is invalid
		else if( !array_key_exists( $key, $this->data[$prefix] ) )
			throw new InvalidArgumentException( 'Invalid Data Key.' );
		//  return Value for prefixless Key
		return $this->data[$key];
	}

	/**
	 *	Counts Files, Folders, Lines of Code and other statistical Information.
	 *	@access		public
	 *	@param		string		$path			Folder to count within
	 *	@param		array		$extensions		List of Code File Extensions
	 *	@return		void
	 */
	public function readFolder( string $path, array $extensions = [] )
	{
		$files			= [];
		$numberCodes	= 0;
		$numberDocs		= 0;
		$numberFiles	= 0;
		$numberLength	= 0;
		$numberLines	= 0;
		$numberStrips	= 0;

		$path	= preg_replace( "@^(.+)/?$@", "\\1/", $path );

		$st		= new Clock();
		$lister	= new FolderRecursiveLister( $path );
		$lister->setExtensions( $extensions );
		$list	= $lister->getList();
		foreach( $list as $entry ){
			$fileName	= str_replace( "\\", "/", $entry->getFilename() );
			$pathName	= str_replace( "\\", "/", $entry->getPathname() );

			if( substr( $fileName, 0, 1 ) == "_" )
				continue;
			if( preg_match( "@/_@", $pathName ) )
				continue;

			$countData	= FileCodeLineCounter::countLines( $pathName );

			unset( $countData['linesCodes'] );
			unset( $countData['linesDocs'] );
			unset( $countData['linesStrips'] );

			$numberLength		+= $countData['length'];
			$numberLines		+= $countData['linesTotal'];

			$numberFiles		++;
			$numberStrips		+= $countData['numberStrips'];
			$numberCodes		+= $countData['numberCodes'];
			$numberDocs			+= $countData['numberDocs'];
			$files[$pathName]	= $countData;
		}
		$linesPerFile	= $numberLines / $numberFiles;
		$this->data		= [
			'number'	=> [
				'files'		=> $numberFiles,
				'lines'		=> $numberLines,
				'codes'		=> $numberCodes,
				'docs'		=> $numberDocs,
				'strips'	=> $numberStrips,
				'length'	=> $numberLength,
			],
			'ratio'			=> [
				'linesPerFile'		=> round( $linesPerFile, 0 ),
				'codesPerFile'		=> round( $numberCodes / $numberFiles, 0 ),
				'docsPerFile'		=> round( $numberDocs / $numberFiles, 0 ),
				'stripsPerFile'		=> round( $numberStrips / $numberFiles, 0 ),
				'codesPerFile%'		=> round( $numberCodes / $numberFiles / $linesPerFile * 100, 1 ),
				'docsPerFile%'		=> round( $numberDocs / $numberFiles / $linesPerFile * 100, 1 ),
				'stripsPerFile%'	=> round( $numberStrips / $numberFiles / $linesPerFile * 100, 1 ),
			],
			'files'		=> $files,
			'seconds'	=> $st->stop( 6 ),
			'path'		=> $path,
		];
	}
}
