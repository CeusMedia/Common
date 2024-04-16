<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	The main Chart package file. It includes the core of all Chart classes.
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

use CeusMedia\Common\FS\File\Writer as FileWriter;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use DOMDocument;
use RuntimeException;


/**
 *	The main Chart class. Base class for all subtypes of charts, like Pie, Bar, Line and so on.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Chart
{
	/**
	 *	Array for storing the data to visualize.
	 *	@var		array
	 *	@access		protected
	 */
	public array $data;

	/**
	 *	Array for storing the colors to visualize the data defined in {@link $data}.
	 *	@var		array
	 *	@access		public
	 *	@see		Chart::$data
	 */
	public array $colors;

	protected string $content	= "";

	/**
	 *	The constructor.
	 *	You can pass it an array of {@link Chart_Data} objects as data,
	 *	the name of the visualization
	 *	and, optional, an array contents the colors in what the Chart data is visualized.
	 *	@access		public
	 *	@param		array 		$data       List of Chart_Data objects
	 *	@param		array		$colors     Colors to display the data in.
	 *	@return		void
	 */
	public function __construct( array $data, array $colors = [] )
	{
		if( !$colors )
			$colors = [
				"red",
				"yellow",
				"blue",
				"orange"
			];
		$this->colors = $colors;
		$this->setData( $data );
	}

	/**
	 *	Builds Bar Graph and appends it to SVG Document.
	 *	@access		public
	 *	@param		array		$options		Options of Graph
	 *	@return		void
	 */
	public function buildBarAcross( array $options = [] ): void
	{
		$graph = new BarAcross( $this );
		$this->content	.= $this->buildComponent( $graph, $options );
	}

	/**
	 *	This function returns the svg code for the visualized form of the internal data.
	 *	It receives the name of the visualization class to use.
	 *	As $options, you can pass an array of options forwarded to the visualization class.<br>
	 *	The following options are also implemented in this function:<br>
	 *	* legend - If set, a legend is also generated. The value is also an array passed to the
	 *	{@link Chart::makeLegend()} function.
	 *	@access		protected
	 *	@param		object		$graph        Class to use
	 *	@param		array		$options      Options, passed to the chart class
	 *	@return		string		SVG code
	 */
	protected function buildComponent( object $graph, array $options = [] ): string
	{
		if( !$this->data )
			throw new RuntimeException( 'No data set' );

		$graph->options = $options;
		$this->content  = $graph->build( $options );

		if( isset( $graph->options["legend"] ) && $graph->options["legend"] ){
			$this->makeLegend( $graph->options["legend"] );
		}
		return $this->content;
	}

	/**
	 *	Builds Pie Graph and appends it to SVG Document.
	 *	@access		public
	 *	@param		array		$options		Options of Graph
	 *	@return		void
	 */
	public function buildPieGraph( array $options = [] ): void
	{
		$graph = new PieGraph( $this );
		$this->content	.= $this->buildComponent( $graph, $options );
	}

	/**
	 *	This function simply enclosoures the received svg code with the beginning- and ending <svg> or </svg> tags.
	 *	Also it includes an <?xml ... ?> header.
	 *	@access		public
	 *	@param		string		$svg        SVG code to encapsulate
	 *	@return		string		The encapsulated SVG code
	 */
	public function encapsulate( string $svg ): string
	{
		$data = '<?xml version="1.0" encoding="iso-8859-1"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "https://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">';
		$data .= $svg;
		$data .= "</svg>";

		return $data;
	}

	/**
	 *	This function simply returns a color from the internal color palette.
	 *	Supplied is a number.
	 *	@access		public
	 *	@param		integer		$id     The id of the color
	 *	@return		string		color name or hexadeciaml triplet
	 */
	public function getColor( int $id ): string
	{
		return $this->colors[$id % count( $this->colors )];
	}

	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 *	This is an internal function used by the visualization classes to make a legend to the various chart types.
	 *	It uses the internal {@link $data} structure.<br>
	 *	You can pass the following options:<br>
	 *	* x & y - X & Y coordinates of the top-left point of the legend
	 *	@access		public
	 *	@param		array		$options        Options passed
	 *	@return		void
	 */
	public function makeLegend( array $options = [] ): void
	{
		$x		= $options["x"] ?? 200;
		$y		= $options["y"] ?? 200;
		$width	= $options["width"] ?? 100;
		$height	= $options["height"] ?? count( $this->data ) * 20 + 5;

		$tags	= [''];

		# Frame
		$attributes	= array( 'x' => $x + 4, 'y' => $y + 4, 'width' => $width, 'height' => $height, 'fill' => "#BBB" );
		$tags[]	= HtmlTag::create( "rect", NULL, $attributes );
		$attributes	= array( 'x' => $x, 'y' => $y, 'width' => $width, 'height' => $height, 'fill' => "white", 'stroke' => "#333" );
		$tags[]	= HtmlTag::create( "rect", NULL, $attributes );

		$y		= $y + 5;
		$x		= $x + 5;
		$count	= 0;
		$colors	= $this->colors;
		$data	= $this->data;
		foreach( $data as $obj ){
			$textY	= $y + 15;
			$textX	= $x + 20;
			$color	= $colors[$count % count( $colors )];
			$tags[]	= HtmlTag::create( "rect", NULL, array( 'x' => $x, 'y' => $y, 'width' => 15, 'height' => 15, 'fill' => $color ) );
			$tags[]	= HtmlTag::create( "text", $obj->desc, array( 'x' => $textX, 'y' => $textY ) );
			$y		+= 20;
			$count++;
		}
		$tags	= implode( "\n", $tags );
		$this->content  .= HtmlTag::create( "g", $tags );
	}

//	public function build( $name, array $options ): string
//	{
//		$content    = '';
//		switch( $name ){
//			case 'BarAcross':
//				$content    = $this->buildBarAcross( $options );
//				break;
//			case 'PieGraph':
//				$content    = $this->buildPieGraph( $options );
//				break;
//		}
//		$content    .= $this->makeLegend( $options );
//		return $content;
//	}
//	/**
//	 * 	This function does the same as {@link build()}, with one difference:
//	 *	The returned svg code is capsuled in a <svg>....</svg> element structure, so it returns a completely SVG document.
//	 *	@access		public
//	 *	@param		string		$name       Class to use
//	 *	@param		array		$options    Options, passed to the chart visualization class
//	 */
//	public function makeSVG( string $name, array $options = [] ): string
//	{
//		return $this->encapsulate( $this->build( $name, $options ) );
//	}

	/**
	 *	Saves SVG Graph to File.
	 *	@access		public
	 *	@param		string		$fileName		File to save to
	 *	@return		int
	 */
	public function save( string $fileName ): int
	{
		$svg	= $this->encapsulate( $this->content );
		$doc	= new DOMDocument();
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true;
		$doc->loadXml( $svg );
		$svg	= $doc->saveXml();
		return FileWriter::save( $fileName, $svg );
	}

	/**
	 *	This function sets the {@link Chart::$data} array to a new value.
	 *	@access		public
	 *	@param		array	    $data	    New Value for {@link Chart::$data}
	 *	@return		self
	 */
	public function setData( array $data ): self
	{
		$sum = 0;
		foreach( $data as $obj )
			$sum += $obj->value;

		foreach( $data as $key => $obj ){
			$obj->percent = $obj->value / $sum * 100;
			$this->data[$key] = $obj;
		}
		return $this;
	}
}