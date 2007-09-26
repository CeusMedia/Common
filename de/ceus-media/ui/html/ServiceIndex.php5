<?php
import( 'de.ceus-media.service.ServiceHandler' );
/**
 *	Service Handler which indexes with HTML Output.
 *	@package		service
 *	@implements		ServiceHandler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Handler which indexes with HTML Output.
 *	@package		service
 *	@implements		ServiceHandler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
class ServiceIndex implements ServiceHandler
{
	/**	@param		array				List of available Response Formats */
	protected $formats		= array();
	/**	@param		ServicePoint		Intance of a Service Point */
	protected $servicePoint	= NULL;
	/**	@param		string				CSS Class of Template in Template */
	protected $tableClass;
	/**	@param		string				File Name of Template for Index */
	protected $template;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		ServicePoint	$servicePoint		Services Class
	 *	@param		array			$availableFormats	Available Response Formats
	 *	@return		void
	 */
	public function __construct( ServicePoint $servicePoint, $availableFormats )
	{
		$this->servicePoint		= $servicePoint;
		$this->availableFormats	= $availableFormats;
	}

	/**
	 *	Shows Index Page of Service.
	 *	@access		protected
	 *	@return		string		HTML of Service Index
	 */
	protected function buildIndex()
	{
		$title		= $this->servicePoint->getServicesTitle();					//  Services Title
		$syntax		= $this->servicePoint->getServicesSyntax();					//  Services Syntax
		$table		= $this->getServiceTable();									//  Services Table
		$list		= $this->getServiceList();									//  Services List
		$examples	= $this->getServiceExamples();								//  Services Examples
		return require_once( $this->template );
	}
	
	/**
	 *	Return Service Examples.
	 *	@access		public
	 *	@return		string
	 */
	protected function getServiceExamples()
	{	
		$examples	= "";
/*		$examples	= array();
		$list		= $this->servicePoint->getServiceExamples();
		foreach( $list as $entry )
			$examples[$entry['service']]	= "<dt><a href='./?service=".$entry['service']."&format=".$entry['format']."'>?service=".$entry['service']."&format=".$entry['format']."</a></dt><dd>".$entry['description']."</dd>";
		natcasesort( $examples );
		$examples	= "<dl>".implode( "", $examples )."</dl>";
*/		return $examples;
	}

	/**
	 *	Return Service List.
	 *	@access		public
	 *	@return		string
	 */
	protected function getServiceList()
	{	
		$services	= array();
		$list		= $this->servicePoint->getServices();
		foreach( $list as $entry )
			$services[]	= "<li>".$entry."</li>";
		$services	= "<ul>".implode( "", $services )."</ul>";	
		return $services;
	}

	/**
	 *	Return HTML Table of Services with their available Formats.
	 *	@access		public
	 *	@return		string		HTML of Service Table
	 */
	protected function getServiceTable()
	{
		$rows		= array();
		$services	= $this->servicePoint->getServices();
		$heads		= array();
		
		$heads	= array( "<th>Service</th>" );
		$cols	= array( "<col width='30%'/>" ); 
		foreach( $this->availableFormats as $format )
		{
			$cols[]		= "<col width='".round( ( 100 - 30 ) / count( $this->availableFormats ), 0 )."%'/>";
			$heads[]	= "<th>".strtoupper( $format )."</th>";
		}
		$cols	= "<colgroup>".implode( "", $cols )."</colgroup>";
		$heads	= "<tr>".implode( "", $heads )."</tr>";
		foreach( $services as $service )
		{
			$cells		= array();
			$formats	= $this->servicePoint->getServiceFormats( $service );
			foreach( $this->availableFormats as $format )
			{
				if( in_array( $format, $formats ) )
					$cells[]	= "<td class='yes'>+</td>";
				else
					$cells[]	= "<td class='no'>-</td>";
			}
			$row	= "<tr><td>".$service."</td>".implode( "", $cells )."</tr>";
			$rows[]	= $row;
		}
		return "<table class='".$this->tableClass."'>".$cols.$heads.implode( "", $rows )."</table>";
	}
	
	/**
	 *	Handles Request to Service Point by either calling a Service or indexing all Services.
	 *	@access		public
	 *	@param		array|Object	$requestData			Array from Request, containing Service Name and Response Format
	 *	@param		bool			$serializeExceptions	Flag: serialize Exceptions instead of throwing
	 *	@return		void
	 */
	public function handle( $requestData, $serializeExceptions = false )
	{
		if( isset( $requestData['service'] ) )
		{
			try
			{
				$serviceName	= $requestData['service'];
				$responseFormat	= isset( $requestData['format'] ) ? $requestData['format'] : false;
				$response		= $this->servicePoint->callService( $serviceName, $responseFormat );
			}
			catch( Exception $e )
			{
				if( $serializeExceptions )
					die( serialize( $e ) );
				die( $e->getMessage() );
			}
		}
		else
		{
			$response	= $this->buildIndex() ;
		}
		die( $response );
	}

	/**
	 *	Sets CSS Class of Template in Template.
	 *	@access		public
	 *	@param		string		$class			CSS Class of Template in Template
	 *	@return		void
	 */
	public function setTableClass( $class )
	{
		$this->tableClass	= $class;
	}
	
	/**
	 *	File Name of Template for Index.
	 *	@access		public
	 *	@param		string		$template		File Name of Template
	 *	@return		void
	 */
	public function setTemplate( $template )
	{
		$this->template			= $template;
	}
}
?>