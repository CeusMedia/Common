<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	This is a pie visualization class.
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the UI_SVG_Chart class.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\SVG;

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	This is a pie visualization class.
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the UI_SVG_Chart class.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PieGraph
{
	protected Chart $chart;

	public function __construct( Chart $chart )
	{
		$this->chart    = $chart;
	}

	/**
	 *	This function generates a pie chart of the given data.
	 *	It uses the technology provided by the {@link Chart} class to generate a pie chart from the given data.<br>
	 *	You can pass the following options to the this method  by using {@link Chart::get()}:<br>
	 *	* cx & cy - The coordinates of the center point of the pie chart.<br>
	 *	* r - The radius of the pie chart.
	 *	@access		public
	 *	@param		array		$options    An array of options, see {@link Chart::get()}.
	 *	@return		string
	 *	@see Chart::get()
	 */
	public function build( array $options ): string
	{
		$cx	= $options["cx"] ?? 200;
		$cy	= $options["cy"] ?? 200;
		$r	= $options["r"] ?? 150;
		$x1 = $cx;
		$y1 = $cy - $r;
		$alpha	= 0;
		$output	= "";
		$count	= 0;

		$data	= $this->chart->data;
		$sum	= 0;
		foreach( $data as $obj )
			$sum += $obj->value;

		foreach( $data as $obj ){
			$alpha = $alpha + ( $obj->percent / 100 * ( 2 * M_PI ) );

			$x2 = $cx + ( $r * sin( $alpha ) );
			$y2 = $cy - ( $r * cos( $alpha ) );

			$rotate180	= $obj->percent > 50 ? 1 : 0;
			$color		= $this->chart->getColor( $count );

			$attributes	= array(
				'd'			=> "M{$cx},{$cy} L$x1,$y1 A{$r},{$r} 0 $rotate180,1 $x2,$y2 Z",
				'fill'		=> $color,
				'opacity'	=> 0.8,
			);
			$output .= HtmlTag::create( "path", NULL, $attributes );

			$x1	= $x2;
			$y1	= $y2;
			$count++;
		}

		if( isset( $this->options["legend"] ) && $options["legend"] ){
			$x = $cx + $r * 1.2;
			$y = $cy - $r;
			$this->options["legend"]	= array(
				"x"	=> $x,
				"y"	=> $y,
			);
		}
		return HtmlTag::create( "g", $output );
	}
}