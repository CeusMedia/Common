<?php
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml.dom
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 */
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml.dom
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_FeedIdentifier
{
	/**	@var		string		$type			Type of Feed */
	protected $type	= "";
	/**	@var		string		$version		Version of Feed Type */
	protected $version	= "";
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string		$xml		XML of Feed
	 *	@return		bool
	 */
	public function identify( $xml )
	{
		$this->type		= "";
		$this->version	= "";
		$xsv	= new XML_DOM_SyntaxValidator;
		if( !$xsv->validate( $xml ) )
			throw new Exception( 'XML is not valid: '.$xsv->getErrors() );

		$doc	=& $xsv->getDocument();
		$xpath	= new DOMXPath( $doc );

		//  --  RSS  --  //
		$rss	= $xpath->query( "//rss/@version" );
		if( $rss->length )
		{
			$this->type		= "RSS";
			$this->version	= $rss->item( 0 )->value;
			return TRUE;
		}

		//  --  RSS 1.0 - RDF  --  //
		$namespace	= $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "rdf", $namespace );
		$rdf		= $xpath->evaluate( "//rdf:RDF" );
		if( $rdf->length )
		{
			$this->type		= "RSS";
			$this->version	= "1.0";
			return TRUE;
		}

		//  --  ATOM  --  //
		$atom	= $xpath->evaluate( "//feed/@version" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$namespace = $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "pre", $namespace );
		$atom	= $xpath->evaluate( "//pre:feed/@version" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$atom	= $xpath->evaluate( "//pre:feed/pre:title/text()" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= "1.0";
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string	filename		XML File of Feed
	 *	@return		string
	 */
	public static function identifyFromFile( $file )
	{
		import( 'de.ceus-media.file.Reader' );
		$xml	= File_Reader::load( $filename );
		return $this->identify( $xml );
	}

	/**
	 *	Identifies Feed from an URL.
	 *	@access		public
	 *	@param		string		$url		URL of Feed
	 *	@return		string
	 */
	public function identifyFromUrl( $url )
	{
		import( 'de.ceus-media.net.Reader' );
		Net_cURL::setTimeOut( 5 );
		$xml	= Net_Reader::readUrl( $url );
		return $this->identify( $xml );
	}
}
?>