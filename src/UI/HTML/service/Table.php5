<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		ui.html.service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	...
 *	@category		cmClasses
 *	@package		ui.html.service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			Code Doc
 */
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
		
		$heads	= array( "<th>Service</th><th>Parameter</th>" );
		$cols	= array( "<col width='35%'/><col width='30%'/>" ); 
		foreach( $this->availableFormats as $format )
		{
			$cols[]		= "<col width='".round( ( 100 - 65 ) / count( $this->availableFormats ), 0 )."%'/>";
			$heads[]	= "<th><a href='#' onClick='$(\"#format\").val(\"".$format."\").trigger(\"change\")'>".strtoupper( $format )."</a></th>";
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
					$cells[]	= "<td class='preferred'><span class='".$format."'>+</span></td>";
				else if( in_array( $format, $formats ) )
					$cells[]	= "<td class='yes'><span class='".$format."'>+</span></td>";
				else
					$cells[]	= "<td class='no'>-</td>";
			}
						
			//  --  PARAMETERS  --   //
			$parameterList	= array();
			$parameters	= $this->servicePoint->getServiceParameters( $service );
			foreach( $parameters as $parameter => $rules )
			{
				$ruleList	= array();
				$mandatory	= FALSE;
				$type		= "";
				if( $rules )
				{
					foreach( $rules as $ruleKey => $ruleValue )
					{
						if( $ruleKey == "title" )
							continue;
						if( $ruleKey == "mandatory" )
						{
							$mandatory	= $ruleValue;
							$ruleValue	= $ruleValue ? "yes" : "no";
						}
						if( $ruleKey == "type" )
						{
							$type	= "<small><em>".$ruleValue."</em></small>&nbsp;";
						}
						$spanKey	= UI_HTML_Tag::create( "span", $ruleKey.":", array( 'class' => "key" ) );
						$spanValue	= UI_HTML_Tag::create( "span", htmlspecialchars( $ruleValue ), array( 'class' => "value" ) );
						$ruleList[]	= $spanKey." ".$spanValue;
					}
				}
				if( isset( $rules['title'] ) )
					$parameter	= UI_HTML_Elements::Acronym( $parameter, $rules['title'] );
				$parameter	= $type.$parameter;
				if( !$mandatory )
					$parameter	= "[".$parameter."]";
				$rules	= $ruleList ? '<div class="rules">'.implode( ", ", $ruleList ).'</div>' : "";
				$parameterList[]	= $rules.$parameter;
			}
			$parameters	= implode( "<br/>", $parameterList );

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