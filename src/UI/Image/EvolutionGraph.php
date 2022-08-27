<?php
/**
 *	Generator for Evolution Graph Images.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			13.09.2006
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\ADT\OptionObject;

/**
 *	Generator for Evolution Graph Images.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			13.09.2006
 *	@todo			Finish Implementation
 *	@todo			Code Documentation
 */
class EvolutionGraph extends OptionObject
{
	protected $defaults	= array(
		//  Width of Image
		'width'					=> 400,
		//  Height of Image
		'height'				=> 150,
		//  Distance of Graph within Image
		'padding_left'			=> 10,
		'padding_right'			=> 50,
		'padding_top'			=> 15,
		'padding_bottom'		=> 15,
		//  Color of Background
		'color_background'		=> [0xFF, 0xFF, 0xFF],
		//  Color of Y-Axis Labels
		'color_bars'			=> [0xCC, 0xCC, 0xCC],
		//  Color of dashed Lines
		'color_dash'			=> [0xDF, 0xDF, 0xDF],
//  Color of Text
//		'color_text'			=> [0x0, 0x00, 0x00],
		//  Color of Title Text
		'color_title'			=> [0x00, 0x00, 0x00],
		'title_x'				=> 20,
		'title_y'				=> 0,
		//  Title Text (to be changed with setTitle()
		'title_text'			=> "EvolutionGraph",
		//  Font and Style of Title (3 - medium&bold)
		'title_font'			=> 3,
		//  Background Transparency
		'transparent'			=> true,
		//  Quantity of horizontal Guidelines
		'horizontal_bars'		=> 5,
		//  Distance of Labels
		'label_adjust_x'		=> -10,
		'label_adjust_y'		=> -10,
		//  Distance of Legend
		'legend_adjust_x'		=> 5,
		'legend_adjust_y'		=> 2,
	);

	/**	@var	array		graphs		Array of Values of one or more Graphs */
	protected $graphs	= [];

	/**
	 *	Constructor, sets default Options.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $options = [] )
	{
		parent::__construct();
		$this->setDefaults();
		foreach( $options as $key => $value )
			$this->setOption( $key, $value );
	}

	/**
	 *	Adds another Graph with Legend, Line Color and Values.
	 *	@access		public
	 *	@param		string		$legend		Legend Label of Graph
	 *	@param		array		$color		Array of RGB-Values
	 *	@param		array		$data		Array of Values of Graph
	 *	@return		void
	 */
	public function addGraph( $legend, $color, $data )
	{
		$position	= count( $this->graphs );
		$this->graphs[$position]	= array(
			'legend'	=> $legend,
			'color'		=> $color,
			'values'	=> $data,
		);
	}

	/**
	 *	Draws Graph Image to Browser.
	 *	@access		public
	 *	@return		void
	 */
	public function drawGraph()
	{
		//  generate Graph Image
		$im	= $this->generateGraph();
		//  send Image to Browser
		ImagePng( $im );
	}

	protected function drawGraphs( &$image, $maxValue, $ratio )
	{
		$verticalZone	= $this->getOption( 'height' ) - $this->getOption( 'padding_top' ) - $this->getOption( 'padding_bottom' );
		for( $i=0; $i<count( $this->graphs ); $i++ )
		{
			$graph	= $this->graphs[$i];
			$color	= $this->setColor( $image, $graph['color'] );
			// write the legend
			ImageString( $image, 2, $this->getOption( 'padding_left' ) + $this->getOption( 'legend_adjust_x' ), $this->getOption( 'padding_top' ) + $this->getOption( 'legend_adjust_y' ) + $i * 10, $graph['legend'], $color );
			// FIXME: a more general approach; maybe allow custom placement on the image
			// draw the graph
			for( $n=0; $n<count( $graph['values'] ) - 1; $n++)
			{
				// calculate and draw line from value N to value N+1
				$xn1	= $this->getOption( 'padding_left' ) + $n * $ratio;
				$yn1	= $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ) - floor( $graph['values'][$n] * $verticalZone / $maxValue );
				$xn2	= $this->getOption( 'padding_left' ) + ( $n + 1 ) *$ratio;
				$yn2	= $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ) - floor( $graph['values'][$n+1] * $verticalZone / $maxValue );
				ImageLine( $image, $xn1, $yn1, $xn2, $yn2, $color );
			}
		}
	}

	protected function drawOutlines( &$image, $color )
	{
		ImageLine( $image, $this->getOption( 'padding_left' ), $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ), $this->getOption( 'width' ) - $this->getOption( 'padding_right' ), $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ), $color );
		ImageLine( $image, $this->getOption( 'padding_left' ), $this->getOption( 'padding_top' ), $this->getOption( 'width' ) - $this->getOption( 'padding_right' ), $this->getOption( 'padding_top' ), $color );
		ImageLine( $image, $this->getOption( 'padding_left' ), $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ), $this->getOption( 'padding_left' ), $this->getOption( 'padding_top' ), $color );
		ImageLine( $image, $this->getOption( 'width' ) - $this->getOption( 'padding_right' ), $this->getOption( 'padding_top' ), $this->getOption( 'width' ) - $this->getOption( 'padding_right' ), $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ), $color );
	}

	/**
	 *	Generates Graph Image and returns Resource.
	 *	@access		public
	 *	@return		resource
	 */
	public function generateGraph()
	{
		// set the image size
		$im = @ImageCreate( $this->getOption( 'width' ), $this->getOption( 'height' ) );
		extract( $this->getOptions() );
		//  set Background Color
		$imageColorBackground	= $this->setColor( $im, $this->getOption( "color_background" ) );
		//  set Color of Y-Axis Labels
		$imageColorBars			= $this->setColor( $im, $this->getOption( "color_bars" ) );
		//  set Color of dashed Lines
		$imageColorDash			= $this->setColor( $im, $this->getOption( "color_dash" ) );
//  set Color of Text
//		$imageColorText			= $this->setColor( $im, $this->getOption( "color_text" ) );
		//  set Color of Title Text
		$imageColorTitle		= $this->setColor( $im, $this->getOption( "color_title" ) );
		if( $this->getOption( 'transparent' ) )
			//  set Background Transparency
			ImageColorTransparent( $im, $imageColorBackground );
		//  draw Outlines of Graph Image
		$this->drawOutlines( $im, $imageColorBars );
		// in case no maximum scale has been provided, calculate the maximum value reached by any of the lines
		if( !isset( $maxValue ) )
		{
			$maxValue	= 0;
			for( $g=0; $g<count( $this->graphs ); $g++ )
				$maxValue	= max( $maxValue, max( $this->graphs[$g]['values'] ) );
	//		if( isset( $maxAdjust ) )
	// so that it doesn't touch the upper margin
	//			$maxValue	+= $maxAdjust;
		}

		// determine the maximum height available for drawing
		// draw the horizontal dotted "guidelines"
		$ratio	= floor( $this->getOption( 'height' ) - $this->getOption( 'padding_top' ) - $this->getOption( 'padding_bottom' ) ) / $this->getOption( 'horizontal_bars' );
		for( $i=0; $i<$this->getOption( 'horizontal_bars' ); $i++ )
		{
			$height	= $this->getOption( 'padding_top' ) + $i * $ratio;
			if( $i )
				ImageDashedLine( $im, $this->getOption( 'padding_left' ), $height, $this->getOption( 'width' ) - $this->getOption( 'padding_right' ), $height, $imageColorDash );
			// write proper values next to the horizontal guidelines, based on their number and max value
			// FIXME: a more general approach; ability to place them on either side for example
			ImageString( $im, 1, $this->getOption( 'width' ) - $this->getOption( 'padding_left' ) - 30, $height - 3, floor( ( $this->getOption( 'horizontal_bars' ) - $i ) * $maxValue / $this->getOption( 'horizontal_bars' ) ), $imageColorTitle );
		}
		// draw the vertical dotted guidelines; these depend on how much data you have
		// FIXME: make it possible to draw only the Nth line
		$ratio	= floor( $this->getOption( 'width' ) - $this->getOption( 'padding_left' ) - $this->getOption( 'padding_right' ) ) / ( count( $this->labels ) - 1 );
		for( $i=0; $i<count( $this->labels ); $i++ )
		{
			if( $i<count( $this->labels ) -2 )
			{
				$width	=$this->getOption( 'padding_left' ) + ( $i + 1 ) * $ratio;
				ImageDashedLine( $im, $width, $this->getOption( 'padding_top' ), $width, $this->getOption( 'height' ) - $this->getOption( 'padding_bottom' ), $imageColorDash );
			}
			$width	= $this->getOption( 'padding_left' ) + $i * $ratio;
			// write the labels for each value
			ImageString( $im, 1, $width + $this->getOption( 'label_adjust_x' ), $this->getOption( 'height' ) + $this->getOption( 'label_adjust_y' ), $this->labels[$i], $imageColorTitle );
		}
		// actually output the graphs
		$this->drawGraphs( $im, $maxValue, $ratio );

		// write the title
		ImageString( $im, $this->getOption( 'title_font' ), $this->getOption( 'title_x' ), $this->getOption( 'title_y' ), $this->getOption( 'title_text' ), $imageColorTitle );
		return $im;
	}

	/**
	 *	Saves Graph Image to File.
	 *	@access		public
	 *	@param		string		filename		File Name to save Graph Image to
	 *	@return		void
	 */
	public function saveGraph( $filename )
	{
		// generate the image
		$im	= $this->generateGraph();
		// output the image
		ImagePng( $im, $filename );
	}

	protected function setColor( &$image, $values )
	{
		$color	= ImageColorAllocate( $image, $values[0], $values[1], $values[2] );
		return	$color;
	}

	protected function setDefaults()
	{
		foreach( $this->defaults as $key => $value )
			$this->setOption( $key, $value );
	}

	/**
	 *	Sets Labels of X-Axis.
	 *	@access		public
	 *	@param		array		labels		Array of Labels of X-Axis
	 *	@return		void
	 */
	public function setLabels( $labels )
	{
		$this->labels	= $labels;
	}

	/**
	 *	Sets Title of Graph.
	 *	@access		public
	 *	@param		string		title			Title of Graph
	 *	@return		void
	 */
	public function setTitle( $title )
	{
		$this->setOption( 'title_text', $title );
	}
}
