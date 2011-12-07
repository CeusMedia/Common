<?php
/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		UI.HTML.Tree
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.07.2009
 *	@version		0.1
 *
 */
/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
 *
 *	@category		cmClasses
 *	@package		UI.HTML.Tree
 *	@uses			Folder_Lister
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.07.2009
 *	@version		0.1
 */
class UI_HTML_Tree_FolderCheckView
{
	protected $id				= NULL;
	protected $path				= NULL;
	protected $showFolders		= TRUE;
	protected $showFiles		= TRUE;
	protected $selected			= array();
	protected $ignorePatterns	= array();
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
		$list	= array();																			//  empty Array for current Level Items
		$lister	= new Folder_Lister( $path );														//  create Lister for Folder Contents
		$lister->showFolders( $this->showFolders );													//  switch Folders Visibility
		$lister->showFiles( $this->showFiles );														//  switch Files Visibility
		$index	= $lister->getList();																//  get Iterator
		foreach( $index as $item )																	//  iterate current Path
		{
			$ignore		= FALSE;
			$path		= str_replace( "\\", "/", $item->getPathname() );							//  correct Slashes on Windows
			$path		= substr( $path, strlen( $this->path ) );									//  remove Tree Root Path
			foreach( $this->ignorePatterns as $pattern )
				if( preg_match( '@^'.$pattern.'$@', $path ) )
					$ignore	= TRUE;
			if( $ignore	)
				continue;
			$label		= $item->getFilename();
			$sublist	= "";																		//  empty Sublist
			if( $item->isDir() )																	//  current Path has Folders
				$sublist	= $this->buildRecursive( $item->getPathname(), $level + 1, $pathRoot );	//  call Method for nested Folder
			$state		= $this->selected ? in_array( $path, $this->selected ) : TRUE;				//  current Item is set to be selected or no presets at all 
			$check		= UI_HTML_FormElements::CheckBox( $this->inputName.'[]', $path, $state );				//  build Checkbox
			$span		= UI_HTML_Tag::create( 'span', $check.$label );				//  build Label
			$item		= UI_HTML_Elements::ListItem( $span.$sublist, $level );						//  build List Item
			$list[$label]		= $item;																	//  append to List
		}
		ksort( $list );
		$list	= $list ? UI_HTML_Elements::unorderedList( $list, $level ) : "";					//  build List
		return $list;																				//  return List of this Level
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
		if( !$this->id )																			//  no ID bound to Tree HTML Code
			return "";																				//  no Plugin Call
		$default	= array();																		//  Options of 'cmCheckTree' by default
		foreach( $options as $key => $value )														//  iterate custom Options
		{
			if( is_null( $value ) )																	//  Key is set but Value is empty
				unset( $default[$key] );															//  remove Option at all
			else																					//  otherwise
				$default[$key]	= $value;															//  overwrite Options default Value
		}
		$id		= "#".$this->id;																	//  shortcut of ID
		$script	= UI_HTML_JQuery::buildPluginCall( "cmCheckTree", $id, $default );					//  build JavaScript Plugin Call
		if( is_array( $treeviewOptions ) )															//  also Treeview Options are given -> add Plugin
			$script	.= UI_HTML_JQuery::buildPluginCall( "treeview", $id, $treeviewOptions );		//  add Treeview Plugin Call
		return $script;																				//  return build JavaScript
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
		if( !$this->path )																			//  no Path to read is set
			throw new RuntimeException( 'No path set' );											//  exit
		$tree	= $this->buildRecursive( $this->path );												//  build HTML Tree recursively
		if( $this->id )																				//  an ID for Tree is set
			$tree	= UI_HTML_Tag::create( 'div', $tree, array( 'id' => $this->id ) );				//  wrap Tree in DIV with ID
		return $tree;																				//  return finished HTML Tree
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
		$this->ignorePatterns	= array();
		foreach( array_value( $list ) as $pattern)
			$this->addIgnorePattern( $pattern );	
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
?>