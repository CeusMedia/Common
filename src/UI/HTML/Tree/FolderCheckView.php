<?php
/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
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
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			27.07.2009
 */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\FS\Folder\Lister as FolderLister;
use CeusMedia\Common\UI\HTML\Elements;
use CeusMedia\Common\UI\HTML\FormElements;
use CeusMedia\Common\UI\HTML\Tag;
use RuntimeException;

/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			27.07.2009
 */
class FolderCheckView
{
	protected $id				= NULL;
	protected $path				= NULL;
	protected $showFolders		= TRUE;
	protected $showFiles		= TRUE;
	protected $selected			= [];
	protected $ignorePatterns	= [];
	protected $inputName		= "items";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			URI (local only) of Folder to list
	 *	@return		void
	 */
	public function __construct( $path = NULL )
	{
		if( !is_null( $path ) )
			$this->setPath( $path );
	}

	/**
	 *	Registers a RegExp Pattern for Path Names to be ignored.
	 *	Note: The delimiter is '@'. Please make sure all @ in your Patterns are escaped.
	 *	@access		public
	 *	@param		string		$pattern		RegExp Pattern matching Path Names to ignore
	 *	@return		void
	 */
	public function addIgnorePattern( $pattern )
	{
		$this->ignorePatterns[]	= $pattern;
	}

	/**
	 *	Builds recursively nested HTML Lists and Items from Folder Structure.
	 *	@access		public
	 *	@param		string		$path			URI (local only) of Folder to list
	 *	@param		int			$level			Current nesting depth
	 *	@return		string
	 */
	protected function buildRecursive( $path, $level = 0, $pathRoot = NULL )
	{
		if( !$pathRoot )
			$pathRoot	= $path;
		//  empty Array for current Level Items
		$list	= [];
		//  create Lister for Folder Contents
		$lister	= new FolderLister( $path );
		//  switch Folders Visibility
		$lister->showFolders( $this->showFolders );
		//  switch Files Visibility
		$lister->showFiles( $this->showFiles );
		//  get Iterator
		$index	= $lister->getList();
		//  iterate current Path
		foreach( $index as $item )
		{
			$ignore		= FALSE;
			//  correct Slashes on Windows
			$path		= str_replace( "\\", "/", $item->getPathname() );
			//  remove Tree Root Path
			$path		= substr( $path, strlen( $this->path ) );
			foreach( $this->ignorePatterns as $pattern )
				if( preg_match( '@^'.$pattern.'$@', $path ) )
					$ignore	= TRUE;
			if( $ignore	)
				continue;
			$label		= $item->getFilename();
			//  empty Sublist
			$sublist	= "";
			//  current Path has Folders
			if( $item->isDir() )
				//  call Method for nested Folder
				$sublist	= $this->buildRecursive( $item->getPathname(), $level + 1, $pathRoot );
			//  current Item is set to be selected or no presets at all
			$state		= $this->selected ? in_array( $path, $this->selected ) : TRUE;
			//  build Checkbox
			$check		= FormElements::CheckBox( $this->inputName.'[]', $path, $state );
			//  build Label
			$span		= Tag::create( 'span', $check.$label );
			//  build List Item
			$item		= Elements::ListItem( $span.$sublist, $level );
			//  append to List
			$list[$label]		= $item;
		}
		ksort( $list );
		//  build List
		$list	= $list ? Elements::unorderedList( $list, $level ) : "";
		//  return List of this Level
		return $list;
	}

	/**
	 *	Builds JavaScript Call for JQuery Plugin 'cmCheckTree' to append Events to Tree, but only if an ID is set.
	 *	If the Treeview Options are given (atleast an empty array) the Plugin Call will be appended.
	 *	@access		public
	 *	@param		array		$options			Array of Options for JQuery Plugin 'cmCheckTree'
	 *	@param		array		$treeviewOptions	Array of Options for JQuery Plugin 'treeview'
	 *	@return		string							JavaScript if an ID is set
	 */
	public function buildScript( $options = array(), $treeviewOptions = NULL )
	{
		//  no ID bound to Tree HTML Code
		if( !$this->id )
			//  no Plugin Call
			return "";
		//  Options of 'cmCheckTree' by default
		$default	= [];
		//  iterate custom Options
		foreach( $options as $key => $value )
		{
			//  Key is set but Value is empty
			if( is_null( $value ) )
				//  remove Option at all
				unset( $default[$key] );
			//  otherwise
			else
				//  overwrite Options default Value
				$default[$key]	= $value;
		}
		//  shortcut of ID
		$id		= "#".$this->id;
		//  build JavaScript Plugin Call
		$script	= JQuery::buildPluginCall( "cmCheckTree", $id, $default );
		//  also Treeview Options are given -> add Plugin
		if( is_array( $treeviewOptions ) )
			//  add Treeview Plugin Call
			$script	.= JQuery::buildPluginCall( "treeview", $id, $treeviewOptions );
		//  return build JavaScript
		return $script;
	}

	/**
	 *	Builds and returns HTML Tree of Folders and/or Files the set Path contains.
	 *	If an ID is set, the Tree is Wrapped in a DIV with this ID.
	 *	@access		public
	 *	@throws		RuntimeException if not Path is set
	 *	@return		void
	 */
	public function buildTree()
	{
		//  no Path to read is set
		if( !$this->path )
			//  exit
			throw new RuntimeException( 'No path set' );
		//  build HTML Tree recursively
		$tree	= $this->buildRecursive( $this->path );
		//  an ID for Tree is set
		if( $this->id )
			//  wrap Tree in DIV with ID
			$tree	= Tag::create( 'div', $tree, array( 'id' => $this->id ) );
		//  return finished HTML Tree
		return $tree;
	}

	/**
	 *	Sets ID of Tree to bind JQuery Plugin 'cmCheckTree' Events. No Events of not set.
	 *	@access		public
	 *	@param		string		$id				Tree ID for binding Jquery Plugin cmCheckTree, no Events if set to NULL|FALSE
	 *	@return		void
	 */
	public function setId( $id )
	{
		$this->id	= $id;
	}

	/**
	 *	Sets List of RegExp Pattern of Path Names to ignore.
	 *	Note: The delimiter is '@'. Please make sure all @ in your Patterns are escaped.
	 *	@access		public
	 *	@param		array		$list			List of RegExp Patterns to ignore
	 *	@return		void
	 */
	public function setIgnorePatterns( $list )
	{
		$this->ignorePatterns	= [];
		foreach( array_value( $list ) as $pattern)
			$this->addIgnorePattern( $pattern );
	}

	/**
	 *	Sets the Input Field Name of all Checkboxes which are arranged to submit an Array.
	 *	@access		public
	 *	@param		string		$name			Input Field Name of the Checkboxes, default: items
	 *	@return		void
	 */
	public function setInputName( $name )
	{
		$this->inputName	= $name;
	}

	/**
	 *	Sets Path to Folder to list.
	 *	@access		public
	 *	@param		string		$path			URI (local only) of Folder to list
	 *	@throws		RuntimeException if path is not existing
	 *	@return		void
	 */
	public function setPath( $path )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Invalid path' );
		$this->path	= $path;
	}

	/**
	 *	Sets checked Folders. Set to NULL to preselect all Folders.
	 *	@access		public
	 *	@param		array		$list			List of Folders to preselect, NULL for all
	 *	@return		void
	 */
	public function setSelected( $list )
	{
		$this->selected	= $list;
	}

	/**
	 *	Sets whether Files are to be listed.
	 *	@access		public
	 *	@param		bool		$state			Flag: show Files
	 *	@return		void
	 */
	public function showFiles( $state )
	{
		$this->showFiles	= (bool) $state;
	}

	/**
	 *	Sets whether Folders are to be listed.
	 *	@access		public
	 *	@param		bool		$state			Flag: show Folders
	 *	@return		void
	 */
	public function showFolders( $state )
	{
		$this->showFolders	= (bool) $state;
	}
}
