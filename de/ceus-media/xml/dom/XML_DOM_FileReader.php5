<?php
import ("de.ceus-media.file.File");
import ("de.ceus-media.xml.dom.XML_DOM_Parser");
/**
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	xml
 *	@subpackage	dom
 *	@extends	XML_DOM_Parser
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_DOM_FileReader extends XML_DOM_Parser
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
	public function __construct( $filename = false )
	{
		if( $filename )
			$this->loadFile( $filename );
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
					trigger_error( "XML_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_ERROR );
				else if( $verbose )
					trigger_error( "XML_DOM_FileReader[loadFile]: XML File '".$filename."' could not been loaded", E_USER_WARNING );
			}
		}
		else
		{
			if( $abort )
				trigger_error( "XML_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_ERROR );
			else if( $verbose )
				trigger_error( "XML_DOM_FileReader[loadFile]: XML File '".$filename."' does not exist", E_USER_WARNING );
		}
		return false;
	}
}
?>