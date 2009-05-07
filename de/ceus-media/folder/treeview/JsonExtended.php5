<?php
import( 'de.ceus-media.folder.treeview.Json' );
class Folder_Treeview_JsonExtended extends Folder_Treeview_Json
{
	protected function buildFileItem( $entry )
	{
		$label		= $entry->getFilename();
		$extension	= $this->getFileExtension( $entry );
		$attributes	= array(
			'href' 		=> $this->getFileUrl( $entry ),
			'target'	=> $this->fileTarget
		);
		$link		= UI_HTML_Tag::create( "a", $label, $attributes );
		$item		= array(
			'text'		=> $link,
			'classes'	=> $this->classLeaf." ".$extension,
		);
		return $item;
	}
	
	protected function buildFolderItem( $entry )
	{
		$children	= $this->hasChildren( $entry, TRUE );
		$item	= array(
			'text'			=> $entry->getFilename()." (".$children.")",
			'id'			=> rawurlencode( $this->getPathName( $entry ) ),
			'hasChildren'	=> (bool) $children,
			'classes'		=> $this->classNode,
		);
		return $item;
	}
}
?>