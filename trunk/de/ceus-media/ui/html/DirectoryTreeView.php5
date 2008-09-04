<?php
import( 'de.ceus-media.ui.html.TreeView' );
/**
 *	Builds Tree View of Directory.
 *	@package		ui.html
 *	@uses			UI_HTML_TreeView
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.08.2008
 *	@version		0.1
 */
/**
 *	Builds Tree View of Directory.
 *	@package		ui.html
 *	@uses			UI_HTML_TreeView
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.08.2008
 *	@version		0.1
 */
class UI_HTML_DirectoryTreeView
{
	/**	@var		string		$path				Path to Folder to index */
	protected $path;
	/**	@var		UI_HTML_TreeView	$view		Instance of Tree View */
	protected $view;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder to index
	 *	@param		string		$baseUrl			Base URL for linked Items
	 *	@param		string		$queryKey			Query Key for linked Items
	 *	@return		void
	 */
	public function __construct( $path, $baseUrl, $queryKey )
	{
		$this->path		= $path;
		$this->view		= new UI_HTML_TreeView( $baseUrl, $queryKey );
	}
	
	/**
	 *	Returns HTML Code of Tree.
	 *	@access		public
	 *	@param		string		$currentId			ID current selected in Tree
	 *	@param		array		$attributes			Attributes for List Tag
	 *	@param		string		$linkNodes			Link Nodes (no Icons possible)
	 *	@param		string		$classNode			CSS Class of Nodes / Folders
	 *	@param		string		$classLeaf			CSS Class of Leafes / Files
	 *	@return		string
	 */
	public function getHtml( $currentId, $attributes = array(), $linkNodes = FALSE, $classNode = "folder", $classLeaf = "file" )
	{
		$nodes	= new ArrayObject();
		$this->readRecursive( $this->path, $currentId, $nodes, $linkNodes, $classNode, $classLeaf );
		return $this->view->constructTree( $nodes, $currentId, $attributes );
	}
	
	/**
	 *	Returns JavaScript Code to call Plugin.
	 *	@access		public
	 *	@param		string		$selector			JQuery Selector of Tree
	 *	@param		string		$cookieId			Store Tree in Cookie
	 *	@param		string		$animated			Speed of Animation (fast|slow)
	 *	@param		bool		$unique				Flag: open only 1 Node in every Level
	 *	@param		bool		$collapsed			Flag: start with collapsed Nodes
	 *	@return		string
	 */
	public function getScript( $selector, $cookieId = NULL, $animated = "fast", $unique = FALSE, $collapsed = FALSE )
	{
		return $this->view->buildJavaScript( $selector, $cookieId, $animated, $unique, $collapsed );
	}

	/**
	 *	Reads Folder recursive.
	 *	@access		protected
	 *	@param		string		$path				Path to Folder to index
	 *	@param		string		$currentId			ID current selected in Tree
	 *	@param		ArrayObject	$nodes				Array Object of Folders and Files
	 *	@param		string		$linkNodes			Link Nodes (no Icons possible)
	 *	@param		string		$classNode			CSS Class of Nodes / Folders
	 *	@param		string		$classLeaf			CSS Class of Leafes / Files
	 *	@return		void
	 */
	protected function readRecursive( $path, $currentId, &$nodes, $linkNodes, $classNode = "folder", $classLeaf = "file" ) 
	{
		$files	= array();
		$index	= new DirectoryIterator( $path );
		foreach( $index as $file )
		{
			if( $file->isDot() )
				continue;
			if( $index->isDir() )
			{
				$children	= array();
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
			else
			{
				$classes	= array();
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
}
?>