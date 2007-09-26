<?php
import ("de.ceus-media.xml.dom.XML_DOM_Parser");
/**
 *	Reads a XML String from a URL.
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_Parser
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Reads a XML String from a URL.
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_Parser
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_DOM_UrlReader extends XML_DOM_Parser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		url			URI of XML File
	 *	@param		bool			abort		Flag: break on Errors showing Messages
	 *	@param		bool			verbose		Flag: show Warnings
	 *	@return		void
	 */
	public function __construct( $url = false, $abort = true, $verbose = true )
	{
		if( $url )
			$this->loadUrl( $url, $abort, $verbose );
	}
	
	/**
	 *	Loads a XML String from a URL.
	 *	@access		public
	 *	@param		string		url			URI of XML File
	 *	@param		bool			abort		Flag: break on Errors showing Messages
	 *	@param		bool			verbose		Flag: show Warnings
	 *	@return		void
	 */
	function loadURL( $url, $abort = true, $verbose = true )
	{
		if( $content = @file( $url ) )
		{
			if( $this->loadXML( implode( "\n", $content ), $abort ) )
				return true;
			else
			{
				if( $abort )
					trigger_error( "XML_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "XML_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "XML_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "XML_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_WARNING );
			return false;
		}
	}	
}
?>