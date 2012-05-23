<?php
/**
 *	XML element based on SimpleXMLElement with improved attribute and content handling.
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
 *	@package		XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		$Id$
 */
/**
 *	XML element based on SimpleXMLElement with improved attribute Handling.
 *	@category		cmClasses
 *	@package		XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		$Id$
 *	@todo			namespace handling: implement detection "Prefix or URI?", see http://www.w3.org/TR/REC-xml/#NT-Name
 */
class XML_Element extends SimpleXMLElement
{
	protected $attributes	= array();
	
	/**
	 *	Adds an attributes.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$value		Value of attribute
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@param		string		$nsURI		Namespace URI of attribute
	 *	@return		void
	 *	@throws		RuntimeException		if attribute is already set
	 *	@throws		RuntimeException		if namespace prefix is neither registered nor given
	 */
	public function addAttribute( $name, $value, $nsPrefix = NULL, $nsURI = NULL )
	{
		$key	= $nsPrefix ? $nsPrefix.':'.$name : $name;
		if( $nsPrefix )
		{
			$namespaces	= $this->getDocNamespaces();
			$key		= $nsPrefix ? $nsPrefix.':'.$name : $name;
			if( $this->hasAttribute( $name, $nsPrefix ) )
				throw new RuntimeException( 'Attribute "'.$key.'" is already set' );
			if( array_key_exists( $nsPrefix, $namespaces ) )
				return parent::addAttribute( $key, $value, $namespaces[$nsPrefix] );
			if( $nsURI )
				return parent::addAttribute( $key, $value, $nsURI );
			throw new RuntimeException( 'Namespace prefix is not registered and namespace URI is missing' );
		}
		if( $this->hasAttribute( $name ) )
			throw new RuntimeException( 'Attribute "'.$name.'" is already set' );
		parent::addAttribute( $name, $value );
	}

	/** 
	 *	Add CDATA text in a node 
	 *	@param		string		$cdata_text		The CDATA value to add 
	 */ 
	private function addCData( $text ) 
	{ 
		$node		= dom_import_simplexml( $this ); 
		$document	= $node->ownerDocument; 
		$node->appendChild( $document->createCDATASection( $text ) ); 
	} 

	/**
	 *	Adds a child element. Sets node content as CDATA section if necessary.
	 *	@access		public
	 *	@param		string		$name		Name of child element
	 *	@param		string		$value		Value of child element
	 *	@param		string		$nsPrefix	Namespace prefix of child element
	 *	@param		string		$nsURI		Namespace URI of child element
	 *	@return		XML_Element
	 *	@throws		RuntimeException		if namespace prefix is neither registered nor given
	 */
	public function addChild( $name, $value = NULL, $nsPrefix = NULL, $nsURI = NULL )
	{
		$child		= NULL;
		if( $nsPrefix )
		{
			$namespaces	= $this->getDocNamespaces();
			$key		= $nsPrefix ? $nsPrefix.':'.$name : $name;
			if( array_key_exists( $nsPrefix, $namespaces ) )
				$child	= parent::addChild( $name, NULL, $namespaces[$nsPrefix] );
			else if( $nsURI )
				$child	= parent::addChild( $key, NULL, $nsURI );
			else 
				throw new RuntimeException( 'Namespace prefix is not registered and namespace URI is missing' );
		}
		else
			$child	= parent::addChild( $name );
		if( $value !== NULL )
			$child->setValue( $value );
		return $child;
	}

	/** 
	 *	Create a child element with CDATA value 
	 *	@param		string		$name			The name of the child element to add. 
	 *	@param		string		$cdata_text		The CDATA value of the child element. 
	 *	@param		string		$nsPrefix		Namespace prefix of child element
	 *	@param		string		$nsURI			Namespace URI of child element
	 *	@return		XML_Element
	 *	@reprecated	use addChild instead
	 */ 
	public function addChildCData( $name, $text, $nsPrefix = NULL, $nsURI = NULL ) 
	{ 
		$child	= $this->addChild( $name, NULL, $nsPrefix, $nsURI ); 
		$child->addCData( $text ); 
		return $child;
	}

	/**
	 *	Writes current XML Element as XML File.
	 *	@access		public
	 *	@param		string		$fileName		File name for XML file
	 *	@return		int
	 */
	public function asFile( $fileName )
	{
		$xml	= $this->asXml();
		return File_Writer::save( $fileName, $xml );
	}

	/**
	 *	Returns count of attributes.
	 *	@access		public
	 *	@param		string		$nsPrefix		Namespace prefix of attributes
	 *	@return		int
	 */
	public function countAttributes( $nsPrefix = NULL )
	{
		return count( $this->getAttributeNames( $nsPrefix ) );
	}

	/**
	 *	Returns count of children.
	 *	@access		public
	 *	@return		int
	 */
	public function countChildren( $nsPrefix = NULL )
	{
		$i = 0;
		foreach( $this->children( $nsPrefix, TRUE ) as $node )
			$i++;
		return $i;
	}

	/**
	 *	Returns the value of an attribute by it's name.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@return		bool
	 *	@throws		RuntimeException		if attribute is not set
	 */
	public function getAttribute( $name, $nsPrefix = NULL )
	{
		$data	= $nsPrefix ? $this->attributes( $nsPrefix, TRUE ) : $this->attributes();
		if( !isset( $data[$name] ) )
			throw new RuntimeException( 'Attribute "'.( $nsPrefix ? $nsPrefix.':'.$name : $name ).'" is not set' );
		return (string) $data[$name];
	}
	
	/**
	 *	Returns List of attribute names.
	 *	@access		public
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@return		array
	 */
	public function getAttributeNames( $nsPrefix = NULL )
	{
		$list	= array();
		$data	= $nsPrefix ? $this->attributes( $nsPrefix, TRUE ) : $this->attributes();
		foreach( $data as $name => $value )
			$list[] = $name;
		return $list;
	}

	/**
	 *	Returns map of attributes.
	 *	@access		public
	 *	@param		string		$nsPrefix	Namespace prefix of attributes
	 *	@return		array
	 */
	public function getAttributes( $nsPrefix = NULL )
	{
		$list	= array();
		foreach( $this->attributes( $nsPrefix, TRUE ) as $name => $value )
			$list[$name]	= (string) $value;
		return $list;
	}
	
	/**
	 *	Returns Text Value.
	 *	@access		public
	 *	@return		string
	 */
	public function getValue()
	{
		return (string) $this;
	}

	/**
	 *	Indicates whether an attribute is existing by it's name.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@return		bool
	 */
	public function hasAttribute( $name, $nsPrefix = NULL  )
	{
		$names	= $this->getAttributeNames( $nsPrefix );
		return in_array( $name, $names );
	}

	/**
	 *	Removes an attribute by it's name.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@return		boolean
	 */
	public function removeAttribute( $name, $nsPrefix = NULL )
	{
		$data	= $nsPrefix ? $this->attributes( $nsPrefix, TRUE ) : $this->attributes();
		foreach( $data as $key => $attributeNode )
		{
			if( $key == $name )
			{
				unset( $data[$key] );
				return TRUE;
			}
		}
		return FALSE;
	}

	public function remove()
	{
		$dom	= dom_import_simplexml( $this );
		$dom->parentNode->removeChild( $dom );
	}

	public function removeChild( $name, $number = NULL )
	{
		$nr		= 0;
		foreach( $this->children() as $nodeName => $child )
		{
			if( $nodeName == $name )
			{
				if( $number === NULL || $nr === (int) $number )
				{
					$dom	= dom_import_simplexml( $child );
					$dom->parentNode->removeChild( $dom );
				}
				$nr++;
			}
		}
	}
	
	/**
	 *	Sets an attribute from by it's name.
	 *	Adds attribute if not existing.
	 *	Removes attribute if value is NULL.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$value		Value of attribute, NULL means removal
	 *	@param		string		$nsPrefix	Namespace prefix of attribute
	 *	@param		string		$nsURI		Namespace URI of attribute
	 *	@return		void
	 */
	public function setAttribute( $name, $value, $nsPrefix = NULL, $nsURI = NULL )
	{
		if( $value !== NULL )
		{
			if( !$this->hasAttribute( $name, $nsPrefix ) )
				return $this->addAttribute( $name, $value, $nsPrefix, $nsURI );
			$this->removeAttribute( $name, $nsPrefix );
			$this->addAttribute( $name, $value, $nsPrefix, $nsURI );
		}
		else if( $this->hasAttribute( $name, $nsPrefix ) )
		{
			$this->removeAttribute( $name, $nsPrefix );
		}
	}
	
	/**
	 *	Returns Text Value.
	 *	@access		public
	 *	@return		string
	 */
	public function setValue( $value, $cdata = FALSE )
	{
		if( !is_string( $value ) && $value !== NULL )
			throw new InvalidArgumentException( 'Value must be a string or NULL' );

		$value	= preg_replace( "/(.*)<!\[CDATA\[(.*)\]\]>(.*)/iU", "\\1\\2\\3", $value );
		if( $cdata || preg_match( '/&|</', $value ) )												//  string is known or detected to be CDATA
		{
			$dom	= dom_import_simplexml( $this );												//  import node in DOM
			$cdata	= $dom->ownerDocument->createCDATASection( $value );							//  create a new CDATA section
			$dom->appendChild( $cdata );															//  replace node with CDATA section
		}
		else
			dom_import_simplexml( $this )->nodeValue	= $value;
	}
}
?>