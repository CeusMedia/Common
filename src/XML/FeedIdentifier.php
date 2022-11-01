<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\Net\Reader as NetReader;
use CeusMedia\Common\XML\DOM\SyntaxValidator;
use DOMXPath;
use Exception;

/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Unit Test
 */
class FeedIdentifier
{
	/**	@var		string		$type			Type of Feed */
	protected $type				= '';

	/**	@var		string		$version		Version of Feed Type */
	protected $version			= '';

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@static
	 *	@param		string		$filename		XML File of Feed
	 *	@return		self
	 *	@throws		Exception
	 */
	public static function fromFile( string $filename ): self
	{
		$identifier	= new FeedIdentifier();
		$identifier->identifyFromFile( $filename );
		return $identifier;
	}

	/**
	 *	Identifies Feed from a URL.
	 *	@access		public
	 *	@static
	 *	@param		string		$url		URL of Feed
	 *	@param		int			$timeout	Timeout in seconds
	 *	@return		self
	 *	@throws		Exception
	 */
	public static function fromUrl( string $url, int $timeout = 5 ): self
	{
		$identifier	= new FeedIdentifier();
		$identifier->identifyFromUrl( $url, $timeout );
		return $identifier;
	}

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string		$xml		XML of Feed
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function identify( string $xml ): bool
	{
		$this->type		= "";
		$this->version	= "";
		$xsv	= new SyntaxValidator();
		if( !$xsv->validate( $xml ) )
			throw new Exception( 'XML is not valid: '.$xsv->getErrors() );

		$doc	=& $xsv->getDocument();
		$xpath	= new DOMXPath( $doc );

		//  --  RSS  --  //
		$rss	= $xpath->query( "//rss/@version" );
		if( $rss->length ){
			$this->type		= "RSS";
			$this->version	= $rss->item( 0 )->nodeValue;
			return TRUE;
		}

		//  --  RSS 1.0 - RDF  --  //
		$namespace	= $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "rdf", $namespace );
		$rdf		= $xpath->evaluate( "//rdf:RDF" );
		if( $rdf->length ){
			$this->type		= "RSS";
			$this->version	= "1.0";
			return TRUE;
		}

		//  --  ATOM  --  //
		$atom	= $xpath->evaluate( "//feed/@version" );
		if( $atom->length ){
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$namespace = $xpath->evaluate( 'namespace-uri(//*)' );
		$xpath->registerNamespace( "pre", $namespace );
		$atom	= $xpath->evaluate( "//pre:feed/@version" );
		if( $atom->length ){
			$this->type		= "ATOM";
			$this->version	= $atom->item( 0 )->value;
			return TRUE;
		}

		$atom	= $xpath->evaluate( "//pre:feed/pre:title/text()" );
		if( $atom->length ){
			$this->type		= "ATOM";
			$this->version	= "1.0";
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string		$filename		XML File of Feed
	 *	@return		bool
	 *	@throws		Exception
	 */
	public function identifyFromFile( string $filename ): bool
	{
		return $this->identify( FileReader::load( $filename ) );
	}

	/**
	 *	Identifies Feed from a URL.
	 *	@access		public
	 *	@param		string		$url		URL of Feed
	 *	@param		integer		$timeout	Timeout in seconds
	 *	@return		boolean
	 *	@throws		Exception
	 */
	public function identifyFromUrl( string $url, int $timeout = 5 ): bool
	{
		return $this->identify( NetReader::readUrl( $url, [
			CURLOPT_TIMEOUT => $timeout,
			CURLOPT_CONNECTTIMEOUT => $timeout,
		] ) );
	}
}
