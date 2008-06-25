<?php
import( 'de.ceus-media.xml.dom.Parser' );
import( 'de.ceus-media.net.Reader' );
/**
 *	Loads XML from an URL and parses to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			Net_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
/**
 *	Loads XML from an URL and parses to a Tree of XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@uses			Net_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
class XML_DOM_UrlReader
{
	/**	@var		string		$url			URL of XML File */
	protected $url;
	/**	@var		array		$mimeTypes		List of acceptable Response MIME Type */
	public static $mimeTypes	= array(
		'application/xml',
		'application/xslt+xml',
		'text/xml',
	);
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of XML File
	 *	@return		void
	 */
	public function __construct( $url )
	{
		$this->url	= $url;
	}
	
	/**
	 *	Loads a XML File statically and returns parsed Tree.
	 *	@access		public
	 *	@param		string		$url			URL of XML File
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		XML_DOM_Node
	 */
	public static function load( $url, $curlOptions = array() )
	{
		$reader	= new Net_Reader( $url );
		$reader->setUserAgent( "cmClasses:XML_DOM_UrlReader/0.6" );
		$xml	= $reader->read( $curlOptions );
		$type	= array_shift( explode( ";", $reader->getStatus( CURL_STATUS_CONTENT_TYPE ) ) );
		
		if( !in_array( $type, self::$mimeTypes ) )
			throw new Exception( 'URL "'.$url.'" is not an accepted XML File (MIME Type: '.$type.').' );
			
		$parser	= new XML_DOM_Parser();
		$tree	= $parser->parse( $xml );
		return $tree;
	}
	
	/**
	 *	Reads XML File and returns parsed Tree.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function read()
	{
		return self::load( $this->url );
	}
}
?>