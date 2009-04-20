<?php
/**
 *	Namespace Map to detect and collect Namespaces from a XML File, usind Simple XML to read XML and import DOM.
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
 *	@package		xml.dom
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
/**
 *	Namespace Map to detect and collect Namespaces from a XML File, usind Simple XML to read XML and import DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
class XML_Namespaces
{
	/**	@var		array				$namespaces		Map of Namespaces */
	protected $namespaces	= array();

	/**
	 *	Adds a Namespace to Map.
	 *	@access		public
	 *	@param		string				$prefix			Namespace Prefix
	 *	@param		string				$uri			Namespace URI
	 *	@return		void
	 */
	public function addNamespace( $prefix, $uri )
	{
		$this->namespaces[$prefix]	= $uri;
	}
	
	/**
	 *	Detects Namespaces from a XML DOM Document and returns Number of found Namespaces.
	 *	@access		public
	 *	@param		DOMDocument			$doc			DOM Document of XML File
	 *	@return		void
	 */
	public function detectNamespacesFromDocument( $doc )
	{
		$namespaces	= self::getNamespacesFromDocument( $element );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}
	
	/**
	 *	Detects Namespaces from a XML DOM Document and returns Number of found Namespaces.
	 *	@access		public
	 *	@param		DOMDocument			$doc			DOM Document of XML File
	 *	@return		void
	 */
	public function detectNamespacesFromSimpleXmlElement( $element )
	{
		$namespaces	= self::getNamespacesFromSimpleXmlElement( $element );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}
	
	/**
	 *	Detects Namespaces from a XML File and returns Number of found Namespaces.
	 *	@access		public
	 *	@param		DOMDocument			$doc			DOM Document of XML File
	 *	@return		void
	 */
	public function detectNamespacesFromXml( $xml )
	{
		$namespaces	= self::getNamespacesFromXml( $xml );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}

	/**
	 *	Returns Map of collected Namespaces.
	 *	@access		public
	 *	@return		array
	 */
	public function getNamespaces()
	{
		return $this->namespaces;
	}

	/**
	 *	Returns Map of Namespaces found in a XML DOM Document.
	 *	@access		public
	 *	@param		DOMDocument			$doc			DOM Document of XML File
	 *	@return		array
	 */
	public static function getNamespacesFromDocument( $doc )
	{
		$element	= simplexml_import_dom( $doc );									//  convert DOM Document to Simple XML Element
		return self::getNamespacesFromSimpleXmlElement( $element );					//  return Namespaces from XML Element
	}

	/**
	 *	Detects and returns Map of Namespaces found in a XML DOM Document.
	 *	@access		public
	 *	@param		SimpleXmlElement	$element		Simple XML Element of XML File
	 *	@return		array
	 */
	public static function getNamespacesFromSimpleXmlElement( $element, $recursive = TRUE )
	{
		return $element->getDocNamespaces( $element );
	}
	
	/**
	 *	Detects and returns Map of Namespaces found in a XML File.
	 *	@access		public
	 *	@param		string				$xml			XML String
	 *	@return		array
	 */
	public static function getNamespacesFromXml( $xml )
	{
		$element	= new SimpleXMLElement( $xml );									//  parse XML String
		return self::getNamespacesFromSimpleXmlElement( $element );					//  return Namespaces from XML Element
	}
}
?>