<?php
import( 'de.ceus-media.ui.image.Image' );
import( 'de.ceus-media.math.analysis.CompactInterval' );
import( 'de.ceus-media.StopWatch' );
/**
 *	Paints Formula Diagram
 *	@package		ui.image
 *	@extends		Image
 *	@uses			CompactInterval
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Paints Formula Diagram
 *	@package		ui.image
 *	@extends		Image
 *	@uses			CompactInterval
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class UI_Image_FormulaDiagram extends Image
{
	protected $intervalX;
	protected $intervalY;

	protected $arcRed	= 0;
	protected $arcGreen	= 0;
	protected $arcBlue	= 0;

	protected $backRed		= 255;
	protected $backGreen	= 255;
	protected $backBlue		= 255;

	protected $gridRed		= 247;
	protected $gridGreen	= 247;
	protected $gridBlue		= 247;

	protected $grid	= false;
	protected $formula;
	protected $zoomX		= 1;
	protected $zoomY		= 1;
	protected $step;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		CompactInterval		Interval on X Axis
	 *	@param		CompactInterval		Interval on Y Axis
	 *	@param		Formula				Formula to display
	 *	@param		float				Dots between to 2 Points.
	 *	@param		int					Dots between Grid Lines (0 for 'no Grid')
	 *	@return		void
	 */
	public function __construct( $intervalX, $intervalY, $formula, $step = 1, $grid = 0 )
	{
		$this->intervalX	= $grid ? new CompactInterval( $intervalX->getStart(), $intervalX->getEnd() + 1 ) : $intervalX;
		$this->intervalY	= $grid ? new CompactInterval( $intervalY->getStart(), $intervalY->getEnd() + 1 ) : $intervalY;
		$this->formula		= $formula;
		$this->step			= (real) $step;
		$this->grid			= (int) $grid;
		$this->zoomX		= 1;
		$this->zoomY		= 1;
	}

	public function draw( $stop = false )
	{
		if( $stop )
			$st = new StopWatch ();
		$xStart		= $this->intervalX->getStart();
		$xEnd		= $this->intervalX->getEnd();
		$xDiam		= $this->intervalX->getDiam();
		$yStart		= $this->intervalY->getStart();
		$yEnd		= $this->intervalY->getEnd();
		$yDiam		= $this->intervalY->getDiam();

		$this->create ($xDiam, $yDiam);

#		$col	= $this->allocateColor( $this->backRed, $this->backGreen, $this->backBlue );
		$col1	= $this->allocateColor( $this->arcRed, $this->arcGreen, $this->arcBlue );
		$grcol	= $this->allocateColor( $this->gridRed, $this->gridGreen, $this->gridBlue );
		
		if( $this->grid )
		{
			for( $i=0; $i<$xDiam; $i+=$this->grid )									//  horizontal Grid Lines
				$this->drawLine( $i, 0, $i, $yDiam, $grcol );
			for( $i=0; $i<$yDiam; $i+=$this->grid )									//  vertical Grid Lines
				$this->drawLine( 0, $i, $xDiam, $i,$grcol );
		}
		if( $xStart <= 0 && 0 < $xEnd )
		{
			$this->drawLine( abs( $xStart ), 0, abs( $xStart ), $yDiam, $col1 );
			for( $i=50; $i<$yDiam-1; $i+=50 )
				$this->drawString( abs( $xStart )+5, $i - ( ( strlen( $i ) - 1 ) * 5 ), ( $i + $yStart ) / $this->zoomY, 1, $col1 );	
		}			
		if( $yStart <= 0 && 0 < $yEnd )
		{
			$this->drawLine( 0, abs( $yStart ), $xDiam, abs( $yStart ), $col1 );
			for( $i=50; $i<$xDiam-1; $i+=50 )
				$this->drawString( $i - ( ( strlen( $i ) - 1 ) * 5 ), abs( $yStart ) + 5, ( $i + $xStart ) / $this->zoomX, 1, $col1 );	
		}			
		ob_start();
		$j=0;
		for( $x=$xStart; $x<$xEnd; $x+=$this->step )
		{
			$useX = $x / $this->zoomX / $this->yscale();
			if( false !== ( $value = $this->formula->getValue( $useX ) ) )
			{
				$x_points[$j] = $x + abs( $xStart );
				$y_points[$j] = ( ( -1 ) * $this->yscale() * $value * $this->zoomY ) + abs( $yStart );
				if( 0 <= $y_points[$j] && $y_points[$j] <= $yDiam )
					$this->drawPixel( $x_points[$j], $y_points[$j] , $col1 );
				else
					print_m( "not drawn: ".$x_points[$j].":".$y_points[$j]." I[".$yStart.";".$yEnd."]" );
			}
			$j++;
		}
		ob_end_clean();
		
		$this->drawString( 15, $yDiam-15, "f(x)=".$this->formula->getExpression(), 2, $col1 );	
		if( $stop )
			$this->drawString( $xDiam-50, $yDiam-15, round( $st->stop(), 0 )."ms", 2, $col1 );	
		$this->show();
	}

	public function setArcColor( $r, $g, $b )
	{
		$this->arcRed	= $r;
		$this->arcGreen	= $g;
		$this->arcBlue	= $b;
	}

	public function setBackgroundColor( $r, $g, $b )
	{
		$this->backRed		= $r;
		$this->backGreen	= $g;
		$this->backBlue		= $b;
	}

	public function setGridColor( $r, $g, $b )
	{
		$this->gridRed		= $r;
		$this->gridGreen	= $g;
		$this->gridBlue		= $b;
	}

	public function setZoomX( $zoom )
	{
		$this->zoomX	= $zoom;
	}

	public function setZoomY( $zoom )
	{
		$this->zoomY	= $zoom;
	}

	public function yscale()
	{
/*		$exp = $this->formula->getExpression();
		if((substr_count($exp,'sin')>0) || (substr_count($exp,'cos')>0) || (substr_count($exp,'tan')>0))
			return(100);
		else*/
			return(1);
	}
}				
?>