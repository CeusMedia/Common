<?php
import( "de.ceus-media.adt.OptionObject" );
/**
 *	Build HTML from XML Tree.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Build HTML from XML Tree.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class XTree extends OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( 'target', '_self' );
		$this->setOption( 'link', '?oid=' );
		$this->setOption( 'image_root',	array( 'source' => 'images/root.png',	'title' => 'no title' ) );
		$this->setOption( 'image_node',	array( 'source' => 'images/node.png',	'title' => 'no title' ) );
		$this->setOption( 'image_leaf', 	array( 'source' => 'images/leaf.png',	'title' => 'no title' ) );
		$this->setOption( 'rack_object', 	'rt' );
		$this->setOption( 'show_content', false );
		$this->setOption( 'show_attribs', 	false );
		$this->setOption( 'classes', 		array(
			'div_node'	=> 'node',
			'div_open'	=> 'nodeopen',
			'div_shut'		=> 'nodeshut',
			'span_icon'	=> 'icon',
			'span_node'	=> 'node',
			'span_leaf'	=> 'leaf',
			'link_node'	=> 'node',
			'link_leaf'		=> 'leaf',
			) );
	}
	
	/**
	 *	Sets Style Classes.
	 *	@access		public
	 *	@param		string		$key		Class key
	 *	@param		string		$value		Class name (css)
	 *	@return		void
	 */
	function setClass( $key, $value )
	{
		$classes	= $this->getOption( 'classes' );
		$classes[$key]	= $value;
		$this->setOption( 'classes', $classes );
	}
	
	/**
	 *	Returns image of Node.
	 *	@access		private
	 *	@param		string		$key		Image key
	 *	@return		string
	 */
	function _getImage( $key )
	{
		$code = "";
		if( $data = $this->getOption( 'image_'.$key ) )
			$code = "<img src='".$data['source']."' alt='".$data['alt']."' alt='".$data['title']."' border='0'>";
		return $code;
	}
	
	/**
	 *	Build dynamic HTML from Tree.
	 *	@access		private
	 *	@param		XML_DOM_Node	$tree		Tree
	 *	@param		int				$offset		Offset = recursive deepth for spaces
	 *	@return		array
	 */
	function buildFromTree ($tree, $offset = 0)
	{
		$oid		= $tree->getOid();
		$tag		= $tree->getNodeName();
		$tabs	= str_repeat ("  ", $offset);
		$rackobj	= $this->getOption( 'rack_object' );
		$classes	= $this->getOption( 'classes' );
		$link		= $tag;
		if( $this->getOption( 'link' ) )
			$link	= "<a class='".$classes['link_node']."' href='".$this->getOption( 'link' ).$oid."' target='".$this->getOption( 'target' )."'>".$tag."</a>";
		$script	= "";
		$code	= "";
		if ($offset == 0)
			$icon = $this->_getImage( 'root' );

		if ( $tree->hasChildren() )
		{
			if( $offset != 0 )
				$icon = $this->_getImage( 'node' );
			$script .= $rackobj.".recall('".$oid."');\n";
			$code .= "
$tabs<div class='".$classes['div_node']."'>
$tabs  <span class='".$classes['span_icon']."' onClick=\"".$rackobj.".change ('".($oid)."');\" style='cursor: pointer'>".$icon."</span>
$tabs  <span class='".$classes['span_node']."'>".$link."</span>
".$this->_getAttributes( $tree )."
".$this->_getContent( $tree )."
$tabs  <div class='".$classes['div_shut']."' id='".$oid."' name='node'>";
			foreach( $tree->getChildren() as $child )
			{
				$result	= $this->buildFromTree( $child, $offset + 2);
				$code	.= $result['code'];
				$script	.= $result['script'];
			}
			$code .= "
$tabs  </div>
$tabs</div>";
		}
		else
		{
			$code .= "
$tabs<div class='".$classes['div_node']."'>
$tabs  <span class='".$classes['span_icon']."'>".$this->_getImage( 'leaf' )."</span>
$tabs  <span class='".$classes['span_leaf']."'>".$link."</span>
".$this->_getAttributes( $tree )."
".$this->_getContent( $tree )."
$tabs</div>";
		}
		return array ("code" => $code, "script" => $script);
	}


	/**
	 *	Returns attribute box of Node.
	 *	@access		private
	 *	@param		XML_DOM_Node
	 *	@return		string
	 */
	function _getAttributes( $node )
	{
		$code = "";
		if ( $node->hasAttributes() && $this->getOption( 'show_attribs' ) )
		{
			$code .= "<div class='attrbox'>";
			foreach( $node->getAttributes() as $key => $value )
				$code .= "<div class='attr'>".$key.": $value</div>";
			$code .= "</div>";
		}
		return $code;
	}

	/**
	 *	Returns content box of Node.
	 *	@access		private
	 *	@param		XML_DOM_Node
	 *	@return		string
	 */
	function _getContent( $node )
	{
		$code = "";
		if ( $node->hasContent() && $this->getOption( 'show_content' ) )
		{
			$code .= "<div class='contbox'>";
			$code .= "<div class='content'>".htmlentities( $node->getContent() )."</div>";
			$code .= "</div>";
		}
		return $code;
	}
}
?>