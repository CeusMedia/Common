<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\UI\HTML\Tree;

use CeusMedia\Common\FS\Folder\Lister as FolderLister;
use CeusMedia\Common\UI\HTML\Elements as HtmlElements;
use CeusMedia\Common\UI\HTML\FormElements as HtmlFormElements;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use CeusMedia\Common\UI\HTML\JQuery;

use RuntimeException;

/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *
 */
/**
 *	Builds HTML Tree of Folder Entries with Checkboxes for Selection.
 *	If an ID is set the JQuery Plugins 'cmCheckTree' and 'treeview' can be bound.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FolderCheckView
{
	protected ?string $id				= NULL;
	protected ?string $path				= NULL;
	protected bool $showFolders	    	= TRUE;
	protected bool $showFiles	    	= TRUE;
	protected array $selected			= [];
	protected array $ignorePatterns	    = [];
	protected string $inputName		    = "items";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL	    $path			URI (local only) of Folder to list
	 *	@return		void
	 */
	public function __construct( ?string $path = NULL )
	{
		if( !is_null( $path ) )
			$this->setPath( $path );
	}

	/**
	 *	Registers a RegExp Pattern for Path Names to be ignored.
	 *	Note: The delimiter is '@'. Please make sure all @ in your Patterns are escaped.
	 *	@access		public
	 *	@param		string		$pattern		RegExp Pattern matching Path Names to ignore
	 *	@return		self
	 */
	public function addIgnorePattern( string $pattern ): self
	{
		$this->ignorePatterns[]	= $pattern;
		return $this;
	}

	/**
	 *	Builds JavaScript Call for JQuery Plugin 'cmCheckTree' to append Events to Tree, but only if an ID is set.
	 *	If the Treeview Options are given (at least an empty array) the Plugin Call will be appended.
	 *	@access		public
	 *	@param		array		$options			Array of Options for JQuery Plugin 'cmCheckTree'
	 *	@param		array|NULL	$treeviewOptions	Array of Options for JQuery Plugin 'treeview'
	 *	@return		string							JavaScript if an ID is set
	 */
	public function buildScript( array $options = [], ?array $treeviewOptions = NULL ): string
	{
		//  no ID bound to Tree HTML Code
		if( !$this->id )
			//  no Plugin Call
			return "";
		//  Options of 'cmCheckTree' by default
		$default	= [];
		//  iterate custom Options
		foreach( $options as $key => $value ){
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
	 *	@return		string
	 */
	public function buildTree(): string
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
			$tree	= HtmlTag::create( 'div', $tree, array( 'id' => $this->id ) );
		//  return finished HTML Tree
		return $tree;
	}

	/**
	 *	Sets ID of Tree to bind JQuery Plugin 'cmCheckTree' Events. No Events of not set.
	 *	@access		public
	 *	@param		string		$id				Tree ID for binding Jquery Plugin cmCheckTree, no Events if set to NULL|FALSE
	 *	@return		self
	 */
	public function setId( string $id ): self
	{
		$this->id	= $id;
		return $this;
	}

	/**
	 *	Builds recursively nested HTML Lists and Items from Folder Structure.
	 *	@access		public
	 *	@param		string		    $path			URI (local only) of Folder to list
	 *	@param		int			    $level			Current nesting depth
	 *	@param		string|NULL		$pathRoot		URI (local only) of Folder to list
	 *	@return		string
	 */
	protected function buildRecursive( string $path, int $level = 0, ?string $pathRoot = NULL ): string
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
		foreach( $index as $item ){
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
			$state		= !$this->selected || in_array( $path, $this->selected );
			//  build Checkbox
			$check		= HtmlFormElements::CheckBox( $this->inputName.'[]', $path, $state );
			//  build Label
			$span		= HtmlTag::create( 'span', $check.$label );
			//  build List Item
			$item		= HtmlElements::ListItem( $span.$sublist, $level );
			//  append to List
			$list[$label]		= $item;
		}
		ksort( $list );
		//  build and return List of this level
		return $list ? HtmlElements::unorderedList( $list, $level ) : '';
	}

	/**
	 *	Sets List of RegExp Pattern of Path Names to ignore.
	 *	Note: The delimiter is '@'. Please make sure all @ in your Patterns are escaped.
	 *	@access		public
	 *	@param		array		$list			List of RegExp Patterns to ignore
	 *	@return		self
	 */
	public function setIgnorePatterns( array $list ): self
	{
		$this->ignorePatterns	= [];
		foreach($list as $pattern )
			$this->addIgnorePattern( $pattern );
		return $this;
	}

	/**
	 *	Sets the Input Field Name of all Checkboxes which are arranged to submit an Array.
	 *	@access		public
	 *	@param		string		$name			Input Field Name of the Checkboxes, default: items
	 *	@return		self
	 */
	public function setInputName( string $name ): self
	{
		$this->inputName	= $name;
		return $this;
	}

	/**
	 *	Sets Path to Folder to list.
	 *	@access		public
	 *	@param		string		$path			URI (local only) of Folder to list
	 *	@throws		RuntimeException if path is not existing
	 *	@return		self
	 */
	public function setPath( string $path ): self
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Invalid path' );
		$this->path	= $path;
		return $this;
	}

	/**
	 *	Sets checked Folders. Set to NULL to preselect all Folders.
	 *	@access		public
	 *	@param		array		$list			List of Folders to preselect, NULL for all
	 *	@return		self
	 */
	public function setSelected( array $list ): self
	{
		$this->selected	= $list;
		return $this;
	}

	/**
	 *	Sets whether Files are to be listed.
	 *	@access		public
	 *	@param		bool		$state			Flag: show Files
	 *	@return		self
	 */
	public function showFiles( bool $state ): self
	{
		$this->showFiles	= $state;
		return $this;
	}

	/**
	 *	Sets whether Folders are to be listed.
	 *	@access		public
	 *	@param		bool		$state			Flag: show Folders
	 *	@return		self
	 */
	public function showFolders( bool $state ): self
	{
		$this->showFolders	= $state;
		return $this;
	}
}