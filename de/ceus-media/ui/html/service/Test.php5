<?php
/**
 *	@package		ui.html.service
 *	@todo			Code Doc
 */
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.net.Reader' );
import( 'de.ceus-media.adt.json.Formater' );
import( 'de.ceus-media.alg.StringTrimmer' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.Tabs' );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.VariableDumper' );
import( 'de.ceus-media.ui.html.exception.TraceViewer' );
import( 'de.ceus-media.xml.Element' );
import( 'de.ceus-media.xml.dom.Formater' );
class UI_HTML_Service_Test
{
	protected $username;
	protected $password;

	public function __construct( Net_Service_Point $servicePoint )
	{
		$this->servicePoint		= $servicePoint;
	}
	
	public function buildContent( $request, $subfolderLevel = 0, $basePath = "" )
	{
		$service	= $request['test'];
		
		$preferred	= $this->servicePoint->getDefaultServiceFormat( $service );
		$format		= isset( $request['parameter_format'] ) ? $request['parameter_format'] : $preferred;

		
		$requestUrl		= $this->getRequestUrl( $request );
		$testUrl		= $this->getTestUrl( $request );

		$stopwatch	= new StopWatch();
		try
		{
			$response	= $this->getResponse( $requestUrl, $format );
		}
		catch( Exception $e )
		{
			$response	= UI_HTML_Exception_TraceViewer::buildTrace( $e, 2 );
		}
		$time			= $stopwatch->stop( 6, 0 );

		//  --  INFORMATION FOR TEMPLATE  --  //
		$title			= $this->servicePoint->getTitle();							//  Service Title
		$class			= $this->servicePoint->getServiceClass( $service );			//  Service Class Name
		$description	= $this->servicePoint->getServiceDescription( $service );	//  Service Description
		$defaultFormat	= $this->servicePoint->getDefaultServiceFormat( $service );	//  Service Format by default
		$parameters		= $this->getParameterFields( $service, $format, $request );

		$trace		= "";
		$data		= "";
		$exception	= "";
		$this->evaluateResponse( $format, $response, $data, $exception, $trace );

		$tabs	= array();
		if( $data )
			$tabs['Data']	= $data;
		if( $exception )
			$tabs['Exception']	= $exception;
		$tabs['Response']	= "<xmp>".$response."</xmp>";
		$tabs['Request']	= UI_VariableDumper::dump( $request->getAll(), 1, 0 );
		if( $trace )
			$tabs['Trace']	= $trace;

		$tabs	= new UI_HTML_Tabs( $tabs, 'tabs-office' );
		return require_once( $this->template );
	}

	private function getBaseUrl()
	{
		if( $referrer = getEnv( 'HTTP_REFERER' ) )
			extract( parse_url( $referrer ) );
		else
		{
			$path	= dirname( getEnv( 'REQUEST_URI' ) );
			$path	= preg_replace( "@^(.*)/?$@", "\\1/", $path );
			$host	= getEnv( 'HTTP_HOST' );
			$scheme	= getEnv( 'HTTPS' ) ? "https" : "http";
		}
		$url	= $scheme."://".$host.$path;
		return $url;
	}

	private function getParameterFields( $service, $format, $request )
	{
		$parameters	= $this->servicePoint->getServiceParameters( $service );
		$formats	= $this->servicePoint->getServiceFormats( $service );
		asort( $formats );

		//  --  TYPES FOR FILTER  --  //
		if( !$format )
			$format	= $this->servicePoint->getDefaultServiceFormat( $service );
		$optFormat	= array_combine( $formats, $formats );
		$optFormat['_selected']	= $format;

		$list	= array(
			array(
				'label'	=> "Format of Response",
				'rules'	=> "",
				'input'	=> UI_HTML_Elements::Select( 'parameter_format', $optFormat, 's' )
			)
		);

		foreach( $parameters as $parameter => $rules )
		{
			$ruleList	= array();
			if( $rules )
			{
				foreach( $rules as $ruleKey => $ruleValue )
				{
					if( $ruleKey == "title" )
						continue;
					if( $ruleKey == "mandatory" )
						$ruleValue = $ruleValue ? "yes" : "no";
					$spanKey	= UI_HTML_Tag::create( "span", $ruleKey.":", array( 'class' => "key" ) );
					$spanValue	= UI_HTML_Tag::create( "span", htmlspecialchars( $ruleValue ), array( 'class' => "value" ) );
					$ruleList[]	= $spanKey." ".$spanValue;
				}
			}
			$divRules	= UI_HTML_Tag::create( "span", " (".implode( ", ", $ruleList ).")", array( 'class' => "rules" ) );
			$label	= isset( $rules['title'] ) ? UI_HTML_Elements::Acronym( $parameter, $rules['title'] ) : $parameter;
			$rules	= count( $ruleList ) ? $divRules : "";
			$value	= isset( $request["parameter_".$parameter] ) ? $request["parameter_".$parameter] : NULL;
			$list[]	= array(
				'label' => $label,
				'rules'	=> $rules,
				'input'	=> UI_HTML_Elements::Input( "parameter_".$parameter, $value, 'l' )
			);
		}
		return $list;
	}

	private function getParametersFromRequest( $request )
	{
		$pairs		= is_a( $request, "ADT_List_Dictionary" ) ? $request->getAll() : $request;
		$parameters	= array();
		foreach( $pairs as $key => $value )
			if( preg_match( "@^parameter_@", $key ) )
				$parameters[preg_replace( "@^parameter_@", "", $key)]	= $value;
		return $parameters;
	}

	private function getRequestUrl( $request )
	{
		$parameters	= $this->getParametersFromRequest( $request );
		$query	= http_build_query( $parameters, '', "&" );

		$url	= $this->getBaseUrl();
		$url	.= "?service=".$request['test']."&".$query;
		return $url;
	}

	private function getResponse( $url, $format )
	{
		$reader		= new Net_Reader( $url );
		$reader->setBasicAuth( $this->username, $this->password );
		$response	= $reader->read();
		return $response;
	}

	private function buildExceptionTab( $type, $message )
	{
		$type		= preg_replace( "@([a-z])([A-Z])@", "\\1 \\2", $type );
		$message	= $message;
		$exception	= "<em>".$type."</em>: <b>".$message."</b>";
		return $exception;
	}
	
	private function evaluateResponse( $format, &$response, &$data, &$exception, &$trace  )
	{
		switch( $format )
		{
			case "json":
				$structure	= json_decode( $response, TRUE );
				if( $structure['status'] == "exception" )
				{
					$e			= $structure['data'];
					$trace		= $e['trace'];
					$exception	= $this->buildExceptionTab( $e['type'], $e['message'] );
				}
				else
					$data	= dumpVar( $structure['data'], 1, 0 );
				$response	= ADT_JSON_Formater::format( $response );
				$response	= $this->trimResponseLines( $response, 120 );
				break;
			case 'php':
				$structure	= unserialize( $response );
				if( $structure['status'] == "exception" )
				{
					$e			= $structure['data'];
					$trace		= $e['trace'];
					$exception	= $this->buildExceptionTab( $e['type'], $e['message'] );
				}
				else
					$data	= dumpVar( $structure['data'], 1, 0 );
				break;
			case "wddx":
				$structure	= wddx_deserialize( $response );
				if( $structure['status'] == "exception" )
				{
					$e			= $structure['data'];
					$trace		= $e['trace'];
					$exception	= $this->buildExceptionTab( $e['type'], $e['message'] );
				}
				else
					$data	= dumpVar( $structure['data'] );
#				$response	= XML_DOM_Formater::format( $response );
				$response	= $this->trimResponseLines( $response );
				break;
			case "xml":
				$xml	= new XML_Element( $response );
				if( $xml->status->getValue() == "exception" )
				{
					$trace		= $xml->data->trace->getValue();
					$type		= $xml->data->type->getValue();
					$message	= $xml->data->message->getValue();
					$exception	= $this->buildExceptionTab( $type, $message );
				}
				else
					$data	= UI_VariableDumper::dump( $xml->data, 1, 1 );
				$response	= $this->trimResponseLines( $response, 120 );
				break;
			case "atom":
			case "rss":
				break;
			case "txt":
				$data	= nl2br( $response );
				break;
			case "html":
				$data	= $response;
				break;
		}
	}

	private function trimResponseLines( $response, $length = 100 )
	{
		$lines	= array();
		foreach( explode( "\n", $response ) as $line )
			$lines[]	= Alg_StringTrimmer::trimCentric( $line, $length );
		return implode( "\n", $lines );
	}

	private function getTestUrl( $request )
	{
		$parameters	= is_a( $request, "ADT_List_Dictionary" ) ? $request->getAll() : $request;
		unset( $parameters['test'] );
		unset( $parameters['call'] );
		$query	= http_build_query( $parameters, '', "&" );

		$url	= $this->getBaseUrl();
		$url	.= "?test=".$request['test']."&".$query;
		return $url;
	}
	
	public function setTemplate( $fileName )
	{
		$this->template	= $fileName;
	}

	public function setTableClass( $className )
	{
		$this->tableClass	= $className;
	}
	
	public function setAuth( $username, $password )
	{
		$this->username	= $username;
		$this->password	= $password;
	}
}
?>