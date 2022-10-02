<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Evaluator for XPath Queries.
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\ADT\OptionObject;
use CeusMedia\Common\Net\Reader as NetReader;
use CeusMedia\Common\XML\Validator;
use DOMDocument;
use DOMNodeList;
use DOMXpath;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Evaluator for XPath Queries.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class XPathQuery extends OptionObject
{
	/**	@var		DOMDocument|NULL	$document		DOM Document Object */
	public $document	= NULL;

	/**	@var		DOMXPath|NULL		$xPath			DOM XPath Object */
	public $xPath		= NULL;

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( "followlocation", 1 );
		$this->setOption( "header", 1 );
		$this->setOption( "ssl_verifypeer", 1 );
	}

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		DOMNodeList|FALSE|mixed
	 *	@throws		RuntimeException		if not XML has been loaded, yet
	 */
	public function evaluate( string $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new RuntimeException( 'No XML loaded yet.' );
		if( $node )
			$nodeList	= $this->xPath->evaluate( $path, $node );
		else
			$nodeList	= $this->xPath->evaluate( $path );
		return $nodeList;
	}

	/**
	 *	Returns DOM Document of loaded XML File.
	 *	@access		public
	 *	@return		DOMDocument
	 *	@throws		RuntimeException		if not XML has been loaded, yet
	 */
	public function getDocument(): DOMDocument
	{
		if( !$this->document )
			throw new RuntimeException( 'No XML loaded yet.' );
		return $this->document;
	}
	/**
	 *	Loads XML from File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to load XML from
	 *	@return		bool
	 */
	public function loadFile( string $fileName ): bool
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'XML File "'.$fileName.'" is not existing.' );
		$this->document	= new DOMDocument();
		$this->document->load( $fileName );
		$this->xPath	= new DOMXpath( $this->document );
		return TRUE;
	}

	/**
	 *	Loads XML from URL.
	 *	@access		public
	 *	@param		string		$url			URL to load XML from
	 *	@return		bool
	 *	@todo		Error Handling
	 */
	public function loadUrl( string $url ): bool
	{
		$options	= [];
		foreach( $this->getOptions() as $key => $value )
			$options["CURLOPT_".strtoupper( $key )]	= $value;
		$xml	= NetReader::readUrl( $url, $options );
		if( !$xml )
			throw new RuntimeException( 'No XML found for URL "'.$url.'".' );
		$this->loadXml( $xml );
		return TRUE;
	}

	/**
	 *	Loads XML into XPath Parser.
	 *	@access		public
	 *	@return		void
	 */
	public function loadXml( string $xml )
	{
		$this->document	= new DOMDocument();
		$validator	= new Validator();
		if( !$validator->validate( $xml ) ){
			$message	= $validator->getErrorMessage();
			throw new InvalidArgumentException( 'XML is invalid ('.$message.')' );
		}
		$this->document->loadXml( $xml );
		$this->xPath	= new DOMXPath( $this->document );
	}

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		DOMNodeList|FALSE|mixed
	 *	@throws		RuntimeException		if not XML has been loaded, yet
	 */
	public function query( string $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new RuntimeException( 'No XML loaded yet.' );
		if( $node )
			$nodeList	= $this->xPath->query( $path, $node );
		else
			$nodeList	= $this->xPath->query( $path );
		return $nodeList;
	}

	/**
	 *	Registers a Namespace for a Prefix.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of Namespace
	 *	@param		string		$namespace		Namespace of Prefix
	 *	@return		bool
	 *	@throws		RuntimeException		if not XML has been loaded, yet
	 *	@see		http://tw.php.net/manual/de/function.dom-domxpath-registernamespace.php
	 */
	public function registerNamespace( string $prefix, string $namespace ): bool
	{
		if( !$this->xPath )
			throw new RuntimeException( 'No XML loaded yet.' );
		return $this->xPath->registerNamespace( $prefix, $namespace );
	}
}
