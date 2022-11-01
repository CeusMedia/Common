<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder\Treeview;

use CeusMedia\Common\UI\HTML\Tag;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class JsonExtended extends Json
{
	protected function buildFileItem( $entry ): array
	{
		$label		= $entry->getFilename();
		$extension	= $this->getFileExtension( $entry );
		$attributes	= [
			'href' 		=> $this->getFileUrl( $entry ),
			'target'	=> $this->fileTarget
		];
		$link		= Tag::create( "a", $label, $attributes );
		return [
			'text'		=> $link,
			'classes'	=> $this->classLeaf." ".$extension,
		];
	}

	protected function buildFolderItem( $entry ): array
	{
		return [
			'text'			=> $entry->getFilename(),#." (".$children.")",
			'id'			=> rawurlencode( $this->getPathName( $entry ) ),
			'hasChildren'	=> $this->hasChildren( $entry ),
			'classes'		=> $this->classNode,
		];
	}
}
