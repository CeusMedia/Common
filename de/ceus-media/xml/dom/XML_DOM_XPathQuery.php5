<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.protocol.cURL' );
import( 'de.ceus-media.xml.dom.XML_DOM_SyntaxValidator' );
/**
 *	Evaluator for XPath Queries.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.2
 */
/**
 *	Evaluator for XPath Queries.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.2
 *	@todo			Code Documentation
 */
class XML_DOM_XPathQuery extends OptionObject
{
	/**	@var	string	_xpath		XPath Object */
	var $_xpath;

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
	 *	Loads XML from URL.
	 *	@access		public
	 *	@param		string		url			URL to load XML from
	 *	@return		bool
	 *	@todo		Error Handling
	 */
	function loadURL( $url )
	{
		$cURL	= new cURL( $url );
		foreach( $this->getOptions() as $key => $value )
		{
			$contant	= "CURLOPT_".strtoupper( $key );
			$cURL->setopt( $contant, $value ) ;
		}
		if( $xml = $cURL->exec() )
		{
			$this->loadXML( $xml );
			return true;
		}
		return false;
	}
	
	/**
	 *	Loads XML from File.
	 *	@access		public
	 *	@param		string		filename		File Name to load XML from
	 *	@return		bool
	 */
	function loadFile( $filename )
	{
		$file		= new File( $filename );
		if( $file->exists() )
		{
			$xml		= $file->readString();
			$this->loadXML( $xml );
			return true;
		}
		return false;
	}
	
	/**
	 *	Loads XML into XPath Parser.
	 *	@access		public
	 *	@return		void
	 */
	function loadXML( $xml )
	{
		$xsv	= new XML_DOM_SyntaxValidator;
		if( $xsv->validate( $xml ) )
		{
			$doc		=& $xsv->getDocument();
			$this->_xpath	= $doc->xpath_new_context();
		}
		else
			trigger_error( "XML_DOM_XPathQuery[loadXML]: XML Document is not valid", E_USER_WARNING );
	}
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	function query( $path, $key = false )
	{
		if( $this->_xpath )
		{
			$x	= $this->_xpath->xpath_eval( $path );
			if( $key )
				return $this->_getNodeContent( $x, $key );
			return $x;
		}
		else
			trigger_error( "XML_DOM_XPathQuery[query]: XML not yet loaded", E_USER_ERROR );
	}
	
	function registerNameSpace( $prefix, $namespace )
	{
		if( $this->_xpath )
		{
			xpath_register_ns( $this->_xpath, $prefix, $namespace );
		}
		else
			trigger_error( "XML_DOM_XPathQuery[registerNameSpace]: XML not yet loaded", E_USER_ERROR );
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	function _getNodeContent( $node, $attribute = "content" )
	{
		$return	= array();
		if( isset( $node->nodeset) )
			foreach( $node->nodeset as $content )
				$return[]	= $content->{$attribute};
		return $return;       
	}
}
?>