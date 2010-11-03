<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		UI.HTML.Service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		UI.HTML.Service
 *	@uses			Alg_Text_Trimmer
 *	@uses			Alg_Time_Clock
 *	@uses			Net_Reader
 *	@uses			UI_HTML_Tabs
 *	@uses			XML_Element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Doc
 */
class UI_HTML_Service_Test
{
	protected $username			= NULL;
	protected $password			= NULL;
	protected $template;
	protected $servicePoint;
	protected $headers			= array();

	public function __construct( Net_Service_Point $servicePoint )
	{
		$this->servicePoint		= $servicePoint;
	}
	
	public function buildContent( $request, $subfolderLevel = 0 )
	{
		$service	= $request['test'];
		
		$basePath	= str_repeat( "../", $subfolderLevel );
		
		$preferred	= $this->servicePoint->getDefaultServiceFormat( $service );
		$format		= isset( $request['parameter_format'] ) ? $request['parameter_format'] : $preferred;

		
		$requestUrl		= $this->getRequestUrl( $request );
		$testUrl		= $this->getTestUrl( $request );

		$clock			= new Alg_Time_Clock();
		try
		{
			$response	= $this->getResponse( $requestUrl, $format );
		}
		catch( Exception $e )
		{
			$response	= UI_HTML_Exception_TraceViewer::buildTrace( $e, 2 );
		}
		$time			= $clock->stop( 6, 0 );

		//  --  INFORMATION FOR TEMPLATE  --  //
		$title			= $this->servicePoint->getTitle();							//  Service Title
		$class			= $this->servicePoint->getServiceClass( $service );			//  Service Class Name
		$description	= $this->servicePoint->getServiceDescription( $service );	//  Service Description
		$defaultFormat	= $this->servicePoint->getDefaultServiceFormat( $service );	//  Service Format by default
		$parameters		= $this->getParameterFields( $service, $format, $request );
		$filters		= $this->servicePoint->getServiceFilters( $service );		//  Service Filter List

		$trace		= "";
		$data		= "";
		$exception	= "";
		$this->evaluateResponse( $format, $response, $data, $exception, $trace );


		if( strlen( $data ) > ( 1024 * 1024 ) )
			$data	= "<em><small>Response larger than 1MB</small></em>";

		if( strlen( $response ) > ( 1024 * 1024 ) )
		{
			$response	= Alg_Text_Trimmer::trimCentric(  $response, 200 );
			$response	= "Response larger than 1MB\n".$response;
		}


		$tabs	= array();
		if( $data )
			$tabs['Data']	= $data;
		if( $exception )
		{
			$tabs['Exception']	= $exception;
			if( $trace )
				$tabs['Trace']	= "<xmp>".$trace."</xmp>";
		}
		$tabs['Response']	= "<xmp>".$response."</xmp>";
		$tabs['Response Headers']	= UI_VariableDumper::dump( $this->headers );
		$tabs['Request']	= UI_VariableDumper::dump( $request->getAll(), 1, 0 );

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

		if( $this->servicePoint->getServiceRoles( $service ) )
		{
			if( !array_key_exists( "auth_username", $parameters ) )
				$parameters['auth_username']	= array(
					'mandatory'	=> 1,
					'preg'		=> '@^\w+$@',
				);
			if( !array_key_exists( "auth_password", $parameters ) )
				$parameters['auth_password']	= array(
					'mandatory'	=> 1,
					'preg'		=> '@^\S+$@',
				);
		}

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
			$mandatory	= FALSE;
			$type		= isset( $rules['type'] ) ? "<small><em>".$rules['type']."</em></small>&nbsp;" : "";
			$ruleList	= $this->buildParameterRuleList( $rules );
			$label	= isset( $rules['title'] ) ? UI_HTML_Elements::Acronym( $parameter, $rules['title'] ) : $parameter;
			$value	= isset( $request["parameter_".$parameter] ) ? $request["parameter_".$parameter] : NULL;
			$label	= $type.$label;
			if( !$mandatory )
				$label	= "[".$label."]";
			$divRules	= UI_HTML_Tag::create( "span", " (".implode( ", ", $ruleList ).")", array( 'class' => "rules" ) );
			$ruleList	= count( $ruleList ) ? $divRules : "";

			$input	= UI_HTML_Elements::Input( "parameter_".$parameter, $value, 'l' );
			if( array_key_exists( "type", $rules ) )
			{
				if( $rules['type']	== "bool" )
					$input	= UI_HTML_FormElements::CheckBox( "parameter_".$parameter, 1, $value );
			}
			$list[]	= array(
				'label' => $label,
				'rules'	=> $ruleList,
				'input'	=> $input,
			);
		}
		return $list;
	}

	protected function buildParameterRuleList( $rules )
	{
		$ruleList	= array();
		foreach( $rules as $ruleKey => $ruleValue )
		{
			if( $ruleKey == "title" )
				continue;
			if( $ruleKey == "filters" )
				$ruleValue	= implode( ", ", $ruleValue );
			if( $ruleKey == "preg" )
				$ruleValue	= $ruleValue ? $ruleValue : "-";
			if( $ruleKey == "mandatory" )
			{
				$mandatory	= $ruleValue;
				$ruleValue	= $ruleValue ? "yes" : "no";
			}
			$spanKey	= UI_HTML_Tag::create( "span", $ruleKey.":", array( 'class' => "key" ) );
			$spanValue	= UI_HTML_Tag::create( "span", htmlspecialchars( $ruleValue ), array( 'class' => "value" ) );
			$ruleList[]	= $spanKey." ".$spanValue;
		}
		return $ruleList;
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
		
		$this->headers	= array();
		$headers	= $reader->getHeader();
		foreach( $headers as $key => $values )
			$this->headers[$key]	= array_pop( $values );

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
					$trace		= isset( $e['trace'] ) ? $e['trace'] : "";
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
					$trace		= isset( $e['trace'] ) ? $e['trace'] : "";
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
					$trace		= isset( $e['trace'] ) ? $e['trace'] : "";
					$exception	= $this->buildExceptionTab( $e['type'], $e['message'] );
				}
				else
					$data	= dumpVar( $structure['data'] );
				$response	= XML_DOM_Formater::format( $response );
				$response	= $this->trimResponseLines( $response, 120 );
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
			$lines[]	= Alg_Text_Trimmer::trimCentric( $line, $length );
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
	
	public function setAuth( $username, $password )
	{
		$this->username	= $username;
		$this->password	= $password;
	}
}
?>