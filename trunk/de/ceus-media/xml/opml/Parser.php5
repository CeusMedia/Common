<?php
/**
 *	Parser for OPML Files.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		xml.opml
 *	@uses			ADT_OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		0.6
 */
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Parser for OPML Files.
 *	@package		xml.opml
 *	@uses			ADT_OptionObject
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		0.6
 */
class XML_OPML_Parser
{
	/**	@var	ADT_OptionObject	$headers			Object containing Headers of OPML Document */
	var $headers;
	/**	@var	array				optionKeys			Array of supported Headers */
	var $optionKeys	= array(
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
	/**	@var	array			outlines			Array of Outlines */
	var $outlines = array();
	/**	@var	XML_DOM_Node	tree			Loaded XML Tree from OPML Document */
	var $tree;
		
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->headers	= new ADT_OptionObject();
		$this->outlines	= array();
		$this->parser	= new XML_DOM_Parser();
		$this->parsed	= false;
	}

	/**
	 *	Returns timestamp from GNU Date.
	 *	@access		protected
	 *	@param		string
	 *	@return		string
	 */
	protected function getDate( $date )
	{
		$timestamp	= strtotime( $date );
		if( $timestamp > 0 )
			return $timestamp;
		return false;
	}

	/**
	 *	Return the value of an options of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	public function getOption( $key)
	{
		if( $this->_parsed )
		{
			if( NULL !== $this->headers->getOption( $key ) )
				return $this->headers->getOption( $key );
			return false;
		}
		else
			trigger_error( "OPML_DOM_Parser[getOption]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}

	/**
	 *	Returns an array of all Headers of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions()
	{
		if( $this->_parsed )
			return $this->headers->getOptions();
		else
			trigger_error( "OPML_DOM_Parser[getOptions]: OPML Document has not been parsed yet.", E_USER_WARNING );
	}

	/**
	 *	Returns an array of all Outlines of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	public function getOutlines()
	{
		return $this->outlines;
	}
	
	public function getOutlineTree()
	{
		$areas	= $this->tree->getChildren();
		foreach( $areas as $area )
			if( $area->getNodeName() == "body" )
				return $area;
	}
	
	/**
	 *	Reads  XML String of OPML Document and builds tree of XML_DOM_Nodes.
	 *	@access		public
	 *	@param		string		$xml		OPML String parse
	 *	@return		void
	 */
	public function parse( $xml )
	{
		$this->tree		= $this->parser->parse( $xml );
		$this->outlines	= array();
		$this->headers->clearOptions();

		foreach( $this->parser->getOptions() as $key => $value )
			$this->headers->setOption( "xml_".$key, $value );
		if( $version = $this->tree->getAttribute( "version" ) )
			$this->headers->setOption( "opml_version", $version );

		foreach( $this->tree->getChildren() as $area )
		{
			$areaName	= $area->getNodeName();
			switch( $areaName )
			{
				case "head":
					$children = $area->getChildren();
					foreach( $children as $nr => $child )
					{
						$childName	= $child->getNodeName();
						$content	= $child->getContent();
						switch( $childName )
						{
							case 'dateCreated':
								$content	= $this->getDate( $content );
								break;
							case 'dateModified':
								$content	= $this->getDate( $content );
								break;
							default:
								break;
						}
						$this->headers->setOption( "opml_".$childName, $content );
					}
					break;
				case "body":
					$this->parseOutlines( $area, $this->outlines );
					break;
				default:
					break;
			}
		}
	}
	
	/**
	 *	Parses Outlines recursive.
	 *	@access		protected
	 *	@return		void
	 */
	protected function parseOutlines( $node, &$array )
	{
		$outlines = $node->getChildren();
		foreach( $outlines as $outline )
		{
			$data	= array();
			foreach( $outline->getAttributes() as $key => $value )
				$data[$key]	= $value;
			if( $outline->hasChildren() )
				$this->parseOutlines( $outline, $data['outlines'] );
			else
				$data['outlines']	= array();
			$array[]	= $data;
		}
	}
}
?>