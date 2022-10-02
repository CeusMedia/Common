<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Simplified XML Node DOM Implementation.
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
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use InvalidArgumentException;

/**
 *	Simplified XML Node DOM Implementation.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Node
{
	/**	@var	string			$nodeName		Name of XML Node */
	protected $nodeName;
	/**	@var	array			$attributes		Map of XML Node Attributes */
	protected $attributes		= [];
	/**	@var	array			$children		List of Child Nodes  */
	protected $children			= [];
	/**	@var	string|NULL		$content		Content of XML Node */
	protected $content			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$nodeName		Tag Name of XML Node
	 *	@param		string|NULL		$content		Content of XML Node
	 *	@param		array			$attributes		Array of Node Attributes
	 *	@return		void
	 */
	public function __construct( string $nodeName, ?string $content = NULL, array $attributes = [] )
	{
		$this->setNodeName( $nodeName );
		if( $content !== NULL )
			$this->setContent( $content );
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Attributes must be given as Array.' );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$this->setAttribute( $key, $value );
	}

	public function __get( string $key )
	{
		switch( $key ){
			case 'name':
			case 'nodeName':
				return $this->nodeName;
			case 'content':
			case 'nodeContent':
			case 'nodeValue':
				return $this->content;
			case 'attr':
			case 'attributes':
				return $this->attributes;
			default:
				throw new InvalidArgumentException( 'Property "'.$key.'" not defined.' );
		}
	}

	/**
	 *	Adds a Child Node, returns the Node just added.
	 *	@access		public
	 *	@param		Node		$xmlNode	XML Node to add
	 *	@return		Node
	 */
	public function addChild( Node $xmlNode ): Node
	{
		$this->children[] = $xmlNode;
		return $xmlNode;
	}

	/**
	 *	Returns an Attribute if it is set.
	 *	@access		public
	 *	@param		string		$key			Key of Attribute
	 *	@return		string
	 */
	public function getAttribute( string $key ): ?string
	{
		if( $this->hasAttribute( $key ) )
			return $this->attributes[$key];
		return NULL;
	}

	/**
	 *	Returns Array of all Attributes.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}

	/**
	 *	Returns a Child Nodes by its name.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node
	 *	@return		Node
	 */
	public function getChild( string $nodeName ): Node
	{
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				return $this->children[$i];
		throw new InvalidArgumentException( 'Child Node with Node Name "'.$nodeName.'" is not existing.' );
	}

	/**
	 *	Returns a Child Node by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of Child, starting with 0
	 *	@return		Node
	 *	@todo		write Unit Test
	 */
	public function getChildByIndex( int $index ): Node
	{
		if( $index > count( $this->children ) )
			throw new InvalidArgumentException( 'Child Node with Index "'.$index.'" is not existing.' );
		return $this->children[$index];
	}

	/**
	 *	Returns all Child Nodes.
	 *	@access		public
	 *	@param		string|NULL		$nodeName		Name of Child Node
	 *	@return		array
	 */
	public function getChildren( ?string $nodeName = NULL ): array
	{
		if( !$nodeName )
			return $this->children;
		$list	= [];
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				$list[]	= $this->children[$i];
		return $list;
	}

	/**
	 *	Returns Content if it is set.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 *	Returns nodeName.
	 *	@access		public
	 *	@return		string
	 */
	public function getNodeName(): string
	{
		return $this->nodeName;
	}

	/**
	 *	Indicates whether XML Node has attributes.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasAttributes(): bool
	{
		return count( $this->attributes ) !== 0;
	}

	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@param		string		$key			Name of attribute to check
	 *	@return		bool
	 */
	public function hasAttribute( string $key ): bool
	{
		return array_key_exists( $key, $this->attributes );
	}

	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node
	 *	@return		bool
	 */
	public function hasChild( string $nodeName ): bool
	{
		return count( $this->getChildren( $nodeName ) ) !== 0;
	}

	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren(): bool
	{
		return count( $this->children ) !== 0;
	}

	/**
	 *	Indicated whether XML Node has content.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasContent(): bool
	{
		return $this->content !== NULL && $this->content !== '';
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string		$key			Key of attribute to be removed
	 *	@return		self
	 */
	public function removeAttribute( string $key ): self
	{
		if( $this->hasAttribute( $key ) )
			unset( $this->attributes[$key] );
		return $this;
	}

	/**
	 *	Remove first found Child Nodes with given name.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node to be removed
	 *	@return		self
	 */
	public function removeChild( string $nodeName ): self
	{
		$found		= false;
		$children	= [];
		foreach( $this->children as $child ){
			if( !$found && $child->getNodeName() === $nodeName ){
				$found	= TRUE;
				continue;
			}
			$children[] = $child;
		}
		if( $children !== $this->children )
			$this->children = $children;
		return $this;
	}

	/**
	 *	Removes content of XML Node.
	 *	@access		public
	 *	@return		self
	 */
	public function removeContent(): self
	{
		if( $this->hasContent() )
			$this->setContent( '' );
		return $this;
	}

	/**
	 *	Sets an attribute.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@param		string		$value			Value of attribute
	 *	@return		self
	 */
	public function setAttribute( string $key, string $value ): self
	{
		if( $this->getAttribute( $key ) !== $value )
			$this->attributes[$key] = $value;
		return $this;
	}

	/**
	 *	Sets content of XML Node.
	 *	@access		public
	 *	@param		string		$content		Content of XML Node
	 *	@return		self
	 */
	public function setContent( string $content ): self
	{
		if( $this->content !== $content )
			$this->content = $content;
		return $this;
	}

	/**
	 *	Sets Name of XML Leaf Node.
	 *	@access		public
	 *	@param		string		$name			Name of XML Node
	 *	@return		self
	 */
	public function setNodeName( string $name ): self
	{
		if( $this->nodeName !== $name )
			$this->nodeName = $name;
		return $this;
	}
}
