<?php
/**
 *	@package		ui.html.service
 *	@todo			Code Doc
 */
import( 'de.ceus-media.ui.html.Elements' );
class UI_HTML_Service_Table
{
	public function __construct( Net_Service_Point $servicePoint, $availableFormats, $tableClass = NULL )
	{
		$this->servicePoint		= $servicePoint;
		$this->availableFormats	= $availableFormats;
		$this->tableClass		= $tableClass;
	}

	/**
	 *	Return HTML Table of Services with their available Formats.
	 *	@access		public
	 *	@return		string			HTML of Service Table
	 */
	public function buildContent()
	{
		$rows		= array();
		$services	= $this->servicePoint->getServices();
		natcasesort( $services );
		$heads		= array();
		
		$heads	= array( "<th>Service</th><th>Parameters</th>" );
		$cols	= array( "<col width='35%'/><col width='35%'/>" ); 
		foreach( $this->availableFormats as $format )
		{
			$cols[]		= "<col width='".round( ( 100 - 70 ) / count( $this->availableFormats ), 0 )."%'/>";
			$label		= UI_HTML_Elements::Acronym( strtoupper( $format ), "show services with response format ".strtoupper( $format ) );
			$heads[]	= "<th style='text-align: center' class='format'><a href='#'>".$label."</a></th>";
		}
		$cols	= "<colgroup>".implode( "", $cols )."</colgroup>";
		$heads	= "<tr>".implode( "", $heads )."</tr>";
		$counter	= 0;
		foreach( $services as $service )
		{
			$counter ++;
			//  --  FORMATS  --   //
			$cells		= array();
			$formats	= $this->servicePoint->getServiceFormats( $service );
			$default	= $this->servicePoint->getDefaultServiceFormat( $service );
			foreach( $this->availableFormats as $format )
			{
				if( $format == $default )
					$cells[]	= "<td class='preferred'><span class='".$format."'>default</span></td>";
				else if( in_array( $format, $formats ) )
					$cells[]	= "<td class='yes'><span class='".$format."'>yes</span></td>";
				else
					$cells[]	= "<td class='no'>no</td>";
			}
						
			//  --  PARAMETERS  --   //
			$parameterList	= array();
			$parameters	= $this->servicePoint->getServiceParameters( $service );
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
				$rules	= implode( "<br/>", $ruleList );
				if( $rules )
					$parameter	= $parameter.'<div class="rules">'.$rules.'</div>';
				$parameterList[]	= UI_HTML_Elements::ListItem( $parameter );
			}
			$parameters		= UI_HTML_Elements::unorderedList( $parameterList );

			$linkService	= UI_HTML_Tag::create( "a", $service, array( 'href' => "?service=".$service, 'title' => "Run this service" ) );
			$imageTest		= UI_HTML_Tag::create( "span", NULL, array( 'class' => 'linkTest', 'title' => 'Test this service' ) );
			$linkTest		= UI_HTML_Elements::Link( "?test=".$service, $imageTest );

			$serviceLink	= '<div class="serviceName">'.$linkTest.$linkService.'</div>';
			$serviceClass	= '<div class="className">'.$this->servicePoint->getServiceClass( $service ).'</div>';
			$description	= '<div class="description">'.$this->servicePoint->getServiceDescription( $service ).'</div>';
			$cellService	= '<td class="service">'.$serviceClass.$serviceLink.$description.'</td>';
			$cellParameters	= '<td class="parameter">'.$parameters.'</td>';
			$cellsFormats	= implode( "", $cells );
			$formats		= implode( " ", $formats );
			$row	= '<tr class="service '.$formats.'">'.$cellService.$cellParameters.$cellsFormats.'</tr>';
			$rows[]	= $row;
			if( $counter % 10 == 0 )
				$rows[]	= $heads;
		}
		return "<table class='".$this->tableClass."'>".$cols."<thead>".$heads."</thead><tbody>".implode( "", $rows )."</tbody></table>";
	}
}