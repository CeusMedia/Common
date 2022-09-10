<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\UI\Template as Template;
use InvalidArgumentException;

/**
 *	...
 *	Attention: Needs jpgraph (https://jpgraph.net/)
 *	Possible Package: https://packagist.org/packages/amenadiel/jpgraph
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PieGraph
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
	protected $map;

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

	public function __construct( int $width, int $height )
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
		return Template::render( 'templates/graph.html', $data );
	}

	public function buildImage( string $id, array $data, ?string $uri = NULL )
	{
		if( empty( $data['values'] ) )
			return "No entries found";
		$graph = new jpgrapth_PieGraph( $this->width, $this->height, $id );
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
		$p1 = new jpgrapth_PiePlot3D( $data['values'] );
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

	public function setAntialias( bool $bool ): self
	{
		$this->antialias	= $bool;
		return $this;
	}

	public function setCenter( int $x, int $y ): self
	{
		$this->centerX		= $x;
		$this->centerY		= $y;
		return $this;
	}

	public function setColors( array $colors ): self
	{
		$this->colors	= $colors;
		return $this;
	}

	public function setHeading( string $heading ): self
	{
		$this->heading	= $heading;
		return $this;
	}

	public function setLegendAlign( int $x, int $y ): self
	{
		$this->legendAlignX		= $x;
		$this->legendAlignY		= $y;
		return $this;
	}

	public function setLegendMargin( int $x, int $y ): self
	{
		$this->legendMarginX	= $x;
		$this->legendMarginY	= $y;
		return $this;
	}

	public function setLegendShadow( bool $bool ): self
	{
		$this->legendShadow	= $bool;
		return $this;
	}

	public function setShadow( bool $bool ): self
	{
		$this->shadow	= $bool;
		return $this;
	}

	public function setSize( int $width, int $height ): self
	{
		$this->width	= $width;
		$this->height	= $height;
		return $this;
	}
}
