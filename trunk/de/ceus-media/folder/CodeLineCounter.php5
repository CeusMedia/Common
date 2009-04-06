<?php
/**
 *	Counter for Lines of Code.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		folder
 *	@uses			File_Reader
 *	@uses			Folder_RecursiveLister
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		0.2
 */
import( 'de.ceus-media.file.CodeLineCounter' );
import( 'de.ceus-media.folder.RecursiveLister' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Counter for Lines of Code.
 *	@package		folder
 *	@uses			File_Reader
 *	@uses			Folder_RecursiveLister
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		0.2
 *	@todo			Code Doc
 */
class Folder_CodeLineCounter
{
	protected $data	= array();
	
	public function getData( $key = NULL )
	{
		if( !$this->data )															//  no Folder scanned yet
			throw new RuntimeException( 'Please read a Folder first.' );
		if( !$key )																	//  no Key set
			return $this->data;														//  return complete Data Array
			
		$prefix	= substr( strtolower( $key ), 0, 5 );								//  extract possible Key Prefix
		if( in_array( $prefix, array_keys( $this->data ) ) )						//  Prefix is valid
		{
			$key	= substr( $key, 5 );											//  extract Key without Prefix
			if( !array_key_exists( $this->data[$prefix] ) )							//  invalid Key
				throw new InvalidArgumentException( 'Invalid Data Key.' );
			return $this->data[$prefix][$key];										//  return Value for prefixed Key
		}
		else if( !array_key_exists( $key, $this->data[$prefix] ) )					//  prefixless Key is invalid
			throw new InvalidArgumentException( 'Invalid Data Key.' );
		return $this->data[$key];													//  return Value for prefixless Key
	}

	/**
	 *	Counts Files, Folders, Lines of Code and other statistical Information.
	 *	@access		public
	 *	@param		string		$path			Folder to count within
	 *	@param		array		$extensions		List of Code File Extensions
	 *	@return		array
	 */
	public function readFolder( $path, $extensions = array() )
	{
		$files			= array();
		$numberCodes	= 0;
		$numberDocs		= 0;
		$numberFiles	= 0;
		$numberLength	= 0;
		$numberLines	= 0;
		$numberStrips	= 0;

		$path	= preg_replace( "@^(.+)/?$@", "\\1/", $path );

		$st	= new StopWatch();
		$lister	= new Folder_RecursiveLister( $path );
		$lister->setExtensions( $extensions );
		$list	= $lister->getList();
		foreach( $list as $entry )
		{
			$fileName	= str_replace( "\\", "/", $entry->getFilename() );
			$pathName	= str_replace( "\\", "/", $entry->getPathname() );
			
			if( substr( $fileName, 0, 1 ) == "_" )
				continue;
			if( preg_match( "@/_@", $pathName ) )
				continue;

			$countData	= File_CodeLineCounter::countLines( $pathName );
			
			$numberLength		+= $countData['length'];
			$numberLines		+= $countData['linesTotal'];
			
			$numberFiles		++;
			$numberStrips		+= $countData['numberStrips'];
			$numberCodes		+= $countData['numberCodes'];
			$numberDocs			+= $countData['numberDocs'];
			$files[$pathName]	= $countData;
		}
		$linesPerFile	= $numberLines / $numberFiles;
		$this->data	= array(
			'number'	=> array(
				'files'		=> $numberFiles,
				'lines'		=> $numberLines,
				'codes'		=> $numberCodes,
				'docs'		=> $numberDocs,
				'strips'	=> $numberStrips,
				'length'	=> $numberLength,
			),
			'ratio'			=> array(
				'linesPerFile'		=> round( $linesPerFile, 0 ),
				'codesPerFile'		=> round( $numberCodes / $numberFiles, 0 ),
				'docsPerFile'		=> round( $numberDocs / $numberFiles, 0 ),
				'stripsPerFile'		=> round( $numberStrips / $numberFiles, 0 ),
				'codesPerFile%'		=> round( $numberCodes / $numberFiles / $linesPerFile * 100, 1 ),
				'docsPerFile%'		=> round( $numberDocs / $numberFiles / $linesPerFile * 100, 1 ),
				'stripsPerFile%'	=> round( $numberStrips / $numberFiles / $linesPerFile * 100, 1 ),
			), 
			'files'		=> $files,
			'seconds'	=> $st->stop( 6 ),
			'path'		=> $path,
		);
	}
}
?>