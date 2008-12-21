<?php
/**
 *	Generic Server for Service Points.
 *	You can extends this Class to set up your own Service Point / Environment.
 *	@package		net.service
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_Service_Point
 *	@uses			Net_Service_Handler
 *	@uses			UI_HTML_Service_Index
 *	@uses			UI_HTML_Service_Test
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.12.2008
 *	@version		0.1
 */
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.ui.DevOutput' );
/**
 *	Generic Server for Service Points.
 *	You can extends this Class to set up your own Service Point / Environment.
 *	@package		net.service
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Net_Service_Point
 *	@uses			Net_Service_Handler
 *	@uses			UI_HTML_Service_Index
 *	@uses			UI_HTML_Service_Test
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.12.2008
 *	@version		0.1
 */
class Net_Service_Server
{
	/**	@var		string			$basePath		Path to Service Index */
	protected $basePath	= "";

	/**	@var		array			$formats		Available Service Formats, can be overwritten */
	public $formats		= array(
		'atom',
		'json',
		'php',
		'rss',
		'txt',
		'wddx',
		'xml',
	);

	protected $servicePoint;
	protected $fileName;
	protected $cacheFile;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$path			Service Point Path, eg. 'public/'
	 *	@param		string			$basePath		Path to Service Point, eg. 'services/'
	 *	@param		array			$formats		Service Response Formats, overwrites preset Formats
	 *	@return		void
	 */
	public function __construct( $path, $basePath = "", $formats = array() )
	{
		error_reporting( 1023 );

		$this->basePath	= $basePath;
		if( is_array( $formats ) && $formats )
			$this->formats	= $formats;
			
		$parts	= array();
		foreach( explode( "/", $basePath.$path ) as $part )
			if( trim( $part ) )
				$parts[]	= $part;

		chDir( str_repeat( "../", count( $parts ) ) );
		$this->path	= implode( "/", $parts )."/";

		$this->fileName			= $this->path."services.xml";
		$this->cacheFile		= $this->path."services.cache";
		$this->servicePoint		= $this->loadServicePoint();
		$this->handleRequest();
	}

	/**
	 *	Handles Request  by either showing Service Index, testing Service oder running Service.
	 *	@access		protected
	 *	@return		void
	 */
	protected function handleRequest()
	{
		$requestHandler	= new Net_HTTP_Request_Receiver;
		$subfolderLevel	= substr_count( $this->path, "/" );

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
		import( 'de.ceus-media.net.service.Point' );
		return new Net_Service_Point( $this->fileName, $this->cacheFile );
	}
	
	/**
	 *	Builds Service Index View, can be overwritten.
	 *	@access		protected
	 *	@return		string
	 */
	protected function runIndex( $subfolderLevel )
	{
		import( 'de.ceus-media.ui.html.service.Index' );
		$index		= new UI_HTML_Service_Index( $this->servicePoint, $this->formats );
		$index->setTableClass( 'services list' );
		$index->setTemplate( $this->basePath.".index/templates/index.phpt" );
		return $index->buildContent( $subfolderLevel, $this->basePath );
	}
	
	/**
	 *	Runs Service and returns Response, can be overwritten.
	 *	@access		protected
	 *	@return		mixed
	 */
	protected function runService( $requestHandler )
	{
		import( 'de.ceus-media.net.service.Handler' );
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
		import( 'de.ceus-media.ui.html.service.Test' );
		$test		= new UI_HTML_Service_Test( $this->servicePoint );
		$test->setTableClass( 'services list' );
		$test->setTemplate( $this->basePath.".index/templates/test.phpt" );
		return $test->buildContent( $requestHandler, $subfolderLevel, $this->basePath );
	}
}
?>