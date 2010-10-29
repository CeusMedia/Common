<?php
/**
 *	Generator for Evolution Graph Images.
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
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.09.2006
 *	@version		$Id$
 */
/**
 *	Generator for Evolution Graph Images.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@extends		ADT_OptionObject
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.09.2006
 *	@version		$Id$
 *	@todo			Finish Implementation
 *	@todo			Code Documentation
 */
class UI_Image_EvolutionGraph extends ADT_OptionObject
{
	protected $defaults	= array(
		'width'					=> 400,												//  Width of Image	
		'height'				=> 150,												//  Height of Image
		'padding_left'			=> 10,												//  Distance of Graph within Image
		'padding_right'			=> 50,
		'padding_top'			=> 15,
		'padding_bottom'		=> 15,
		'color_background'		=> array( 0xFF, 0xFF, 0xFF ),						//  Color of Background 
		'color_bars'			=> array( 0xCC, 0xCC, 0xCC ),						//  Color of Y-Axis Labels
		'color_dash'			=> array( 0xDF, 0xDF, 0xDF ),						//  Color of dashed Lines
//		'color_text'			=> array( 0x0, 0x00, 0x00 ),						//  Color of Text
		'color_title'			=> array( 0x00, 0x00, 0x00 ),						//  Color of Title Text
		'title_x'				=> 20,
		'title_y'				=> 0,
		'title_text'			=> "EvolutionGraph",								//  Title Text (to be changed with setTitle()
		'title_font'			=> 3,												//  Font and Style of Title (3 - medium&bold)
		'transparent'			=> true,											//  Background Transparency 
		'horizontal_bars'		=> 5,												//  Quantity of horizontal Guidelines
		'label_adjust_x'		=> -10,												//  Distance of Labels
		'label_adjust_y'		=> -10,
		'legend_adjust_x'		=> 5,												//  Distance of Legend
		'legend_adjust_y'		=> 2,
	);

	/**	@var	array		graphs		Array of Values of one or more Graphs */
	protected $graphs	= array();

	/**
	 *	Constructor, sets default Options.
	 *	@access		public
	 *	@return		void
	 */	 
	public function __construct()
	{
		parent::__construct();
		$this->setDefaults();
	}
	
	/**
	 *	Adds another Graph with Legend, Line Color and Values.
	 *	@access		public
	 *	@param		string		legend		Legend Label of Graph
	 *	@param		array		color		Array of RGB-Values
	 *	@param		array		data			Array of Values of Graph
	 *	@return		void
	 */
	public function addGraph( $legend, $color, $data )
	{
		$position	= count( $this->graphs );
		$this->graphs[$position]	= array(
			'legend'	=> $legend,
			'color'	=> $color,
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
		$im	= $this->generateGraph();												//  generate Graph Image
		ImagePng( $im );															//  send Image to Browser
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
		$imageColorBackground	= $this->setColor( $im, $this->getOption( "color_background" ) );			//  set Background Color
		$imageColorBars			= $this->setColor( $im, $this->getOption( "color_bars" ) );					//  set Color of Y-Axis Labels
		$imageColorDash			= $this->setColor( $im, $this->getOption( "color_dash" ) );					//  set Color of dashed Lines
//		$imageColorText			= $this->setColor( $im, $this->getOption( "color_text" ) );					//  set Color of Text
		$imageColorTitle		= $this->setColor( $im, $this->getOption( "color_title" ) );				//  set Color of Title Text
		if( $this->getOption( 'transparent' ) )
			ImageColorTransparent( $im, $imageColorBackground );											//  set Background Transparency
		$this->drawOutlines( $im, $imageColorBars );														//  draw Outlines of Graph Image
		// in case no maximum scale has been provided, calculate the maximum value reached by any of the lines
		if( !isset( $maxValue ) )
		{
			$maxValue	= 0;
			for( $g=0; $g<count( $this->graphs ); $g++ )
				$maxValue	= max( $maxValue, max( $this->graphs[$g]['values'] ) );
	//		if( isset( $maxAdjust ) )
	//			$maxValue	+= $maxAdjust; // so that it doesn't touch the upper margin
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
}
?>