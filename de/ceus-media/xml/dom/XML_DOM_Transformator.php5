<?php
import( 'de.ceus-media.xml.dom.XML_DOM_FileReader' );
import( 'de.ceus-media.xml.dom.XSLT_DOM_Reader' );
/**
 *	Transformator for XML and XSLT.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XSLT_DOM_Reader
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.07.2005
 *	@version		0.4
 */
/**
 *	Transformator for XML and XSLT.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		XSLT_DOM_Reader
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.07.2005
 *	@version		0.4
 */
class XML_DOM_Transformator extends XSLT_DOM_Reader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		xml_file		URI of XML File
	 *	@param		string		xsl_file		URI of XSLT File
	 *	@return		void
	 */
	public function __construct( $xml_file = false, $xsl_file = false )
	{
		if( $xml_file )
			$this->_xml	= new XML_DOM_FileReader( $xml_file );
		if( $xsl_file )
			$this->_xsl	= new XSLT_DOM_Reader( $xsl_file );
	}
	
	/**
	 *	Transforms XML with XSLT.
	 *	@access		public
	 *	@param		string		xml_file		URI of XML File
	 *	@param		string		xsl_file		URI of XSLT File
	 *	@return		string
	 */
	function transform( $xml_file = false, $xsl_file = false )
	{
		if( $xml_file )
			$this->_xml	= new XML_DOM_FileReader( $xml_file );
		if( $xsl_file )
			$this->_xsl	= new XSLT_DOM_Reader( $xsl_file );
		if( $this->_xml && $this->_xsl )
		{
			$proc	= new XSLTProcessor();
			$proc->importStyleSheet( $this->_xsl->_doc );
			$result =  $proc->transformToXML( $this->_xml->_doc );
			return $result;
		}
	}
}
?>