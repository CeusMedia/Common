<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.net.cURL' );
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Evaluator for XPath Queries.
 *	@package		xml.dom
 *	@extends		ADT_OptionObject
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 */
/**
 *	Evaluator for XPath Queries.
 *	@package		xml.dom
 *	@extends		ADT_OptionObject
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 */
class XML_DOM_XPathQuery extends ADT_OptionObject
{
	/**	@var		DOMDocument	$document		DOM Document Object */
	var $document	= NULL;
	/**	@var		DOMXPath	$xPath			DOM XPath Object */
	var $xPath		= NULL;

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( "followlocation", true );
		$this->setOption( "header", false );
		$this->setOption( "ssl_verifypeer", true );
	}
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function evaluate( $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
		if( $node )
			$nodeList	= $this->xPath->evaluate( $path, $node );
		else
			$nodeList	= $this->xPath->evaluate( $path );
		return $nodeList;
	}

	/**
	 *	Returns DOM Document of loaded XML File.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function getDocument()
	{
		if( !$this->document )
			throw new Exception( 'No XML loaded yet.' );
		return $this->document;
	}
	/**
	 *	Loads XML from File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to load XML from
	 *	@return		bool
	 */
	public function loadFile( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'XML File "'.$fileName.'" is not existing.' );
		$this->document	= new DOMDocument();
		$this->document->load( $fileName );
		$this->xPath	= new DOMXpath( $this->document );
		return true;
	}
	
	/**
	 *	Loads XML from URL.
	 *	@access		public
	 *	@param		string		$url			URL to load XML from
	 *	@return		bool
	 *	@todo		Error Handling
	 */
	public function loadUrl( $url )
	{
		$cURL	= new Net_cURL( $url );
		foreach( $this->getOptions() as $key => $value )
			$cURL->setOption( "CURLOPT_".strtoupper( $key ), $value ) ;
		$xml = $cURL->exec();
		if( !$xml )
			throw new Exception( 'No XML found for URL "'.$url.'".' );
		$this->loadXml( $xml );
		return true;
	}
	
	/**
	 *	Loads XML into XPath Parser.
	 *	@access		public
	 *	@return		void
	 */
	public function loadXml( $xml )
	{
		$this->document	= new DOMDocument();
		$this->document->loadXml( $xml );
		$this->xPath	= new DOMXPath( $this->document );
	}
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function query( $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
		if( $node )
			$nodeList	= $this->xPath->query( $path, $node );
		else
			$nodeList	= $this->xPath->query( $path );
		return $nodeList;
	}

	/** 
	 *	Registers a Namespace for a Prefix.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of Namespace
	 *	@param		string		$namespace		Namespace of Prefix
	 *	@return		bool
	 *	@see		http://tw.php.net/manual/de/function.dom-domxpath-registernamespace.php
	 */
	public function registerNamespace( $prefix, $namespace )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
		return $this->xPath->registerNamespace( $prefix, $namespace );
	}
}
?>