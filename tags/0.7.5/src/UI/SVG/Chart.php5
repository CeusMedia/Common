<?php
/**
 *	The main Chart package file. It includes the core of all Chart classes.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
/**
 *	The main Chart class. Base class for all subtypes of charts, like Pie, Bar, Line and so on.
 *	@category		cmClasses
 *	@package		UI.SVG
 *	@uses			UI_HTML_Tag
 *	@uses			File_Writer
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
class UI_SVG_Chart
{
	/**
	 *	Array for storing the data to visualize.
	 *	@var		array
	 *	@access		protected
	 */
	public $data;
	
	/**
	 *	Array for storing the colors to visualize the data defined in {@link $data}.
	 *	@var		array
	 *	@access		public
	 *	@see		Chart::$data
	 */	
	public $colors;
	
	protected $content	= "";

	/**
	 *	The constructor.
	 *	You can pass it an array of {@link Chart_Data} objects as data, 
	 *	the name of the visualization 
	 *	and, optional, an array contents the colors in what the Chart data is visualized.
	 *	@access		public
	 *	@param		array 		List of Chart_Data objects
	 *	@param		array		Colors to display the data in.
	 *	@return		void
	 */
	public function __construct( $data = false, $colors = false )
	{
		if( !$colors )
			$colors = array(
				"red",
				"yellow",
				"blue",
				"orange"
			);
		$this->colors = $colors;
		$this->setData( $data );
	}
	
	/**
	 *	This function sets the {@link Chart::$data} array to a new value.
	 *	@access		public
	 *	@param		array		New Value for {@link Chart::$data}
	 *	@return		array		Old Value of {@link Chart::$data}
	 */
	public function setData( $data )
	{
		$sum = 0;
		foreach( $data as $obj )
			$sum += $obj->value;
		
		foreach( $data as $key => $obj )
		{
			$obj->percent = $obj->value / $sum * 100;
			$this->data[$key] = $obj;
		}
	}
	
	/**
	 *	This function returns the svg code for the visualized form of the internal data.
	 *	It receives the name of the visualization class to use.
	 *	As $options, you can pass an array of options forwarded to the visualization class.<br>
	 *	The following options are also implemented in this function:<br>
	 *	* legend - If set, a legend is also generated. The value is also an array passed to the 
	 *	{@link Chart::makeLegend()} function.
	 *	@access		protected
	 *	@param		string		Class to use
	 *	@param		array		Options, passed to the chart class
	 *	@return		string		SVG code
	 */
	protected function buildComponent( $chart, $options = false )
	{
		if( !$this->data )
			throw new Exception( "No \$data set!" );
		if( !$options )
			$options = array();

		$chart->options = $options;
		$content = $chart->build( $options );
		$options = $chart->options;
		
		if( isset( $options["legend"] ) && $options["legend"] )
		{
			$content .= $this->makeLegend( $options["legend"] );
		}
		return $content;
	}

	/**
	 *	Builds Pie Graph and appends it to SVG Document.
	 *	@access		public
	 *	@param		array		$options		Options of Graph
	 *	@return		void
	 */
	public function buildPieGraph( $options = false )
	{
		$chart = new UI_SVG_PieGraph;
		$chart->chart = &$this;
		$this->content	.= $this->buildComponent( $chart, $options );
	}

	/**
	 *	Builds Bar Graph and appends it to SVG Document.
	 *	@access		public
	 *	@param		array		$options		Options of Graph
	 *	@return		void
	 */
	public function buildBarAcross( $options = false )
	{
		$chart = new UI_SVG_BarAcross;
		$chart->chart = &$this;
		$this->content	.= $this->buildComponent( $chart, $options );
	}

	
	/**
	 * 	This function does the same as {@link get()}, with one difference:
	 *	The returned svg code is capsulated in a <svg>....</svg> element structure, so it returns a completely SVG document.
	 *	@access		public
	 *	@param		string		Class to use
	 *	@param		array		Options, passed to the chart visulaization class
	 */
	public function makeSVG( $name = false, $options = false )
	{
		return encapsulate( $this->get( $name, $options ) );
	}
	
	/**
	 *	This is an internal function used by the visualization classes to make a legend to the various chart types.
	 *	It uses the internal {@link $data} structure.<br>
	 *	You can pass the following options:<br>
	 *	* x & y - X & Y coordinates of the top-left point of the legend
	 *	@access		public
	 *	@param		array		Options passed
	 *	@return		string		SVG code for a legend.
	 */
	public function makeLegend( $options = false )
	{
		$x		= isset( $options["x"] ) ? $options["x"] : 200;
		$y		= isset( $options["y"] ) ? $options["y"] : 200;
		$width	= isset( $options["width"] ) ? $options["width"] : 100;
		$height	= isset( $options["height"] ) ? $options["height"] : count( $this->data ) * 20 + 5;

		$tags	= array( "" );		

		# Frame
		$attributes	= array( 'x' => $x + 4, 'y' => $y + 4, 'width' => $width, 'height' => $height, 'fill' => "#BBB" );
		$tags[]	= UI_HTML_Tag::create( "rect", NULL, $attributes );
		$attributes	= array( 'x' => $x, 'y' => $y, 'width' => $width, 'height' => $height, 'fill' => "white", 'stroke' => "#333" );
		$tags[]	= UI_HTML_Tag::create( "rect", NULL, $attributes );
		
		$y		= $y + 5;
		$x		= $x + 5;
		$count	= 0;
		$colors	= $this->colors;
		$data	= $this->data;
		foreach( $data as $obj )
		{
			$texty	= $y + 15;
			$textx	= $x + 20;
			$color	= $colors[$count % count( $colors )];
			$tags[]	= UI_HTML_Tag::create( "rect", NULL, array( 'x' => $x, 'y' => $y, 'width' => 15, 'height' => 15, 'fill' => $color ) );
			$tags[]	= UI_HTML_Tag::create( "text", $obj->desc, array( 'x' => $textx, 'y' => $texty ) );
			$y		+= 20;
			$count++;
		}
		$tags	= implode( "\n", $tags );
		$graph	= UI_HTML_Tag::create( "g", $tags );
		$this->content	.= $graph;		
	}
	
	/**
	 *	This function simply returns a color from the internal coller palette.
	 *	Supplied is a number.
	 *	@access		public
	 *	@param		integer		The id of the color
	 *	@return		string		color name or hexadeciaml triplet
	 */
	public function getColor( $id )
	{
		$color = $this->colors[$id % count( $this->colors )];
		return $color;
	}
	
	/**
	 *	This function simply enclosoures the received svg code with the beginning- and ending <svg> or </svg> tags.
	 *	Also it includes an <?xml ... ?> header.
	 *	@access		public
	 *	@param		string		SVG code to encapsulate
	 *	@return		string		The encapsulated SVG code
	 */
	public function encapsulate( $svg )
	{
		$data = '<?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
		$data .= $svg;
		$data .= "</svg>";
		
		return $data;
	}
	
	/**
	 *	Saves SVG Graph to File.
	 *	@access		public
	 *	@param		string		$fileName		File to save to
	 *	@return		int
	 */
	public function save( $fileName )
	{
		$svg	= $this->encapsulate( $this->content );
		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace = false; 
		$doc->formatOutput = true;
		$doc->loadXml( $svg );
		$svg	= $doc->saveXml();
		return File_Writer::save( $fileName, $svg );
	}
}
?>