<?php
import ("de.ceus-media.file.File");
import ("de.ceus-media.xml.opml.OPML_DOM_Parser");
/**
 *	@package	xml
 *	@subpackage	opml
 *	@extends	OPML_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	xml
 *	@subpackage	opml
 *	@extends	OPML_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class OPML_DOM_FileReader extends OPML_DOM_Parser
{
	/**	@var	string			_xml			XML String */
	var $_xml = "";
	/**	@var	bool				_loaded		Flag: XML File was loaded */
	var $_loaded	= false;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of XML File
	 *	@return		void
	 */
	public function __construct( $filename = false, $abort = true, $verbose = true )
	{
		parent::__construct( false, $abort, $verbose );
		if( $filename )
			$this->loadFile( $filename, $abort, $verbose );
	}
	
	/**
	 *	Loads a XML File.
	 *	@access		public
	 *	@param		string	filename		URI of XML File
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
					trigger_error( "OPML_DOM_FileReader[loadFile]: OPML File '".$filename."' could not been loaded", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "OPML_DOM_FileReader[loadFile]: OPML File '".$filename."' could not been loaded", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "OPML_DOM_FileReader[loadFile]: OPML File '".$filename."' does not exist", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "OPML_DOM_FileReader[loadFile]: OPML File '".$filename."' does not exist", E_USER_WARNING );
		}
		return false;
	}
}
?>