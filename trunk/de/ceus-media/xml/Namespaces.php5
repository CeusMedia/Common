<?php
/**
 *	Namespace Map to detect and collect Namespaces from a XML File, usind Simple XML to read XML and import DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Namespace Map to detect and collect Namespaces from a XML File, usind Simple XML to read XML and import DOM.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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