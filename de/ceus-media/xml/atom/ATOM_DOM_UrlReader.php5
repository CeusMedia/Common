<?php
import( 'de.ceus-media.xml.atom.ATOM_DOM_Parser' );
import( 'de.ceus-media.xml.dom.UrlReader' );
/**
 *	Reader for ATOM Feeds.
 *	@package	xml
 *	@subpackage	atom
 *	@extends	ATOM_DOM_Parser
 *	@uses		XML_DOM_UrlReader
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		24.01.2006
 *	@version		0.1
 */
/**
 *	Reader for ATOM Feeds.
 *	@package	xml
 *	@subpackage	atom
 *	@extends	ATOM_DOM_Parser
 *	@uses		XML_DOM_UrlReader
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		24.01.2006
 *	@version		0.1
 */
class ATOM_DOM_UrlReader extends ATOM_DOM_Parser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	url		URL or filename of ATOM Feed
	 *	@return		void
	 */
	public function __construct( $url = false )
	{
		parent::__construct();
		$this->_entries	= array();
		$this->_parsed	= false;
		$this->_reader	= new XML_DOM_UrlReader();
		if( $url )
			$this->loadUrl( $url );
	}

	/**
	 *	Reads ATOM Feed by calling XML_DOM_Reader with parameters.
	 *	@access		public
	 *	@param		string	url			URL or filename of ATOM Feed
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
					trigger_error( "ATOM_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "ATOM_DOM_UrlReader[loadURL]: Error while reading XML from URL'".$url."'.", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "ATOM_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "ATOM_DOM_UrlReader[loadURL]: Error while reading URL '".$url."'.", E_USER_WARNING );
			return false;
		}
	}
}
?>