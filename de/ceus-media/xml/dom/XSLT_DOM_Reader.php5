<?php
import( 'de.ceus-media.file.File' );
/**
 *	Reads XSLT Files.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.07.2005
 *	@version		0.4
 */
/**
 *	Reads XSLT Files.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.07.2005
 *	@version		0.4
 */
class XSLT_DOM_Reader
{
	/**	@var	DomDocument		_doc		DOM Document */
	var $_doc;
	/**	@var	string			_xsl			XSLT String */
	var $_xsl;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of XSLT File
	 *	@return		void
	 */
	public function __construct( $filename = false )
	{
		if( $filename )
			$this->loadXSLT( $filename );	
	}
	
	/**
	 *	Loads XSLT File.
	 *	@access		public
	 *	@param		string		filename		URI of XSLT File
	 *	@return		void
	 */
	function loadXSLT( $filename )
	{
		$xslt_file		= new File($filename);
		$this->_xsl	= $xslt_file->readString();
		$this->_doc	= new DOMDocument();
		$this->_doc->loadXML( $this->_xsl );
	}
	
	/**
	 *	Returns XSLT as string.
	 *	@access		public
	 *	@return		string
	 */
	function getXSL()
	{
		return $this->_xsl;
	}
}
?>