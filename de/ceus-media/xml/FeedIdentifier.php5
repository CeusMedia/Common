<?php
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml.dom
 *	@uses			File_Reader
 *	@uses			Net_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_FeedIdentifier
{
	/**	@var		string		$type			Type of Feed */
	protected $type	= "";
	/**	@var		string		$version		Version of Feed Type */
	protected $version	= "";
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string		$xml		XML of Feed
	 *	@return		bool
	 */
	public function identify( $xml )
	{
		$this->type		= "";
		$this->version	= "";
		$xsv	= new XML_DOM_SyntaxValidator;
		if( !$xsv->validate( $xml ) )
			throw new Exception( 'XML is not valid: '.$xsv->getErrors() );

		$doc	=& $xsv->getDocument();
		$xpath	= new DOMXPath( $doc );

		//  --  RSS  --  //
		$rss	= $xpath->query( "//rss/@version" );
		if( $rss->length )
		{
			$this->type		= "RSS";
			$this->version	= $rss->item( 0 )->value;
			return TRUE;
		}

		//  --  RSS 1.0 - RDF  --  //
		$namespace	= $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "rdf", $namespace );
		$rdf		= $xpath->evaluate( "//rdf:RDF" );
		if( $rdf->length )
		{
			$this->type		= "RSS";
			$this->version	= "1.0";
			return TRUE;
		}

		//  --  ATOM  --  //
		$atom	= $xpath->evaluate( "//feed/@version" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$namespace = $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "pre", $namespace );
		$atom	= $xpath->evaluate( "//pre:feed/@version" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$atom	= $xpath->evaluate( "//pre:feed/pre:title/text()" );
		if( $atom->length )
		{
			$this->type		= "ATOM";
			$this->version	= "1.0";
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string	filename		XML File of Feed
	 *	@return		string
	 */
	public static function identifyFromFile( $file )
	{
		import( 'de.ceus-media.file.Reader' );
		$xml	= File_Reader::load( $filename );
		return $this->identify( $xml );
	}

	/**
	 *	Identifies Feed from an URL.
	 *	@access		public
	 *	@param		string		$url		URL of Feed
	 *	@param		int			$timeout	Timeout in seconds
	 *	@return		string
	 */
	public function identifyFromUrl( $url, $timeout = 5 )
	{
		import( 'de.ceus-media.net.Reader' );
		Net_cURL::setTimeOut( $timeout );
		$xml	= Net_Reader::readUrl( $url );
		return $this->identify( $xml );
	}
}
?>