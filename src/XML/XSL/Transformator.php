<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Transformator for XML and XSLT.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_XSL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\XSL;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use DOMDocument;
use InvalidArgumentException;
use XSLTProcessor;

/**
 *	Transformator for XML and XSLT.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_XSL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Transformator
{
	/**	@var	string		$xml		Content of XML File */
	protected string $xml;
	/**	@var	string		$xml		Content of XSLT File */
	protected string $xsl;

	/**
	 *	Loads XML File.
	 *	@access		public
	 *	@param		string		$xmlFile		File Name of XML File
	 *	@return		void
	 */
	public function loadXmlFile( string $xmlFile ): void
	{
		$reader		= new FileReader( $xmlFile );
		$this->xml	= $reader->readString();
	}

	/**
	 *	Loads XSL File.
	 *	@access		public
	 *	@param		string		$xslFile		File Name of XSL File
	 *	@return		void
	 */
	public function loadXslFile( string $xslFile ): void
	{
		$reader		= new FileReader( $xslFile );
		$this->xsl	= $reader->readString();
	}

	/**
	 *	Transforms loaded XML and XSL and returns Result.
	 *	@access		public
	 *	@return		string
	 */
	public function transform(): string
	{
		if( !( $this->xml && $this->xsl ) )
			throw new InvalidArgumentException( 'XML and XSL must be set.' );
		$xml	= new DOMDocument();
		$xml->loadXML( $this->xml );
		$xsl	= new DOMDocument();
		$xsl->loadXML( $this->xsl );
		$proc	= new XSLTProcessor();
		$proc->importStyleSheet( $xsl );
		return $proc->transformToXML( $xml );
	}

	/**
	 *	Transforms XML with XSLT.
	 *	@access		public
	 *	@param		string		$outFile		File Name for Output
	 *	@return		integer
	 */
	public function transformToFile( string $outFile ): int
	{
		$result	= $this->transform();
		$writer	= new FileWriter( $outFile );
		return $writer->writeString( $result );
	}
}
