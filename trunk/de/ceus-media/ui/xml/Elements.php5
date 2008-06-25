<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Elements for XML UI Output Generation.
 *	@package		ui.xml
 *	@todo			finish Implementation
 *	@todo			Code Documentation
 */
/**
 *	Elements for XML UI Output Generation.
 *	@package		ui.xml
 *	@todo			finish Implementation
 *	@todo			Code Documentation
 */
class XML_Elements
{
	public function __construct( &$root )
	{
		$this->root	=& $root;
	}
	
	public function addNode( $node, $debug = false )
	{
		if( $debug )
		{
			remark( "Adding Node:" );
			print_m( $node );
		}
		$this->root->addChild( $node );
	}
	
	public function addNodeTo( &$parent, $node )
	{
		$parent->addChild( $node );
	}
	
	public function buildLink( $tag, $reference, $title, $target = false, $attributes = array() )
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
	
	public function buildList( $tag, $items, $attributes = array() )
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
	
	public function buildNode( $tag, $text = false , $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		if( $text )
			$node->setContent( $text );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	public function buildParent( $tag, $child, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag );
		$node->addChild( $child );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	public function buildText( $tag, $text, $attributes = array() )
	{
		$node	= new XML_DOM_Node( $tag, $text );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$node->setAttribute( $key, $value );
		return $node;
	}
	
	public function buildXML( $xslt_file )
	{
		$builder		= new XML_DOM_Builder();
		$xml		= $builder->build( $this->root );
		
		$lines	= explode( "\n", $xml );
		
		$link		='<?xml-stylesheet type="text/xsl" href="'.$xslt_file.'"?>';
		$first	= array_shift( $lines );
		array_unshift( $lines, $link );
		array_unshift( $lines, $first );
		$xml	= implode( "\n", $lines );
		return $xml;
	}
}
?>