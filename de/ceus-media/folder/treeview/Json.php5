<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		folder.treeview
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
import( 'de.ceus-media.alg.time.Clock' );
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	...
 *	@package		folder.treeview
 *	@todo			Code Doc
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
class Folder_Treeview_Json
{
	protected $logFile;
	protected $path;
	
	public $classLeaf		= "file";
	public $classNode		= "folder";
	
	public $fileUrl			= "./?file=";
	public $fileTarget		= NULL;
	
	public function __construct( $basePath, $logFile = NULL )
	{
		$this->basePath		= $basePath;
		$this->logFile		= $logFile;
	}
	
	public function buildJson( $path = "" )
	{
		$clock		= new Alg_Time_Clock;
		$index		= new DirectoryIterator( $this->basePath.$path );
		$folders	= array();
		$files		= array();
		foreach( $index as $entry )
		{
			if( substr( $entry->getFilename(), 0, 1 ) == "." )
				continue;
			if( $entry->isDir() )
				$folders[]	= $this->buildFolderItem( $entry );
			else if( $entry->isFile() )
				$files[]		= $this->buildFileItem( $entry );
		}
		$list	= array_merge( $folders, $files );
		$json	= json_encode( $list );
		if( $this->logFile )
			$this->log( $path, count( $list ), strlen( $json ), $watch->stop( 6, 0 ) );
		return $json;
	}

	
	protected function buildFileItem( $entry )
	{
		$label		= $entry->getFilename();
		$url		= $this->getFileUrl( $entry );
		$attributes	= array(
			'href' 		=> $url,
			'target'	=> $this->fileTarget
		);
		$link		= UI_HTML_Tag::create( "a", $label, $attributes );
		$item		= array(
			'text'		=> $link,
			'classes'	=> $this->classLeaf,
		);
		return $item;
	}
	
	protected function buildFolderItem( $entry )
	{
		$children	= $this->hasChildren( $entry );
		$item	= array(
			'text'			=> $entry->getFilename(),
			'id'			=> rawurlencode( $this->getPathName( $entry ) ),
			'hasChildren'	=> (bool) $children,
			'classes'		=> $this->classNode,
		);
		return $item;
	}

	protected function getFileExtension( $entry )
	{
		$ext	= "";
		$info	= pathinfo( $entry->getPathname() );
		if( isset( $info['extension'] ) )
			$ext	= $info['extension'];
		return $ext;
	}
	
	protected function getFileUrl( $entry )
	{
		return $this->fileUrl.rawurlencode( $this->getPathName( $entry ) );
	}

	protected function getPathname( $entry )
	{
		$path	= str_replace( "\\", "/", $entry->getPathname() );
		$base	= str_replace( "\\", "/", $this->basePath );
		$path	= substr( $path, strlen( $base ) );
		return $path;
	}

	protected function hasChildren( $entry, $countChildren = FALSE )
	{
		$children	= 0;
		$childIndex	= new DirectoryIterator( $entry->getPathname() );
		foreach( $childIndex as $child )
		{
			if( substr( $child->getFilename(), 0, 1 ) == "." )
				continue;
			if( $child->isLink() )
				continue;
			$children++;
			if( !$countChildren )
				break;
		}
		return $children;
	}
	
	protected function log( $path, $numberItems, $jsonLength, $time )
	{
		$message	= '<path time="%1$d" items="%3$d" length="%4$d" time="%5$d">%2$s</path>';
		$message	= '%1$d {%3$d} [%4$d] (%5$d) %2$s';
		$message	= sprintf(
			$message,
			time(),
			$this->basePath.$path,
			$numberItems,
			$jsonLength,
			$time
		);
		error_log( $message."\n", 3, $this->logFile );
	}
}
?>