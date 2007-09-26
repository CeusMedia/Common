<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import ("de.ceus-media.xml.dom.XML_DOM_Builder");
/**
 *	[DEV] Elements for XML UI Output Generation.
 *	@package	ui
 *	@subpackage	xml
 *	@todo		finish Implementation
 *	@todo		Code Documentation
 */
/**
 *	[DEV] Elements for XML UI Output Generation.
 *	@package	ui
 *	@subpackage	xml
 *	@todo		finish Implementation
 *	@todo		Code Documentation
 */
class XML_Elements
{
	public function __construct( &$root )
	{
		$this->_root	=& $root;
	}
	
	function buildXML( $xslt_file )
	{
	


		$builder		= new XML_DOM_Builder();
		$xml		= $builder->build( $this->_root );
		
		$lines	= explode( "\n", $xml );
		
		$link		='<?xml-stylesheet type="text/xsl" href="'.$xslt_file.'"?>';
		$first	= array_shift( $lines );
		array_unshift( $lines, $link );
		array_unshift( $lines, $first );
		$xml	= implode( "\n", $lines );
		return $xml;
	}
	
	function & buildLink( $tag, $reference, $title, $target = false, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		$node->addChild( new XML_DOM_Node( "reference", $reference ) );
		$node->addChild( new XML_DOM_Node( "title", $title ) );
		if( $target )
			$node->addChild( new XML_DOM_Node( "target", $target ) );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	function & buildList( $tag, $items, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		foreach( $items as $item )
		{
			$node->addChild( $item );
			unset( $item );
		}
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	function buildParent( $tag, $child, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		$node->addChild( $child );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	function & buildText( $tag, $text, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag, $text );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	function & buildNode( $tag, $text = false , $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		if( $text )
			$node->setContent( $text );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	function addNode( $node, $debug = false )
	{
		if( $debug )
		{
			remark( "Adding Node:" );
			print_m( $node );
		}
		$this->_root->addChild( $node );
	}
	
	function addNodeTo( &$parent, $node )
	{
		$parent->addChild( $node );
	}
	
/*	function addNode( &$parent, $tag, $text = false , $attributes = array() )
	{
		$node	= $this->buildNode( $tag, $text, $attributes );
		$parent->addChild( $node );
	}
*/
}
?>