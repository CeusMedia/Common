<?php
/**
 *	...
 *	@package		ui.image
 *	@uses			UI_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
require_once( JPGRAPH_PATH.'jpgraph.php' );
require_once( JPGRAPH_PATH.'jpgraph_pie.php');
require_once( JPGRAPH_PATH.'jpgraph_pie3d.php' );
/**
 *	...
 *	@package		ui.image
 *	@uses			UI_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
class UI_Image_PieGraph
{
	protected $antialias		= TRUE;
	protected $centerX			= 0.43;
	protected $centerY			= 0.58;
	protected $heading			= NULL;
	protected $height			= 400;
	protected $legendMarginX	= 0.008;
	protected $legendMarginY	= 0.005;
	protected $legendAlignX		= 'right';
	protected $legendAlignY		= 'top';
	protected $legendShadow		= FALSE;
	protected $width			= 600;
	protected $shadow			= FALSE;
		
	
	protected $colors	= array(
		'#07077F',
		'#2F2F9F',
		'#575FBF',
		'#7F8FDF',
		'#a7bFFF',
		'#1FDF1F',
		'#7FDF1F',
		'#DFDF1F',
		'#DF7F1F',
		'#DF1F1F',
		'#BF0073',
		'#6A009F',
		'#DFDFDF',
		'#7F7F7F',
		'#3F3F3F',
	);

	public function __construct( $width, $height )
	{
		$this->setSize( $width, $height );
	}
	
	public function build( $id, $samples, $uri )
	{
		$idMap	= $id."_imageMap";
		$this->buildImage( $id, $samples, $uri );
		$image		= "<img src='".$uri."?".time()."' ISMAP USEMAP='#".$idMap."' border='0'>";
		$data	= array(
			'type'		=> "test",//$type,
			'map'		=> $this->map,
			'image'		=> $image
		);
		return UI_Template::render( 'templates/graph.html', $data );
	}
	
	public function buildImage( $id, $data, $uri = NULL )
	{
		if( empty( $data['values'] ) )
			return "No entries found";
		$graph = new PieGraph( $this->width, $this->height, $id );
		$graph->setShadow( $this->shadow );
		$graph->setAntiAliasing( $this->antialias );
		if( $this->heading )
			$graph->title->Set( $this->heading );
//		$graph->title->setFont( FF_VERDANA,FS_NORMAL, 11 );
		$graph->title->setFont( FF_FONT1, FS_BOLD );
#		$graph->title->pos();
		$graph->legend->pos(
			$this->legendMarginX,
			$this->legendMarginY,
			$this->legendAlignX,
			$this->legendAlignY
		);
		$graph->legend->setShadow( $this->legendShadow );
//		$graph->legend->setFont( FF_VERDANA, FS_NORMAL, 8 );
		$graph->legend->setFont( FF_FONT1, FS_NORMAL, 8 );
		$p1 = new PiePlot3D( $data['values'] );
		$p1->value->setFormat( "%d%%" );
		$p1->value->show();
//		$p1->value->setFont( FF_VERDANA, FS_NORMAL, 8 );
		$p1->value->setFont( FF_FONT1, FS_NORMAL, 7 );
		$p1->setLegends( $data['legends'] );
		$p1->setCSIMTargets( $data['uris'], $data['labels'] );
		$p1->setSliceColors( $this->colors );
		$p1->setCenter( $this->centerX, $this->centerY );
		$graph->add( $p1 );
		if( $uri )
			$graph->stroke( $uri );
		else
			$graph->stroke();
		$this->map	= $graph->getHTMLImageMap( $id."_imageMap" );
	}

	public function setAntialias( $bool )
	{
		$this->antialias	= (bool) $bool;
	}

	public function setCenter( $x, $y )
	{
		$this->centerX			= $x;
		$this->centerY			= $y;
	}
	
	public function setColors( $colors )
	{
		if( !is_array( $colors ) )
			throw new InvalidArgumentException( 'Must be array' );
		$this->colors	= $colors;
	}

	public function setHeading( $heading )
	{
		$this->heading	= $heading;
	}
	
	public function setLegendAlign( $x, $y )
	{
		$this->legendAlignX		= $x;
		$this->legendAlignY		= $y;
	}

	public function setLegendMargin( $x, $y )
	{
		$this->legendMarginX	= $x;
		$this->legendMarginY	= $y;
	}

	public function setLegendShadow( $bool )
	{
		$this->legendShadow	= (bool) $bool;
	}

	public function setShadow( $bool )
	{
		$this->shadow	= (bool) $bool;
	}

	public function setSize( $width, $height )
	{
		$this->width	= $width;
		$this->height	= $height;
	}
}
?>