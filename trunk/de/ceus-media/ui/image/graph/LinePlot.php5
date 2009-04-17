<?php
/**
 *	Builds a Line Plot for Graph.
 *	@package		ui.image.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.04.2008
 *	@version		0.1
 */
/**
 *	Builds a Line Plot for Graph.
 *	@package		ui.image.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.04.2008
 *	@version		0.1
 */
class UI_Image_Graph_LinePlot
{
	/**
	 *	Builds and returns Line Plot.
	 *	@access		public
	 *	@param		array		$config			Graph Configuration
	 *	@param		array		$data			Graph Data
	 *	@return		LinePlot
	 */
	public function buildPlot( $config, $data )
	{
		$graphClass	= $config['type'];
		if( is_string( UI_Image_Graph_Components::getConfigValue( $config, 'data' ) ) )
		{
			if( isset( $data[$config['data']] ) )
			{
				if( !$data[$config['data']] )
					return NULL;
				$graphData	= $data[$config['data']];
			}
			else if( $config['data'] )
				throw new Exception( 'Data source "'.$config['data'].'" is not available.' );
			else
				return NULL;
		}
		else
			$graphData	= array_fill( 0, count( $data['x'] ), $config['data'] );

		$legend		= UI_Image_Graph_Components::getConfigValue( $config, 'legend' );
		$color		= UI_Image_Graph_Components::getConfigValue( $config, 'color' );
		$weight		= UI_Image_Graph_Components::getConfigValue( $config, 'weight' );
		$fillColor	= UI_Image_Graph_Components::getConfigValue( $config, 'fill.color' );

		$graphType	= new $graphClass( $graphData );

		if( $legend )
			$graphType->setLegend( $legend );
		if( $color )
			$graphType->setColor( $color );
		if( $weight )
			$graphType->setWeight( $weight );
		if( $fillColor )
			$graphType->setFillColor( $fillColor );

		UI_Image_Graph_Components::setValue( $graphType, $config );
		UI_Image_Graph_Components::setMark( $graphType, $config );
		return $graphType;
	}
}
?>