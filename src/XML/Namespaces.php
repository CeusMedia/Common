<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Namespace Map to detect and collect namespaces from an XML file, using Simple XML to read XML and import DOM.
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
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use DOMDocument;
use SimpleXMLElement;
use Exception;

/**
 *	Namespace Map to detect and collect namespaces from an XML file, using Simple XML to read XML and import DOM.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Namespaces
{
	/**	@var		array				$namespaces		Map of namespaces */
	protected $namespaces	= [];

	/**
	 *	Adds a namespace to map.
	 *	@access		public
	 *	@param		string				$prefix			Namespace Prefix
	 *	@param		string				$uri			Namespace URI
	 *	@return		self
	 */
	public function addNamespace( string $prefix, string $uri ): self
	{
		$this->namespaces[$prefix]	= $uri;
		return $this;
	}

	/**
	 *	Detects namespaces from an XML DOM Document and returns number of found namespaces.
	 *	@access		public
	 *	@param		DOMDocument			$doc			DOM document of XML file
	 *	@return		integer
	 */
	public function detectNamespacesFromDocument( DOMDocument $doc ): int
	{
		$namespaces	= self::getNamespacesFromDocument( $doc );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}

	/**
	 *	Detects Namespaces from a Simple XML Element and returns number of found namespaces.
	 *	@access		public
	 *	@param		SimpleXMLElement	$element		Simple XML Element
	 *	@return		integer
	 */
	public function detectNamespacesFromSimpleXmlElement( SimpleXMLElement $element ): int
	{
		$namespaces	= self::getNamespacesFromSimpleXmlElement( $element );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}

	/**
	 *	Detects Namespaces from an XML string and returns number of found namespaces.
	 *	@access		public
	 *	@param		string			$xml			XML string
	 *	@return		integer
	 *	@throws		Exception
	 */
	public function detectNamespacesFromXml( string $xml ): int
	{
		$namespaces	= self::getNamespacesFromXml( $xml );
		$this->namespaces	= array_merge( $this->namespaces, $namespaces );
		return count( $namespaces );
	}

	/**
	 *	Returns map of collected namespaces.
	 *	@access		public
	 *	@return		array
	 */
	public function getNamespaces(): array
	{
		return $this->namespaces;
	}

	/**
	 *	Returns map of namespaces found in an XML DOM Document.
	 *	@access		public
	 *	@static
	 *	@param		DOMDocument			$doc			DOM Document
	 *	@return		array
	 */
	public static function getNamespacesFromDocument( DOMDocument $doc ): array
	{
		//  convert DOM Document to Simple XML Element
		$element	= simplexml_import_dom( $doc );
		//  return Namespaces from XML Element
		return self::getNamespacesFromSimpleXmlElement( $element );
	}

	/**
	 *	Detects and returns map of namespaces found in a Simple XML Element.
	 *	@access		public
	 *	@static
	 *	@param		SimpleXMLElement	$element		Simple XML Element
	 *	@param		boolean				$recursive		Flag: search with recursion, default: yes
	 *	@return		array
	 */
	public static function getNamespacesFromSimpleXmlElement( SimpleXMLElement $element, bool $recursive = TRUE ): array
	{
		return $element->getDocNamespaces( $recursive );
	}

	/**
	 *	Detects and returns map of namespaces found in an XML string.
	 *	@access		public
	 *	@static
	 *	@param		string				$xml			XML String
	 *	@return		array
	 *	@throws		Exception
	 */
	public static function getNamespacesFromXml( string $xml ): array
	{
		//  parse XML String
		$element	= new SimpleXMLElement( $xml );
		//  return Namespaces from XML Element
		return self::getNamespacesFromSimpleXmlElement( $element );
	}
}
