<?php
import( 'de.ceus-media.xml.rss.RSS_DOM_Parser' );
import( 'de.ceus-media.xml.dom.UrlReader' );
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	RSS_DOM_Parser
 *	@uses		XML_DOM_UrlReader
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	RSS_DOM_Parser
 *	@uses		XML_DOM_UrlReader
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
class RSS_DOM_UrlReader extends RSS_DOM_Parser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	url		URL or filename of RSS Feed
	 *	@return		void
	 */
	public function __construct( $url = false )
	{
		parent::__construct();
		$this->_items		= array();
		$this->_parsed	= false;
		$this->_reader	= new XML_DOM_UrlReader();
		if( $url )
			$this->loadUrl( $url );
	}

	/**
	 *	Reads RSS Feed by calling XML_DOM_Reader with parameters.
	 *	@access		public
	 *	@param		string	url			URL or filename of RSS Feed
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		void
	 */
	function loadURL( $url, $abort = true, $verbose = true )
	{
		if( $content = @file( $url ) )
		{
			if( $this->_loaded = $this->loadXML( implode( "\n", $content ), $abort ) )
				return true;
			else
			{
				if( $abort )
					trigger_error( "RSS_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "RSS_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "RSS_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "RSS_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_WARNING );
			return false;
		}
	}
}
?>