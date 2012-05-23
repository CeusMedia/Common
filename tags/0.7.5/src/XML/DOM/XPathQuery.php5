<?php
/**
 *	Evaluator for XPath Queries.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		$Id$
 */
/**
 *	Evaluator for XPath Queries.
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@extends		ADT_OptionObject
 *	@uses			Net_CURL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.01.2006
 *	@version		$Id$
 */
class XML_DOM_XPathQuery extends ADT_OptionObject
{
	/**	@var		DOMDocument	$document		DOM Document Object */
	var $document	= NULL;
	/**	@var		DOMXPath	$xPath			DOM XPath Object */
	var $xPath		= NULL;

	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
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
	 *	@return		string
	 */
	public function evaluate( $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
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
	 */
	public function getDocument()
	{
		if( !$this->document )
			throw new Exception( 'No XML loaded yet.' );
		return $this->document;
	}
	/**
	 *	Loads XML from File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to load XML from
	 *	@return		bool
	 */
	public function loadFile( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'XML File "'.$fileName.'" is not existing.' );
		$this->document	= new DOMDocument();
		$this->document->load( $fileName );
		$this->xPath	= new DOMXpath( $this->document );
		return true;
	}
	
	/**
	 *	Loads XML from URL.
	 *	@access		public
	 *	@param		string		$url			URL to load XML from
	 *	@return		bool
	 *	@todo		Error Handling
	 */
	public function loadUrl( $url )
	{
		$cURL	= new Net_CURL( $url );
		foreach( $this->getOptions() as $key => $value )
			$cURL->setOption( "CURLOPT_".strtoupper( $key ), $value ) ;
		$xml = $cURL->exec();
		if( !$xml )
			throw new Exception( 'No XML found for URL "'.$url.'".' );
		$this->loadXml( $xml );
		return true;
	}
	
	/**
	 *	Loads XML into XPath Parser.
	 *	@access		public
	 *	@return		void
	 */
	public function loadXml( $xml )
	{
		$this->document	= new DOMDocument();
		$this->document->loadXml( $xml );
		$this->xPath	= new DOMXPath( $this->document );
	}
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	public function query( $path, $node = NULL )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
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
	 *	@see		http://tw.php.net/manual/de/function.dom-domxpath-registernamespace.php
	 */
	public function registerNamespace( $prefix, $namespace )
	{
		if( !$this->xPath )
			throw new Exception( 'No XML loaded yet.' );
		return $this->xPath->registerNamespace( $prefix, $namespace );
	}
}
?>