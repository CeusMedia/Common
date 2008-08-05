<?PHP
/**
 *	This is a pie visualization class. 
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the UI_SVG_Chart class.
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 */
/**
 *	This is a pie visualization class. 
 *	You shouldn´t use this class alone, but you can.
 *	You should only use it in corporation with the UI_SVG_Chart class.
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 */
class UI_SVG_PieGraph
{
	/**
	 *	This function generates a pie chart of the given data.
	 *	It uses the technology provided by the {@link Chart} class to generate a pie chart from the given data.<br>
	 *	You can pass the following options to the this method  by using {@link Chart::get()}:<br>
	 *	* cx & cy - The coordinates of the center point of the pie chart.<br>
	 *	* r - The radius of the pie chart.
	 *	@access		public
	 *	@param		array		An array of options, see {@link Chart::get()}.
	 *	@return		string
	 *	@see Chart::get()
	 */
	public function build( $options )
	{
		$cx	= isset( $options["cx"] ) ? $options["cx"] : 200;
		$cy	= isset( $options["cy"] ) ? $options["cy"] : 200;
		$r	= isset( $options["r"] ) ? $options["r"] : 150;
		$x1 = $cx;
		$y1 = $cy - $r;
		$alpha	= 0;
		$output	= "";
		$count	= 0;
		
		$data	= $this->chart->data;
		$sum	= 0;
		foreach( $data as $obj )
			$sum += $obj->value;
		
		foreach( $data as $obj )
		{
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
			$output .= UI_HTML_Tag::create( "path", NULL, $attributes );
			
			$x1	= $x2;
			$y1	= $y2;
			$count++;
		}
		
		if( isset( $this->options["legend"] ) && $options["legend"] )
		{
			$x = $cx + $r * 1.2;
			$y = $cy - $r;
			$this->options["legend"]	= array(
				"x"	=> $x,
				"y"	=> $y,
			);
		}
		$graph	= UI_HTML_Tag::create( "g", $output );
		return $graph;
	}
}
?>