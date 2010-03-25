<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Transformator for XML and XSLT.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		xml.xsl
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.07.2005
 *	@version		$Id$
 */
/**
 *	Transformator for XML and XSLT.
 *	@category		cmClasses
 *	@package		xml.xsl
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.07.2005
 *	@version		$Id$
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