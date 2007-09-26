<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.rss.RSS_DOM_Parser' );
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	RSS_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
/**
 *	Reader for RSS Feeds. RSS versions 0.91, 0.92, 1.0 and 2.0 are supported.
 *	@package	xml
 *	@subpackage	rss
 *	@extends	RSS_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
class RSS_DOM_FileReader extends RSS_DOM_Parser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		File Name of RSS Feed
	 *	@return		void
	 */
	public function __construct( $filename = false )
	{
		parent::__construct();
		$this->_items		= array();
		$this->_parsed	= false;
		if( $filename )
			$this->loadFile( $filename );
	}

	/**
	 *	Reads RSS Feed by calling XML_DOM_Reader with parameters.
	 *	@access		public
	 *	@param		string	filename		File Name of RSS Feed
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		bool
	 */
	function loadFile( $filename, $abort = true, $verbose = true )
	{
		$file	= new File( $filename );
		$this->_loaded	= false;
		if( $file->exists() )
		{
			$xml	= $file->readString();
			if( $this->_loaded = $this->loadXML( $xml, $abort ) )
				return true;
			else
			{
				if( $abort )
					trigger_error( "RSS_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "RSS_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "RSS_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "RSS_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_WARNING );
		}
		return false;
	}
}
?>
