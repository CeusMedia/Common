<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	This is a Bar Visualization Class.
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the {@link Chart} class.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\SVG;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	This is a Bar Visualization Class.
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the {@link Chart} class.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class BarAcross
{
	protected Chart $chart;

	public function __construct( Chart $chart )
	{
		$this->chart    = $chart;
	}

	/**
	 * This function generates a pie chart of the given data.
	 * It uses the technology provided by the {@link Chart} class to generate a pie chart from the given data.<br>
	 * You can pass the following options to the this method  by using {@link Chart::get()}:<br>
	 * * cx & cy - The coordinates of the center point of the pie chart.<br>
	 * * r - The radius of the pie chart.
	 * @param array $options An array of options, see {@link Chart::get()}.
	 * @return string
	 * @see Chart::get()
	 */
	public function build( array $options ): string
	{
		$x = $options["x"] ?? 50;
		$y = $options["y"] ?? 80;
		$data = $this->chart->data;

		$pointLight	= HtmlTag::create( "fePointLight", "", ['x' => -5000, 'y' => -5000, 'z' => 5000] );

		$filters	= [
			HtmlTag::create( "feGaussianBlur", "", ['in' => "SourceAlpha", 'stdDeviation' => "0.5", 'result' => "blur"] ),
			HtmlTag::create( "feSpecularLighting", $pointLight, ['in' => "blur", 'surfaceScale' => "5", 'specularConstant' => "0.5", 'specularExponent' => "10", 'result' => "specOut", 'style' => "lighting-color: #FFF"] ),
			HtmlTag::create( "feComposite", "", ['in' => "specOut", 'in2' => "SourceAlpha", 'operator' => "in", 'result' => "specOut2"] ),
			HtmlTag::create( "feComposite", "", ['in' => "SourceGraphic", 'in2' => "specOut2", 'operator' => "arithmetic", 'k1' => 0, 'k2' => 1, 'k3' => 1, 'k4' => 0] ),
		];
		$filter		= HtmlTag::create( "filter", implode( "", $filters ), ['id' => "flt"] );
		$defs		= HtmlTag::create( "defs", $filter );

		$count	= 0;
		$barX	= $x + 100;
		$descX	= $x + 200;
		$tags	= array();
		foreach( $data as $obj ){
			$color  = $this->chart->getColor( $count );
			$textY  = $y + 11;
			$width  = $obj->percent;

			$percent = number_format( $obj->percent, 2, ",", "." );
			if( isset( $options["animated"] ) ){
				$ani1	= HtmlTag::create( "animate", "", ['attributeName' => "width", 'attributeType' => "XML", 'begin' => "0s", 'dur' => "1s", 'fill' => "freeze", 'from' => 0, 'to' => $width] );
				$ani2	= HtmlTag::create( "animate", "", ['attributeName' => "visibility", 'attributeType' => "CSS", 'begin' => "1s", 'dur' => "0.1s", 'fill' => "freeze", 'from' => 'hidden', 'to' => 'visible', 'calcMode' => 'discrete'] );
				$tags[]	= HtmlTag::create( "rect", $ani1, ['x' => $barX, 'y' => $y, 'width' => 0, 'height' => 15, 'fill' => $color, 'style' => "filter: url(#flt)"] );
				$tags[]	= HtmlTag::create( "text", $obj->desc, ['x' => $x, 'y' => $textY, 'style' => "font-size: 12px; text-anchor: right"] );
				$tags[]	= HtmlTag::create( "text", "[".$percent."%]".$ani2, ['x' => $descX, 'y' => $textY, 'style' => "font-size: 12px; text-anchor: right; visibility: hidden"] );
			}
			else{
				$tags[]	= HtmlTag::create( "rect", "", ['x' => $barX, 'y' => $y, 'width' => $width, 'height' => 15, 'fill' => $color, 'style' => "filter: url(#flt)"] );
				$tags[]	= HtmlTag::create( "text", $obj->desc, ['x' => $x, 'y' => $textY, 'style' => "font-size: 12px; text-anchor: right"] );
				$tags[]	= HtmlTag::create( "text", "[".$percent."%]", ['x' => $descX, 'y' => $textY, 'style' => "font-size: 12px; text-anchor: right"] );
			}
			$y = $y + 27;
			$count++;
		}
		$tags	= implode( "", $tags );

		if( isset( $this->options["legend"] ) ){
			unset( $this->options["legend"] );
		}
		return "  ".HtmlTag::create( "g", $defs.$tags."  " );
	}
}