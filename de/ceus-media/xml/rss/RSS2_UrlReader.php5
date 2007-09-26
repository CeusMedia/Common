<?php
import( 'de.ceus-media.xml.rss.RSS2_Parser' );
/**
 *	Reader for RSS2 XML URLs.
 *	@see			http://blogs.law.harvard.edu/tech/rss
 *	@package		xml
 *	@subpackage		rss
 *	@extends		RSS2_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version			0.1
 */
/**
 *	Reader for RSS2 XML URLs.
 *	@see			http://blogs.law.harvard.edu/tech/rss
 *	@package		xml
 *	@subpackage		rss
 *	@extends		RSS2_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.01.2006
 *	@version			0.1
 *	@todo			Code Documentation
 */
class RSS2_UrlReader extends RSS2_Parser
{
	public function __construct( $url = false )
	{
		parent::__construct();
		$this->RSS2_Parser();
		if( $url )
			$this->loadURL( $url );
	}
	
	function loadURL( $url )
	{
		$this->_items	= array();
		$this->_xpq->loadURL( $url );
	}
}
?>