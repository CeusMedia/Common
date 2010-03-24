<?php
/**
 *	Namespace Map to detect and collect Namespaces from a XML File.
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
 *	@package		xml.dom
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Namespace Map to detect and collect Namespaces from a XML File.
 *	@category		cmClasses
 *	@package		xml.dom
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@deprecated		use XML_Namespaces instead, which is faster using Simple XML
 *	@todo			remove in 0.6.6
 */
class XML_DOM_Namespaces
{
	/**	@var		array		$namespaces		Map of Namespaces */
	protected $namespaces	= array();

	/**
	 *	Adds a Namespace to Map.
	 *	@access		public
	 *	@param		string		$prefix			Namespace Prefix
	 *	@param		string		$uri			Namespace URI
	 *	@return		void
	 */
	public function addNamespace( $prefix, $uri )
	{
		$this->namespaces[$prefix]	= $uri;
	}
	
	/**
	 *	Detects Namespaces from a XML DOM Document and returns Number of found Namespaces.
	 *	@access		public
	 *	@param		DOMDocument	$doc			DOM Document of XML File
	 *	@return		void
	 */
	public function detectNamespacesFromDocument( $doc )
	{
		$namespaces	= self::getNamespacesFromDocument( $doc );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}
	
	/**
	 *	Detects Namespaces from a XML File and returns Number of found Namespaces.
	 *	@access		public
	 *	@param		DOMDocument	$doc			DOM Document of XML File
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
	 *	Detects and returns Map of Namespaces found in a XML DOM Document.
	 *	@access		public
	 *	@static
	 *	@param		DOMDocument		$doc			DOM Document of XML File
	 *	@return		array
	 */
	public static function getNamespacesFromDocument( $doc )
	{
		$nodes = $doc->getElementsByTagNameNS( "*", "*" );
		foreach( $nodes as $node )
		{
			$prefix	= strtolower( $node->prefix );
			if( array_key_exists( $prefix, $this->namespaces ) )
				continue;
			$this->namespaces[$prefix] = $node->namespaceURI;
		}
		return $namespaces;
	}
	
	/**
	 *	Detects and returns Map of Namespaces found in a XML File.
	 *	@access		public
	 *	@static
	 *	@return		array
	 */
	public static function getNamespacesFromXml( $xml )
	{
		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace	= FALSE;
		$doc->loadXml( $xml );
		return self::getNamespacesFromDocument( $doc );

		$nodes = $doc->getElementsByTagNameNS( "*", "*" );
		foreach( $nodes as $node )
		{
			$prefix	= strtolower( $node->prefix );
			if( array_key_exists( $prefix, $namespaces ) )
				continue;
			$namespaces[$prefix] = $node->namespaceURI;
		}
		return $namespaces;
	}
}
?>