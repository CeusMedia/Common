<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for OPML Files.
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
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\OPML;

use CeusMedia\Common\ADT\OptionObject;
use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\Common\XML\DOM\Parser as DomParser;
use Exception;
use RuntimeException;

/**
 *	Parser for OPML Files.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**	@var	OptionObject		$headers			Object containing Headers of OPML Document */
	protected $headers;

	/**	@var	array				$optionKeys			Array of supported Headers */
	protected $optionKeys	= [
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
	];

	/**	@var	array				$outlines			Array of Outlines */
	protected $outlines = [];

	/**	@var	Node				$tree				Loaded XML Tree from OPML Document */
	protected $tree;

	/**	@var	DomParser			$parser				Instance of DOM parser */
	protected $parser;

	/**	@var	bool				$parsed				Flag: OPML has been parsed */
	protected $parsed;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->headers	= new OptionObject();
		$this->outlines	= [];
		$this->parser	= new DomParser();
		$this->parsed	= false;
	}

	/**
	 *	Returns timestamp from GNU Date.
	 *	@access		protected
	 *	@param		string		$date
	 *	@return		int|NULL
	 */
	protected function getDate( string $date ): ?int
	{
		$timestamp	= strtotime( $date );
		if( FALSE !== $timestamp && $timestamp > 0 )
			return $timestamp;
		return NULL;
	}

	/**
	 *	Return the value of an options of OPML Document.
	 *	@access		public
	 *	@param		string		$key
	 *	@return		string|NULL
	 */
	public function getOption( string $key ): ?string
	{
		if( !$this->parsed )
			throw new RuntimeException( "XML_OPML_Parser[getOption]: OPML Document has not been parsed yet." );
		if( $this->headers->hasOption( $key ) )
			return $this->headers->getOption( $key );
		return NULL;
	}

	/**
	 *	Returns an array of all Headers of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions(): array
	{
		if( !$this->parsed )
			throw new RuntimeException( "XML_OPML_Parser[getOptions]: OPML Document has not been parsed yet." );
		return $this->headers->getOptions();
	}

	/**
	 *	Returns an array of all Outlines of OPML Document.
	 *	@access		public
	 *	@return		array
	 */
	public function getOutlines(): array
	{
		return $this->outlines;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		Node|null
	 *	@noinspection	PhpUnused
	 */
	public function getOutlineTree(): ?Node
	{
		if( !$this->parsed )
			throw new RuntimeException( "XML_OPML_Parser[getOutlineTree]: OPML Document has not been parsed yet." );
		$areas	= $this->tree->getChildren();
		foreach( $areas as $area )
			if( $area->getNodeName() == "body" )
				return $area;
		return NULL;
	}

	/**
	 *	Reads  XML String of OPML Document and builds tree of XML_DOM_Nodes.
	 *	@access		public
	 *	@param		string		$xml		OPML String parse
	 *	@return		void
	 *	@throws		Exception
	 */
	public function parse( string $xml ): void
	{
		$this->tree		= $this->parser->parse( $xml );
		$this->outlines	= [];
		$this->headers->clearOptions();

		foreach( $this->parser->getOptions() as $key => $value )
			$this->headers->setOption( "xml_".$key, $value );
		if( $version = $this->tree->getAttribute( "version" ) )
			$this->headers->setOption( "opml_version", $version );

		foreach( $this->tree->getChildren() as $area ){
			$areaName	= $area->getNodeName();
			switch( $areaName ){
				case "head":
					$children = $area->getChildren();
					foreach( $children as $child ) {
						$childName	= $child->getNodeName();
						$content	= $child->getContent();
						switch( $childName ){
							case 'dateCreated':
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
		$this->parsed	= TRUE;
	}

	/**
	 *	Parses Outlines recursive.
	 *	@access		protected
	 *	@param		Node		$node
	 *	@param		array		$array
	 *	@return		void
	 */
	protected function parseOutlines( Node $node, array &$array ): void
	{
		$outlines = $node->getChildren();
		foreach( $outlines as $outline ) {
			$data	= [];
			foreach( $outline->getAttributes() as $key => $value )
				$data[$key]	= $value;
			if( $outline->hasChildren() )
				$this->parseOutlines( $outline, $data['outlines'] );
			else
				$data['outlines']	= [];
			$array[]	= $data;
		}
	}
}
