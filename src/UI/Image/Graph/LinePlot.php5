<?php
/**
 *	Builds a Line Plot for Graph.
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
 *	@package		UI.Image.Graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.04.2008
 *	@version		$Id$
 */
/**
 *	Builds a Line Plot for Graph.
 *	@category		cmClasses
 *	@package		UI.Image.Graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.04.2008
 *	@version		$Id$
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