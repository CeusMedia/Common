<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for OPML Files.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\OPML;

use CeusMedia\Common\XML\DOM\Builder as DomBuilder;
use CeusMedia\Common\XML\DOM\Node;
use DOMException;
use InvalidArgumentException;


/**
 *	Builder for OPML Files.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Builder extends Node
{
	/**	@var	Node	$tree			Outline Document Tree */
	protected $tree;

	protected $head;

	protected $body;

	/**	@var	array			$headers		Array of supported Headers */
	protected $headers	= [
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

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$version		Version of OPML Document
	 *	@return		void
	 */
	public function __construct( string $version = "1.0" )
	{
		parent::__construct( 'opml' );
		$this->head = new Node( "head" );
		$this->body = new Node( "body" );
		$this->tree	= new Node( "opml" );
		$this->setAttribute( "version", $version );
		$this->addChild( $this->head );
		$this->addChild( $this->body );
	}

	/**
	 *	Adds Outline to OPML Document.
	 *	@access		public
	 *	@param		Outline		$outline		Outline Node to add
	 *	@return		void
	 */
	public function addOutline( Outline $outline )
	{
		$this->body->addChild( $outline );
	}

	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		$key			Key of Header
	 *	@param		string		$value			Value of Header
	 *	@return		void
	 */
	public function setHeader( string $key, string $value )
	{
		if( !in_array( $key, $this->headers, TRUE ) )
			throw new InvalidArgumentException( "Unsupported Header '".$key."'" );
		$node		= new Node( $key, $value );
		$this->head->addChild( $node );
	}

	/**
	 *	Sets Header of OPML Document.
	 *	@access		public
	 *	@param		string		$encoding		Encoding of OPML Document
	 *	@return		string
	 *	@throws		DOMException
	 */
	public function build( string $encoding = "utf-8" ): string
	{
		$builder	= new DomBuilder;
		return $builder->build( $this, $encoding );
	}
}
