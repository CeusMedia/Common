<?php
/**
 *	Generic Server for Service Points.
 *	You can extends this Class to set up your own Service Point / Environment.
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
 *	@package		Net.Service
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 */
/**
 *	Generic Server for Service Points.
 *	You can extends this Class to set up your own Service Point / Environment.
 *	@category		cmClasses
 *	@package		Net.Service
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_Service_Point
 *	@uses			Net_Service_Handler
 *	@uses			UI_HTML_Service_Index
 *	@uses			UI_HTML_Service_Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.5
 *	@version		$Id$
 *	@deprecated		moved to cmModules::ENS
 *	@todo			to be removed in 0.7.3
 */
class Net_Service_Server
{
	/**	@var		array			$formats		Available Service Formats, can be overwritten */
	protected $formats					= array(
		'atom',
		'json',
		'php',
		'rss',
		'txt',
		'wddx',
		'xml',
	);

	/**	@var		string			$fileName		File Name of Service Definition */
	protected $fileName				= "services.xml";

	/**	@var		string			$cacheFile		Cache File Name of Service Definition */
	protected $cacheFile			= "services.cache";
	
	/**	@var		string			$templateIndex	Template of Index */
	protected $templateIndex		= ".index/templates/index.phpt";
	
	/**	@var		string			$templateTest	Template of Test */
	protected $templateTest			= ".index/templates/test.phpt";

	/**	@var		string			$basePath		Path to Service Index */
	protected $basePath				= "";

	protected $servicePoint;

	protected $pointFolders			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$classPath		Working Path of Application, eg. '../'
	 *	@param		string			$pointPath		Service Point Path, eg. 'public/'
	 *	@param		array			$formats		Service Response Formats, overwrites preset Formats
	 *	@return		void
	 */
	public function __construct( $classPath = "", $pointPath = "", $formats = array() )
	{
		error_reporting( E_ALL );
		if( is_array( $formats ) && $formats )
			$this->formats	= $formats;

		$this->realizePaths( $classPath, $pointPath );
		$this->loadServicePoint();
		$this->handleRequest();
	}

	protected function realizePaths( $classPath, $pointPath )
	{
		foreach( explode( "/", $pointPath ) as $part )
		{
			if( !trim( $part ) )
				continue;
			$this->pointFolders[]	= $part; 
			chDir( ".." );
		}
		$this->basePath		= getCwd()."/";
		$this->pointPath	= $pointPath;
		$this->pointPath	.= preg_match( '@/$@', $this->pointPath ) ? "" : "/";		//  correct Path

		if( $classPath )
			chDir( $classPath );
	}

	/**
	 *	Handles Request  by either showing Service Index, testing Service oder running Service.
	 *	@access		protected
	 *	@return		void
	 */
	protected function handleRequest()
	{
		$requestHandler	= new Net_HTTP_Request_Receiver;
		$subfolderLevel	= count( $this->pointFolders );

		if( $requestHandler->has( 'service' ) )											//  run Service
			$this->runService( $requestHandler );
		else if( $requestHandler->has( 'test' ) )										//  test Service
			echo $this->runTest( $requestHandler, $subfolderLevel );
		else																			//  index Services
			echo $this->runIndex( $subfolderLevel );
	}

	/**
	 *	Loads Service Point Class and builds Instance, can be overwritten.
	 *	@access		protected
	 *	@return		Net_Service_Point
	 */
	protected function loadServicePoint()
	{
		$fileName	= $this->basePath.$this->pointPath.$this->fileName;					//  File Path of Service Definition
		$fileCache	= $this->basePath.$this->pointPath.$this->cacheFile;				//  File Path of Service Definition Cache File
		$this->servicePoint	= new Net_Service_Point( $fileName, $fileCache );			//  start Service Point
	}
	
	/**
	 *	Builds Service Index View, can be overwritten.
	 *	@access		protected
	 *	@return		string
	 */
	protected function runIndex( $subfolderLevel )
	{
		$index		= new UI_HTML_Service_Index( $this->servicePoint, $this->formats );
		$index->setTableClass( 'services list' );
		$index->setTemplate( $this->basePath.$this->templateIndex );
		return $index->buildContent( $subfolderLevel, "" );
	}
	
	/**
	 *	Runs Service and returns Response, can be overwritten.
	 *	@access		protected
	 *	@return		mixed
	 */
	protected function runService( $requestHandler )
	{
		$handler	= new Net_Service_Handler( $this->servicePoint, $this->formats );
		return $handler->handle( $requestHandler );
	}

	/**
	 *	Builds Service Test View, can be overwritten.
	 *	@access		protected
	 *	@return		string
	 */
	protected function runTest( $requestHandler, $subfolderLevel )
	{
		$test		= new UI_HTML_Service_Test( $this->servicePoint );
#		$test->setTableClass( 'services list' );
		$test->setTemplate( $this->basePath.$this->templateTest );
		return $test->buildContent( $requestHandler, $subfolderLevel, "");
	}
}
?>