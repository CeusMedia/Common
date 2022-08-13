<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	XML element based on SimpleXMLElement with improved attribute and content handling.
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
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;

/**
 *	XML element based on SimpleXMLElement with improved attribute Handling.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			namespace handling: implement detection "Prefix or URI?", see http://www.w3.org/TR/REC-xml/#NT-Name
 */
class Element extends SimpleXMLElement
{
	protected $attributes	= array();

	/**
	 *	Adds an attributes.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of attribute
	 *	@param		string			$value				Value of attribute
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@param		string|NULL		$nsURI				Namespace URI of attribute
	 *	@return		void
	 *	@throws		RuntimeException		if attribute is already set
	 *	@throws		RuntimeException		if namespace prefix is neither registered nor given
	 */
	public function addAttribute( string $qualifiedName, string $value, $namespace = NULL, ?string $nsURI = NULL ): void
	{
		if( $namespace ) {
			$namespaces	= $this->getDocNamespaces();
			$key		= $namespace.':'.$qualifiedName;
			if( $this->hasAttribute( $qualifiedName, $namespace ) )
				throw new RuntimeException( 'Attribute "'.$qualifiedName.'" is already set' );
			if( array_key_exists( $namespace, $namespaces ) ){
				parent::addAttribute( $key, $value, $namespaces[$namespace] );
				return;
			}
			if( $nsURI ){
				parent::addAttribute( $key, $value, $nsURI );
				return;
			}
			throw new RuntimeException( 'Namespace prefix is not registered and namespace URI is missing' );
		}
		if( $this->hasAttribute( $qualifiedName ) )
			throw new RuntimeException( 'Attribute "'.$qualifiedName.'" is already set' );
		parent::addAttribute( $qualifiedName, $value );
	}

	/**
	 *	Add CDATA text in a node
	 *	@param		string		$text		The CDATA value to add
	 */
	private function addCData( string $text ): void
	{
		$node		= dom_import_simplexml( $this );
		$document	= $node->ownerDocument;
		$node->appendChild( $document->createCDATASection( $text ) );
	}

	/**
	 *	Adds a child element. Sets node content as CDATA section if necessary.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of child element
	 *	@param		string|NULL		$value		Value of child element
	 *	@param		string|NULL 	$namespace	Namespace prefix of child element
	 *	@param		string|NULL		$nsURI		Namespace URI of child element
	 *	@return		self
	 *	@throws		RuntimeException		if namespace prefix is neither registered nor given
	 */
	public function addChild( string $qualifiedName, ?string $value = NULL, ?string $namespace = NULL, ?string $nsURI = NULL ): self
	{
		if( $namespace ) {
			$namespaces	= $this->getDocNamespaces();
			$key		= $namespace.':'.$qualifiedName;
			if( array_key_exists( $namespace, $namespaces ) )
				$child	= parent::addChild( $qualifiedName, NULL, $namespaces[$namespace] );
			else if( $nsURI )
				$child	= parent::addChild( $key, NULL, $nsURI );
			else
				throw new RuntimeException( 'Namespace prefix is not registered and namespace URI is missing' );
		}
		else
			$child	= parent::addChild( $qualifiedName );
		if( $value !== NULL )
			$child->setValue( $value );
		return $child;
	}

	/**
	 *	Create a child element with CDATA value
	 *	@param		string			$qualifiedName		The name of the child element to add.
	 *	@param		string			$text				The CDATA value of the child element.
	 *	@param		string|NULL		$namespace			Namespace prefix of child element
	 *	@param		string|NULL		$nsURI				Namespace URI of child element
	 *	@return		Element
	 *	@reprecated	use addChild instead
	 */
	public function addChildCData( string $qualifiedName, string $text, ?string $namespace = NULL, ?string $nsURI = NULL ): self
	{
		$child	= $this->addChild( $qualifiedName, NULL, $namespace, $nsURI );
		$child->addCData( $text );
		return $child;
	}

	/**
	 *	Writes current XML Element as XML File.
	 *	@access		public
	 *	@param		string		$fileName		File name for XML file
	 *	@return		int
	 */
	public function asFile( string $fileName ): int
	{
		$xml	= $this->asXML();
		return FileWriter::save( $fileName, $xml );
	}

	/**
	 *	Returns count of attributes.
	 *	@access		public
	 *	@param		string|NULL		$namespace		Namespace prefix of attributes
	 *	@return		int
	 */
	public function countAttributes( ?string $namespace = NULL ): int
	{
		return count( $this->getAttributeNames( $namespace ) );
	}

	/**
	 *	Returns count of children.
	 *	@access		public
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@return		int
	 */
	public function countChildren( ?string $namespace = NULL ): int
	{
		if( $namespace === NULL )
			return $this->count();
		$i = 0;
		foreach($this->children( $namespace, TRUE ) as $ignored)
			$i++;
		return $i;
	}

	/**
	 *	Returns the value of an attribute by its name.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of attribute
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@return		string
	 *	@throws		RuntimeException		if attribute is not set
	 */
	public function getAttribute( string $qualifiedName, ?string $namespace = NULL ): string
	{
		$data	= $namespace ? $this->attributes( $namespace, TRUE ) : $this->attributes();
		if( !isset( $data[$qualifiedName] ) )
			throw new RuntimeException( 'Attribute "'.( $namespace ? $namespace.':'.$qualifiedName : $qualifiedName ).'" is not set' );
		return (string) $data[$qualifiedName];
	}

	/**
	 *	Returns List of attribute names.
	 *	@access		public
	 *	@param		string|NULL		$namespace	Namespace prefix of attribute
	 *	@return		array
	 */
	public function getAttributeNames( ?string $namespace = NULL ): array
	{
		$list	= [];
		$data	= $namespace ? $this->attributes( $namespace, TRUE ) : $this->attributes();
		foreach( $data as $name => $value )
			$list[] = $name;
		return $list;
	}

	/**
	 *	Returns map of attributes.
	 *	@access		public
	 *	@param		string|NULL		$namespace	Namespace prefix of attributes
	 *	@return		array
	 */
	public function getAttributes( ?string $namespace = NULL ): array
	{
		$list	= [];
		foreach( $this->attributes( $namespace, TRUE ) as $name => $value )
			$list[$name]	= (string) $value;
		return $list;
	}

	/**
	 *	Returns Text Value.
	 *	@access		public
	 *	@return		string
	 */
	public function getValue(): string
	{
		return (string) $this;
	}

	/**
	 *	Indicates whether an attribute is existing by its name.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of attribute
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@return		bool
	 */
	public function hasAttribute( string $qualifiedName, ?string $namespace = NULL ): bool
	{
		$names	= $this->getAttributeNames( $namespace );
		return in_array( $qualifiedName, $names, TRUE );
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of attribute
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@return		boolean
	 */
	public function removeAttribute( string $qualifiedName, ?string $namespace = NULL ): bool
	{
		$data	= $namespace ? $this->attributes( $namespace, TRUE ) : $this->attributes();
		foreach( $data as $key => $attributeNode ) {
			if( $key == $qualifiedName ) {
				unset( $data[$key] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function remove(): void
	{
		$dom	= dom_import_simplexml( $this );
		$dom->parentNode->removeChild( $dom );
	}

	public function removeChild( $qualifiedName, ?int $number = NULL )
	{
		$nr		= 0;
		foreach( $this->children() as $nodeName => $child ){
			if( $nodeName == $qualifiedName ){
				if( $number === NULL || $nr === $number ){
					$dom	= dom_import_simplexml( $child );
					$dom->parentNode->removeChild( $dom );
				}
				$nr++;
			}
		}
	}

	/**
	 *	Sets an attribute from by its name.
	 *	Adds attribute if not existing.
	 *	Removes attribute if value is NULL.
	 *	@access		public
	 *	@param		string			$qualifiedName		Name of attribute
	 *	@param		string|NULL		$value				Value of attribute, NULL means removal
	 *	@param		string|NULL		$namespace			Namespace prefix of attribute
	 *	@param		string|NULL		$nsURI				Namespace URI of attribute
	 *	@return		void
	 */
	public function setAttribute( string $qualifiedName, ?string $value, ?string $namespace = NULL, ?string $nsURI = NULL )
	{
		if( $value !== NULL ){
			if( !$this->hasAttribute( $qualifiedName, $namespace ) ){
				$this->addAttribute( $qualifiedName, $value, $namespace, $nsURI );
				return;
			}
			$this->removeAttribute( $qualifiedName, $namespace );
			$this->addAttribute( $qualifiedName, $value, $namespace, $nsURI );
		}
		else if( $this->hasAttribute( $qualifiedName, $namespace ) ){
			$this->removeAttribute( $qualifiedName, $namespace );
		}
	}

	/**
	 *	Sets text Value.
	 *	@access		public
	 *	@param		string|NULL		$value			The name of the child element to add.
	 *	@param		boolean			$cdata			Flag: value is CDATA
	 *	@return		self
	 */
	public function setValue( ?string $value, bool $cdata = FALSE ): self
	{
		if( !is_string( $value ) && $value !== NULL )
			throw new InvalidArgumentException( 'Value must be a string or NULL' );

		$value	= preg_replace( "/(.*)<!\[CDATA\[(.*)\]\]>(.*)/iU", "\\1\\2\\3", $value );
		//  string is known or detected to be CDATA
		if( $cdata || preg_match( '/[&<]/', $value ) ) {
			//  import node in DOM
			$dom	= dom_import_simplexml( $this );
			//  create a new CDATA section
			$cdata	= $dom->ownerDocument->createCDATASection( $value );
			//  clear node content
			$dom->nodeValue	= "";
			//  add CDATA section
			$dom->appendChild( $cdata );
		}
		//  normal node content
		else
		{
			//  set node content
			dom_import_simplexml( $this )->nodeValue	= $value;
		}
		return $this;
	}
}
