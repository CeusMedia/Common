<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class FS_Folder_Treeview_JsonExtended extends FS_Folder_Treeview_Json
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
			'text'			=> $entry->getFilename(),#." (".$children.")",
			'id'			=> rawurlencode( $this->getPathName( $entry ) ),
			'hasChildren'	=> (bool) $children,
			'classes'		=> $this->classNode,
		);
		return $item;
	}
}
