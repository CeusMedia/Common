<?php
import( 'de.ceus-media.net.Reader' );
import( 'de.ceus-media.adt.json.Formater' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.ui.html.exception.TraceViewer' );
class UI_HTML_Service_Test
{
	public function __construct( Net_Service_Point $servicePoint )
	{
		$this->servicePoint		= $servicePoint;
	}
	
	public function buildContent( $request )
	{
		$service	= $request['test'];
		$format		= isset( $request['parameter_format'] ) ? $request['parameter_format'] : NULL;
		$url		= "";
		$response	= "";
		$parameters	= $this->getParameterFields( $service, $format, $request );
		$url		= $this->getRequestUrl( $request );
		$response	= $this->getResponse( $url, $format );
		return require_once( $this->template );
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

	private function getRequestUrl( $request )
	{
		$pairs		= is_a( $request, "ADT_List_Dictionary" ) ? $request->getAll() : $request;
		$parameters	= array();
		foreach( $pairs as $key => $value )
			if( preg_match( "@^parameter_@", $key ) )
				$parameters[preg_replace( "@^parameter_@", "", $key)]	= $value;

		$query	= http_build_query( $parameters, '', "&" );

		$url	= parse_url( getEnv( 'HTTP_REFERER' ) );
		$url	= $url['scheme']."://".$url['host'].$url['path']."?service=".$request['test']."&".$query;
		return $url;
	}

	private function getResponse( $url, $format )
	{
		$response	= Net_Reader::readUrl( $url );
		$exception	= @unserialize( $response );
		if( $exception && is_a( $exception, "Exception" ) )
			return UI_HTML_Exception_TraceViewer::buildTrace( $exception );

		if( $format == "json" )
		{
			$response	= "<xmp>".ADT_JSON_Formater::format( stripslashes( $response ) )."</xmp>";
		}
		else if( $format == "php" )
		{
			if( @unserialize( $response ) )
			{
				ob_start();
				print_m( unserialize( $response ) );
				$response	= ob_get_clean();
			}
		}
		else if( $format == "xml" )
		{
			$response	= "<xmp>".$response."</xmp>";
		}
		return $response;
	}
	
	public function setTemplate( $fileName )
	{
		$this->template	= $fileName;
	}

	public function setTableClass( $className )
	{
		$this->tableClass	= $className;
	}
}
?>