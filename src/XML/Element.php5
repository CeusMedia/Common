<?php
/**
 *	XML element based on SimpleXMLElement with improved attribute Handling.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		$Id$
 */
/**
 *	XML element based on SimpleXMLElement with improved attribute Handling.
 *	@category		cmClasses
 *	@package		XML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.02.2008
 *	@version		$Id$
 *	@todo			namespace handling: check current usage (URI vs. prefix?) and finish implementation
 */
class XML_Element extends SimpleXMLElement
{
	protected $attributes	= array();

	/**
	 *	Adds an attributes.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$value		Value of attribute
	 *	@param		string		$namespace	Namespace URI of attribute
	 *	@return		void
	 *	@throws		Exception	if attribute is already set
	 */
	public function addAttribute( $name, $value, $namespace = NULL )
	{
		$names	= $this->getAttributeNames();
		if( $this->hasAttribute( $name ) )
			throw new Exception( 'Attribute "'.$name.'" is already set' );
		parent::addAttribute( $name, $value, $namespace );
	}
	/** 
	 *	Add CDATA text in a node 
	 *	@param		string		$cdata_text		The CDATA value  to add 
	 */ 
	private function addCData( $text ) 
	{ 
		$node		= dom_import_simplexml( $this ); 
		$document	= $node->ownerDocument; 
		$node->appendChild( $document->createCDATASection( $text ) ); 
	} 

	/** 
	 *	Create a child with CDATA value 
	 *	@param		string		$name			The name of the child element to add. 
	 *	@param		string		$cdata_text		The CDATA value of the child element. 
	 *	@param		string		$namespace		Namespace of node
	 */ 
	public function addChildCData( $name, $text, $namespace = NULL ) 
	{ 
		$child	= $this->addChild( $name, NULL, $namespace ); 
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
	 *	Returns count of Attributes.
	 *	@access		public
	 *	@return		int
	 */
	public function countAttributes()
	{
		return count( $this->getAttributeNames() );
	}

	/**
	 *	Returns count of Children.
	 *	@access		public
	 *	@return		int
	 */
	public function countChildren()
	{
		$i = 0;
		foreach( $this->children() as $node )
			$i++;
		return $i;
	}

	/**
	 *	Returns the value of an Attribute from it's name.
	 *	@access		public
	 *	@param		string		$name		Name of attribute
	 *	@param		string		$namespace	Namespace of attribute
	 *	@return		bool
	 */
	public function getAttribute( $name, $namespace = NULL )
	{
		$data	= $namespace ? $this->attributes( $namespace, 1 ) : $this->attributes();
		if( !isset( $data[$name] ) )
			throw new Exception( 'Attribute "'.$name.'" is not set.' );
		return (string) $data[$name];
	}

	/**
	 *	Returns List of Attribute Keys.
	 *	@access		public
	 *	@param		string		$namespace	Namespace of attribute
	 *	@return		array
	 */
	public function getAttributeNames( $namespace = NULL )
	{
		$list	= array();
		$data	= $namespace ? $this->attributes( $namespace, 1 ) : $this->attributes();
		foreach( $data as $name => $value )
			$list[] = $name;
		return $list;
	}

	/**
	 *	Returns Array of Attributes.
	 *	@access		public
	 *	@param		string		$namespace	Namespace of attributes
	 *	@return		array
	 */
	public function getAttributes( $namespace = NULL )
	{
		$list	= array();
		foreach( $this->attributes( $namespace ) as $name => $value )
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
	 *	@param		string		$namespace	Namespace of attribute
	 *	@return		bool
	 */
	public function hasAttribute( $name, $namespace = NULL  )
	{
		$names	= $this->getAttributeNames( $namespace );
		return in_array( $name, $names );
	}
}
?>