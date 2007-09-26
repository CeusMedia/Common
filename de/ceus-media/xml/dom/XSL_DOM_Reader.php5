<?php
import( 'de.ceus-media.file.File' );
/**
 *	Reads XSL Files.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.07.2005
 *	@version		0.4
 */
/**
 *	Reads XSL Files.
 *	@package	xml
 *	@subpackage	dom
 *	@uses		File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		30.07.2005
 *	@version		0.4
 */
class XSL_DOM_Reader
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
			$this->loadXSL( $filename );	
	}
	
	/**
	 *	Loads XSL File.
	 *	@access		public
	 *	@param		string		filename		URI of XSLT File
	 *	@return		void
	 */
	function loadXSL( $filename )
	{
		$xsl_file		= new File( $filename );
		$this->_xsl	= $xsl_file->readString();
		$this->_doc	= domxml_xslt_stylesheet( $this->_xsl );
	}
	
	/**
	 *	Returns XSL as string.
	 *	@access		public
	 *	@return		string
	 */
	function getXSL()
	{
		return $this->_xsl;
	}
}
?>