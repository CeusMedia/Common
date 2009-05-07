<?php
/**
 *	This is a Bar Visualization Class. 
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the {@link Chart} class.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
/**
 *	This is a Bar Visualization Class. 
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the {@link Chart} class.
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
class UI_SVG_BarAcross
{
	/**
	* This function generates a pie chart of the given data.
	* It uses the technology provided by the {@link Chart} class to generate a pie chart from the given data.<br>
	* You can pass the following options to the this method  by using {@link Chart::get()}:<br>
	* * cx & cy - The coordinates of the center point of the pie chart.<br>
	* * r - The radius of the pie chart.
	* @param array An array of options, see {@link Chart::get()}.
	* @return string
	* @see Chart::get()
	*/
	public function build( $options )
	{
		$x = isset( $options["x"] ) ? $options["x"] : 50;
		$y = isset( $options["y"] ) ? $options["y"] : 80;
		$data = $this->chart->data;
		
		$filters	= array();
		$pointLight	= UI_HTML_Tag::create( "fePointLight", "", array( 'x' => -5000, 'y' => -5000, 'z' => 5000 ) );
		$filters[]	= UI_HTML_Tag::create( "feGaussianBlur", "", array( 'in' => "SourceAlpha", 'stdDeviation' => "0.5", 'result' => "blur" ) );
		$filters[]	= UI_HTML_Tag::create( "feSpecularLighting", $pointLight, array( 'in' => "blur", 'surfaceScale' => "5", 'specularConstant' => "0.5", 'specularExponent' => "10", 'result' => "specOut", 'style' => "lighting-color: #FFF" ) );
		$filters[]	= UI_HTML_Tag::create( "feComposite", "", array( 'in' => "specOut", 'in2' => "SourceAlpha", 'operator' => "in", 'result' => "specOut2" ) );
		$filters[]	= UI_HTML_Tag::create( "feComposite", "", array( 'in' => "SourceGraphic", 'in2' => "specOut2", 'operator' => "arithmetic", 'k1' => 0, 'k2' => 1, 'k3' => 1, 'k4' => 0 ) );
		$filter		= UI_HTML_Tag::create( "filter", implode( "", $filters ), array( 'id' => "flt" ) );
		$defs		= UI_HTML_Tag::create( "defs", $filter );

		$count	= 0;
		$barx	= $x + 100;
		$descx	= $x + 200;
		$tags	= array();
		foreach( $data as $obj )
		{
			$color = $this->chart->getColor($count);
			$texty = $y + 11;
			$width = $obj->percent;
			$percent = number_format( $obj->percent, 2, ",", "." );
			if( isset( $options["animated"] ) )
			{
				$ani1	= UI_HTML_Tag::create( "animate", "", array( 'attributeName' => "width", 'attributeType' => "XML", 'begin' => "0s", 'dur' => "1s", 'fill' => "freeze", 'from' => 0, 'to' => $width ) );
				$ani2	= UI_HTML_Tag::create( "animate", "", array( 'attributeName' => "visibility", 'attributeType' => "CSS", 'begin' => "1s", 'dur' => "0.1s", 'fill' => "freeze", 'from' => 'hidden', 'to' => 'visible', 'calcMode' => 'discrete' ) );
				$tags[]	= UI_HTML_Tag::create( "rect", $ani1, array( 'x' => $barx, 'y' => $y, 'width' => 0, 'height' => 15, 'fill' => $color, 'style' => "filter: url(#flt)" ) );
				$tags[]	= UI_HTML_Tag::create( "text", $obj->desc, array( 'x' => $x, 'y' => $texty, 'style' => "font-size: 12px; text-anchor: right" ) );
				$tags[]	= UI_HTML_Tag::create( "text", "[".$percent."%]".$ani2, array( 'x' => $descx, 'y' => $texty, 'style' => "font-size: 12px; text-anchor: right; visibility: hidden" ) );
			}
			else
			{
				$tags[]	= UI_HTML_Tag::create( "rect", "", array( 'x' => $barx, 'y' => $y, 'width' => $width, 'height' => 15, 'fill' => $color, 'style' => "filter: url(#flt)" ) );
				$tags[]	= UI_HTML_Tag::create( "text", $obj->desc, array( 'x' => $x, 'y' => $texty, 'style' => "font-size: 12px; text-anchor: right" ) );
				$tags[]	= UI_HTML_Tag::create( "text", "[".$percent."%]", array( 'x' => $descx, 'y' => $texty, 'style' => "font-size: 12px; text-anchor: right" ) );
			}
			$y = $y + 27;
			$count++;
		}
		$tags	= implode( "", $tags );
		
		if( isset( $this->options["legend"] ) )
		{
			unset( $this->options["legend"] );
		}
		$graph	= "  ".UI_HTML_Tag::create( "g", $defs.$tags."  " );
		return $graph;
	}
}
?>