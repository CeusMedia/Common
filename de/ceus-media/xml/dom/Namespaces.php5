<?php
/**
 *	Namespace Map to detect and collect Namespaces from a XML File.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Namespace Map to detect and collect Namespaces from a XML File.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
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