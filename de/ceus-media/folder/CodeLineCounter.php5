<?php
import( 'de.ceus-media.folder.RecursiveLister' );
import( 'de.ceus-media.ui.html.Elements' );
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
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		0.1
 */
/**
 *	Counter for Lines of Code.
 *	@package		folder
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		0.1
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
			$fileName	= $entry->getFilename();
			$pathName	= $entry->getPathname();
			if( substr( $fileName, 0, 1 ) == "_" )
				continue;
			if( preg_match( "@/_@", str_replace( "\\", "/", $pathName ) ) )
				continue;
			$content			= file_get_contents( $entry->getPathname() );
			$numberLength		+= strlen( $content );
			$lines				= count( explode( "\n", $content ) );
			$numberLines		+= $lines;
			$countData			= $this->countLines( $content );

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
	
	/**
	 *	Counts Lines per File.
	 *	@access		public
	 *	@param		string		$content		Content of File
	 *	@return		array
	 */
	public static function countLines( $content )
	{
		$numberCodes	= 0;
		$numberDocs		= 0;
		$numberStrips	= 0;
		$linesCodes		= array();
		$linesDocs		= array();
		$linesStrips	= array();

		$counter	= 0;
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			if( preg_match( "@^(\t| )*/?\*@", $line ) )
			{
				$linesDocs[$counter] = $line;
				$numberDocs++;
			}
			else if( preg_match( "@^(<\?php|<\?|\?>|\}|\{|\t| )*$@", trim( $line ) ) )
			{
				$linesStrips[$counter] = $line;
				$numberStrips++;
			}
			else if( preg_match( "@^(public|protected|private|class|function|final|define|import)@", trim( $line ) ) )
			{
				$linesStrips[$counter] = $line;
				$numberStrips++;
			}
			else
			{
				$linesCodes[$counter] = $line;
				$numberCodes++;
			}
			$counter++;
		}
		$data	= array(
			'numberCodes'	=> $numberCodes,
			'numberDocs'	=> $numberDocs,
			'numberStrips'	=> $numberStrips,
			'linesCodes'	=> $linesCodes,
			'linesDocs'		=> $linesDocs,
			'linesStrips'	=> $linesStrips,
			'ratioCodes'	=> $numberCodes / $counter * 100,
			'ratioDocs'		=> $numberDocs / $counter * 100,
			'ratioStrips'	=> $numberStrips / $counter * 100,
		);
		return $data;
	}
	
	public function buildFileList()
	{
		$list	= array();
		foreach( $this->data['files'] as $pathName => $fileName )
		{
			$link	= UI_HTML_Elements::Link( "view.php5?file=".$pathName."&width=900&height=700", $fileName, 'thickbox' );
			$item	= UI_HTML_Elements::ListItem( $link );
			$list[]	= $item;
		}
		$list	= UI_HTML_Elements::unorderedList( $list );
		return $list;
	}
	
	public function buildFileTableRows( $precision = 0 )
	{
		$list	= array();
		foreach( $this->data['files'] as $pathName => $data )
		{
			$fileName	= substr( $pathName, strlen( $this->data['path'] ) );
			$link	= UI_HTML_Elements::Link( "view.php5?file=".$pathName."&width=900&height=700",  $fileName, 'thickbox' );
			$row	= "
<tr>
  <td>".$link."</td>
  <td>".round( $data['ratioCodes'], $precision )." %</td>
  <td>".round( $data['ratioDocs'], $precision )." %</td>
  <td>".round( $data['ratioStrips'], $precision )." %</td>
</tr>";
			$list[]	= $row;
		}
		$rows	= implode( "", $list );
		return $rows;
	}
}
?>