<?php
/**
 *	...
 *	@category		cmClasses
 *	@package		ui.image
 *	@uses			UI_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
require_once( JPGRAPH_PATH.'jpgraph.php' );
require_once( JPGRAPH_PATH.'jpgraph_pie.php');
require_once( JPGRAPH_PATH.'jpgraph_pie3d.php' );
import( 'de.ceus-media.ui.Template' );
/**
 *	...
 *	@category		cmClasses
 *	@package		ui.image
 *	@uses			UI_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
class UI_Image_PieGraph
{
	protected $shadow			= FALSE;
	protected $antialias		= TRUE;
	protected $heading			= "Heading";
		
	protected $legendMarginX	= 0.008;
	protected $legendMarginY	= 0.005;
	protected $legendAlignX		= 'right';
	protected $legendAlignY		= 'top';
	protected $legendShadow		= FALSE;
	
	protected $centerX	= 0.43;
	protected $centerY	= 0.58;
	protected $width	= 600;
	protected $height	= 400;
	
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
		$this->width	= $width;
		$this->height	= $height;
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
		$graph->SetShadow( $this->shadow );
		$graph->SetAntiAliasing( $this->antialias );
		$graph->title->Set( $this->heading );
//		$graph->title->SetFont( FF_VERDANA,FS_NORMAL, 11 );
		$graph->title->SetFont( FF_FONT1, FS_BOLD );
#		$graph->title->Pos();
		$graph->legend->Pos( $this->legendMarginX, $this->legendMarginY, $this->legendAlignX, $this->legendAlignY );
		$graph->legend->SetShadow( $this->legendShadow );
//		$graph->legend->SetShadow( false );
//		$graph->legend->SetFont( FF_VERDANA, FS_NORMAL, 8 );
		$graph->legend->SetFont( FF_FONT1, FS_NORMAL, 8 );
		$p1 = new PiePlot3D( $data['values'] );
		$p1->value->SetFormat( "%d%%" );
		$p1->value->Show();
//		$p1->value->SetFont( FF_VERDANA, FS_NORMAL, 8 );
		$p1->value->SetFont( FF_FONT1, FS_NORMAL, 7 );
		$p1->SetLegends( array_unique( $data['legends'] ) );
		$p1->SetCSIMTargets( $data['uris'], $data['labels'] );
		$p1->setSliceColors( $this->colors );
		$p1->SetCenter( $this->centerX, $this->centerY );
		$graph->Add( $p1 );
		if( $uri )
			$graph->Stroke( $uri );
		else
			$graph->Stroke();
		$this->map	= $graph->GetHTMLImageMap( $id."_imageMap" );
		unset( $graph );
	}
}

/**
 *	Builds different Graphs with Configuration.
 *	@package		tools.BugTracker
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.ui.Template' );
/**
 *	Builds different Graphs with Configuration.
 *	@package		tools.BugTracker
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			UI_Template
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		$Id$
 */
class GraphViews
{
}
?>