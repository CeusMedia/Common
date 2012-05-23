<?php
/**
 *	Elements for XML UI Output Generation.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		UI.XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Elements for XML UI Output Generation.
 *	@category		cmClasses
 *	@package		UI.XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			finish Implementation
 *	@todo			Code Documentation
 */
class UI_XML_Elements
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