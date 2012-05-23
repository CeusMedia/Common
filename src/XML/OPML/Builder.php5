<?php
/**
 *	Builder for OPML Files.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		XML.OMPL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		$Id$
 */
/**
 *	Builder for OPML Files.
 *	@category		cmClasses
 *	@package		XML.OMPL
 *	@extends		XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		$Id$
 */
class XML_OPML_Builder extends XML_DOM_Node
{
	/**	@var	XML_DOM_Node	$tree			Outline Document Tree */
	protected $tree;
	/**	@var	array			$headers		Array of supported Headers */
	protected $headers	= array(
		"title",
		"dateCreated",
		"dateModified",
		"ownerName",
		"ownerEmail",
		"expansionState",
		"vertScrollState",
		"windowTop",
		"windowLeft",
		"windowBottom",
		"windowRight",
		);
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$version		Version of OPML Document
	 *	@return		void
	 */
	public function __construct( $version = "1.0" )
	{
		$this->tree	= new XML_DOM_Node( "opml" );
		$this->tree->setAttribute( "version", $version );
		$this->tree->addChild( new XML_DOM_Node( "head" ) );
		$this->tree->addChild( new XML_DOM_Node( "body" ) );
	}
	
	/**
	 *	Adds Outline to OPML Document.
	 *	@access		public
	 *	@param		OPML_DOM_Outline	outline		Outline Node to add
	 *	@return		void
	 */
	public function addOutline( $outline )
	{
		$children	=& $this->getChildren();
		$body		=& $children[1];
		$body->addChild( $outline );
	}

	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		$key			Key of Header
	 *	@param		string		$value			Value of Header
	 *	@return		void
	 */
	public function setHeader( $key, $value )
	{
		if( !in_array( $key, $this->headers ) )
			throw new InvalidArgumentException( "Unsupported Header '".$key."'" );
		$children	=& $this->tree->getChildren();
		$head		=& $children[0];
		$node		= new XML_DOM_Node( $key, $value );
		$head->addChild( $node );
	}
	
	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		$encoding		Encoding of OPML Document
	 *	@return		string
	 */
	public function build( $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder;
		$xml		= $builder->build( $this->tree, $encoding );
		return $xml;
	}
}
?>