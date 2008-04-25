<?php
import( 'de.ceus-media.folder.RecursiveLister' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Counter for Lines of Code.
 *	@package		folder
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.1
 */
/**
 *	Counter for Lines of Code.
 *	@package		folder
 *	@uses			Folder_RecursiveLister
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.1
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
		$countCodes		= 0;
		$countDocs		= 0;
		$countFiles		= 0;
		$countFolders	= 0;
		$countLength	= 0;
		$countLines		= 0;
		$countStrips	= 0;

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
			$countLength		+= strlen( $content );
			$lines				= count( explode( "\n", $content ) );
			$countLines			+= $lines;
			$countData			= $this->countLines( $content );

			$countFiles			++;
			$countStrips		+= $countData['countStrips'];
			$countCodes			+= $countData['countCodes'];
			$countDocs			+= $countData['countDocs'];
			$files[$pathName]	= $countData;
		}
		$linesPerFile	= $countLines / $countFiles;
		$this->data	= array(
			'count'	=> array(
				'files'		=> $countFiles,
				'lines'		=> $countLines,
				'codes'		=> $countCodes,
				'docs'		=> $countDocs,
				'strips'	=> $countStrips,
				'length'	=> $countLength,
			),
			'ratio'			=> array(
				'linesPerFile'		=> round( $linesPerFile, 0 ),
				'codesPerFile'		=> round( $countCodes / $countFiles, 0 ),
				'docsPerFile'		=> round( $countDocs / $countFiles, 0 ),
				'stripsPerFile'		=> round( $countStrips / $countFiles, 0 ),
				'codesPerFile%'		=> round( $countCodes / $countFiles / $linesPerFile * 100, 1 ),
				'docsPerFile%'		=> round( $countDocs / $countFiles / $linesPerFile * 100, 1 ),
				'stripsPerFile%'	=> round( $countStrips / $countFiles / $linesPerFile * 100, 1 ),
			), 
			'files'			=> $files,
			'seconds'		=> $st->stop( 0, 1 ),
			'path'			=> $path,
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
		$countCodes		= 0;
		$countDocs		= 0;
		$countStrips	= 0;
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
				$countDocs++;
			}
			else if( preg_match( "@^(<\?php|<\?|\?>|\}|\{|\t| )*$@", trim( $line ) ) )
			{
				$linesStrips[$counter] = $line;
				$countStrips++;
			}
			else if( preg_match( "@^(public|protected|private|class|function|final|define|import)@", trim( $line ) ) )
			{
				$linesStrips[$counter] = $line;
				$countStrips++;
			}
			else
			{
				$linesCodes[$counter] = $line;
				$countCodes++;
			}
			$counter++;
		}
		$data	= array(
			'countCodes'	=> $countCodes,
			'countDocs'		=> $countDocs,
			'countStrips'	=> $countStrips,
			'linesCodes'	=> $linesCodes,
			'linesDocs'		=> $linesDocs,
			'linesStrips'	=> $linesStrips,
			'ratioCodes'	=> $countCodes / $counter * 100,
			'ratioDocs'		=> $countDocs / $counter * 100,
			'ratioStrips'	=> $countStrips / $counter * 100,
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
			$fileName	= substr( $pathName, strlen( $this->data['path'] ) + 1 );
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