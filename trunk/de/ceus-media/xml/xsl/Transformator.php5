<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Transformator for XML and XSLT.
 *	@package		xml.xsl
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.07.2005
 *	@version		0.6
 */
/**
 *	Transformator for XML and XSLT.
 *	@package		xml.xsl
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.07.2005
 *	@version		0.6
 */
class XML_XSL_Transformator
{
	/**	@var	string		$xml		Content of XML File */
	protected $xml;
	/**	@var	string		$xml		Content of XSLT File */
	protected $xsl;

	/**
	 *	Loads XML File.
	 *	@access		public
	 *	@param		string		$xmlFile		File Name of XML File
	 *	@return		void
	 */
	public function loadXmlFile( $xmlFile )
	{
		$reader		= new File_Reader( $xmlFile );
		$this->xml	= $reader->readString();
	}

	/**
	 *	Loads XSL File.
	 *	@access		public
	 *	@param		string		$xslFile		File Name of XSL File
	 *	@return		void
	 */
	public function loadXslFile( $xslFile )
	{
		$reader		= new File_Reader( $xslFile );
		$this->xsl	= $reader->readString();
	}
	
	/**
	 *	Transforms loaded XML and XSL and returns Result.
	 *	@access		public
	 *	@return		string
	 */
	public function transform()
	{
		if( !( $this->xml && $this->xsl ) )
			throw new InvalidArgumentException( 'XML and XSL must be set.' );
		$xml	= DOMDocument::loadXML( $this->xml );
		$xsl	= DOMDocument::loadXML( $this->xsl );
		$proc	= new XSLTProcessor();
		$proc->importStyleSheet( $xsl );
		$result =  $proc->transformToXML( $xml );
		return $result;
	}

	/**
	 *	Transforms XML with XSLT.
	 *	@access		public
	 *	@param		string		$xmlFile		File Name of XML File
	 *	@param		string		$xsltFile		File Name of XSLT File
	 *	@param		string		$outFile		File Name for Output
	 *	@return		string
	 */
	public function transformToFile( $outFile = false )
	{
		$result	= $this->transform();
		$writer	= new File_Writer( $outFile );
		return $writer->writeString( $result );
	}
}
?>