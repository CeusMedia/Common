<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.atom.ATOM_DOM_Parser' );
/**
 *	Reader for ATOM Feeds.
 *	@package	xml
 *	@subpackage	atom
 *	@extends	ATOM_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
/**
 *	Reader for ATOM Feeds.
 *	@package	xml
 *	@subpackage	atom
 *	@extends	ATOM_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		19.07.2005
 *	@version		0.4
 */
class ATOM_DOM_FileReader extends ATOM_DOM_Parser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		File Name of ATOM Feed
	 *	@return		void
	 */
	public function __construct( $filename = false )
	{
		parent::__construct();
		$this->_entries	= array();
		$this->_parsed	= false;
		if( $filename )
			$this->loadFile( $filename );
	}

	/**
	 *	Reads ATOM Feed by calling XML_DOM_Reader with parameters.
	 *	@access		public
	 *	@param		string	filename		File Name of ATOM Feed
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return		bool
	 */
	function loadFile( $filename, $abort = true, $verbose = true )
	{
		$file	= new File_Reader( $filename );
		$this->_loaded	= false;
		if( $file->exists() )
		{
			$xml	= $file->readString();
			if( $this->_loaded = $this->loadXML( $xml, $abort ) )
				return true;
			else
			{
				if( $abort )
					trigger_error( "ATOM_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "ATOM_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "ATOM_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "ATOM_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_WARNING );
		}
		return false;
	}
}
?>
