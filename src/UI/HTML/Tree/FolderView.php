<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\UI\HTML\Tree;

use ArrayObject;
use DirectoryIterator;

/**
 *	Builds Tree View of a Folders content for JQuery Plugin Treeview.
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
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Builds Tree View of a Folders content for JQuery Plugin Treeview.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FolderView
{
	/**	@var		string		$path				Path to Folder to index */
	protected string $path;
	/**	@var		ArrayView	$view	Instance of Tree View */
	protected ArrayView $view;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder to index
	 *	@param		string		$baseUrl			Base URL for linked Items
	 *	@param		string		$queryKey			Query Key for linked Items
	 *	@return		void
	 */
	public function __construct( string $path, string $baseUrl, string $queryKey )
	{
		$this->path		= $path;
		$this->view		= new ArrayView( $baseUrl, $queryKey );
	}

	/**
	 *	Returns HTML Code of Tree.
	 *	@access		public
	 *	@param		string		$currentId			ID current selected in Tree
	 *	@param		array		$attributes			Attributes for List Tag
	 *	@param		bool		$linkNodes			Link Nodes (no Icons possible)
	 *	@param		string		$classNode			CSS Class of Nodes / Folders
	 *	@param		string		$classLeaf			CSS Class of Leafs / Files
	 *	@return		string
	 */
	public function getHtml( string $currentId, array $attributes = [], bool $linkNodes = FALSE, string $classNode = "folder", string $classLeaf = "file" ): string
	{
		$nodes	= new ArrayObject();
		$this->readRecursive( $this->path, $currentId, $nodes, $linkNodes, $classNode, $classLeaf );
		return $this->view->constructTree( $nodes, $currentId, $attributes );
	}

	/**
	 *	Returns JavaScript Code to call Plugin.
	 *	@access		public
	 *	@param		string	    	$selector			JQuery Selector of Tree
	 *	@param		string|NULL		$cookieId			Store Tree in Cookie
	 *	@param		string		    $animated			Speed of Animation (fast|slow)
	 *	@param		bool	    	$unique				Flag: open only 1 Node in every Level
	 *	@param		bool		    $collapsed			Flag: start with collapsed Nodes
	 *	@return		string
	 */
	public function getScript( string $selector, ?string $cookieId = NULL, string $animated = "fast", bool $unique = FALSE, bool $collapsed = FALSE ): string
	{
		return $this->view->buildJavaScript( $selector, $cookieId, $animated, $unique, $collapsed );
	}

	/**
	 *	Reads Folder recursive.
	 *	@access		protected
	 *	@param		string		$path				Path to Folder to index
	 *	@param		string		$currentId			ID current selected in Tree
	 *	@param		ArrayObject	$nodes				Array Object of Folders and Files
	 *	@param		bool		$linkNodes			Link Nodes (no Icons possible)
	 *	@param		string		$classNode			CSS Class of Nodes / Folders
	 *	@param		string		$classLeaf			CSS Class of Leafs / Files
	 *	@return		void
	 */
	protected function readRecursive( string $path, string $currentId, ArrayObject &$nodes, bool $linkNodes = FALSE, string $classNode = "folder", string $classLeaf = "file" ): void
	{
		$files	= [];
		$index	= new DirectoryIterator( $path );
		foreach( $index as $file ){
			if( $file->isDot() )
				continue;
			if( preg_match( "@^\.\w@", $file->getFilename() ) )
				continue;
			if( $file->isDir() ){
				$children	= new ArrayObject();
				$this->readRecursive( $file->getPathname(), $currentId, $children, $linkNodes, $classNode, $classLeaf );
				$dir	= array(
					'label'		=> basename( $file->getPathname() ),
					'type'		=> "node",
					'class'		=> $classNode,
					'linked'	=> $linkNodes,
					'children'	=> $children,
				);
				$nodes[]	= $dir;
			}
			else{
				$classes	= [];
				$info		= pathinfo( $file->getFilename() );
				if( $classLeaf )
					$classes[]	= $classLeaf;
				if( isset( $info['extension'] ) )
					$classes[]	= "file-".strtolower( $info['extension'] );
				$classes	= implode( " ", $classes );
				$files[]	= array(
					'label'		=> $file->getFilename(),
					'type'		=> "leaf",
					'class'		=> $classes,
					'linked'	=> TRUE,
				);
			}
		}
		foreach( $files as $file )
			$nodes[]	= $file;
	}

	public function setTarget( string $target ): self
	{
		$this->view->setTarget( $target );
		return $this;
	}
}