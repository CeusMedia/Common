<?php
/**
 *	@package		ui.html.service
 *	@todo			Code Doc
 */
import( 'de.ceus-media.net.Reader' );
import( 'de.ceus-media.adt.json.Formater' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.html.exception.TraceViewer' );
class UI_HTML_Service_Test
{
	protected $username;
	protected $password;

	public function __construct( Net_Service_Point $servicePoint )
	{
		$this->servicePoint		= $servicePoint;
	}
	
	public function buildContent( $request )
	{
		$service	= $request['test'];
		
		$preferred	= $this->servicePoint->getDefaultServiceFormat( $service );
		$format		= isset( $request['parameter_format'] ) ? $request['parameter_format'] : $preferred;
		$url		= "";
		$response	= "";
		
		$requestUrl		= $this->getRequestUrl( $request );
		$testUrl		= $this->getTestUrl( $request );
		try
		{
			$response	= $this->getResponse( $requestUrl, $format );
			$parameters	= $this->getParameterFields( $service, $format, $request );
		}
		catch( Exception $e )
		{
			$response	= UI_HTML_Exception_TraceViewer::buildTrace( $e );
			$parameters	= array();
		}
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
					if( $ruleKey == "mandatory" )
						$ruleValue = $ruleValue ? "yes" : "no";
					$ruleList[]	= $ruleKey.": ".htmlspecialchars( $ruleValue );
				}
			}
			$rules	= count( $ruleList ) ? " (".implode( ", ", $ruleList ).")" : "";
			$value	= isset( $request["parameter_".$parameter] ) ? $request["parameter_".$parameter] : NULL;	
			$list[]	= array(
				'label' => $parameter,
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
		if( $format == "json" )
		{
			$response	= "<xmp>".ADT_JSON_Formater::format( stripslashes( $response ) )."</xmp>";
		}
		else if( $format == "php" )
		{
			$response	= unserialize( $response );
			if( $response && is_a( $response, "Exception" ) )
				return UI_HTML_Exception_TraceViewer::buildTrace( $response );
			ob_start();
			print_m( $response );
			$response	= ob_get_clean();
		}
		else if( $format == "xml" )
		{
			$response	= "<xmp>".$response."</xmp>";
		}
		else if( $format == "wddx" )
		{
			$response	= wddx_deserialize( $response );
			if( $response && is_a( $response, "Exception" ) )
				return UI_HTML_Exception_TraceViewer::buildTrace( $response );
			ob_start();
			print_m( $response );
			$response	= ob_get_clean();
		}
		return $response;
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