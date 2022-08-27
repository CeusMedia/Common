<?php
/**
 *	Builds a Graph based on Configuration and Graph Data.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			16.04.2008
 */

namespace CeusMedia\Common\UI\Image\Graph;

/**
 *	Builds a Graph based on Configuration and Graph Data.
 *	Attention: Needs jpgraph (https://jpgraph.net/)
 *	Possible Package: https://packagist.org/packages/amenadiel/jpgraph
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			16.04.2008
 */
class Builder
{
	/**
	 *	Builds and returns the Graph Object.
	 *	@access		protected
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		jpgraph_Graph
	 */
	protected static function buildGraph( $config, $data )
	{
		$graph = new jpgraph_Graph( $config['width'], $config['height'], 'auto' );
		$graph->setScale( self::getConfigValue( $config, 'scale' ) );
		$graph->img->SetAntiAliasing( self::getConfigValue( $config, 'image.antialias', FALSE ) );

		Components::setTitle( $graph, $config );
		Components::setSubTitle( $graph, $config );
		Components::setLegend( $graph, self::getSubConfig( $config, "legend." ) );
		Components::setFrame( $graph, $config );
		Components::setShadow( $graph, $config );
		Components::setAxis( $graph->xaxis, self::getSubConfig( $config, 'x.axis.' ), $data );
		Components::setAxis( $graph->yaxis, self::getSubConfig( $config, 'y1.axis.' ), $data );

		self::setUpMargin( $graph, $config );
		self::setUpGrid( $graph, $config, $data );
		self::setUpPlots( $graph, $config, $data );
		return $graph;
	}

	/**
	 *	Builds and prints Graph Image.
	 *	@access		public
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	public static function buildImage( $config, $data )
	{
		$graph	= self::buildGraph( $config, $data );
		$graph->stroke();
	}

	/**
	 *	Creates a Plot working like a Plot Factory.
	 *	@access		protected
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@param		string		$prefix			Configuration Prefix, must end with a Point
	 *	@return		mixed
	 */
	protected static function createPlot( $config, $data, $prefix )
	{
		$plotClass	= $config[$prefix.'type'];
		$plotClass	= '\\CeusMedia\\Common\\UI\\Image\\Graph\\'.$plotClass;
		$plotObject	= new $plotClass;
		$plotConf	= self::getSubConfig( $config, $prefix );
		return $plotObject->buildPlot( $plotConf, $data );
	}

	/**
	 *	Alias for Components::getConfigValue.
	 *	@access		protected
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		string		$key			Parameter Key
	 *	@param		mixed		$default		Default Value to set if empty or not set
	 *	@return		mixed
	 */
	protected static function getConfigValue( $config, $key, $default = NULL )
	{
		return Components::getConfigValue( $config, $key, $default );
	}

	/**
	 *	Alias for Components::getSubConfig.
	 *	@access		protected
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		string		$prefix			Parameter Prefix, must end mit a Point
	 *	@return		array
	 */
	protected static function getSubConfig( $config, $prefix )
	{
		return Components::getSubConfig( $config, $prefix );
	}

	/**
	 *	Builds and saves Graph Image to an Image File.
	 *	@access		public
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	public static function saveImage( $fileName, $config, $data, $invertOrder = FALSE )
	{
		$graph	= self::buildGraph( $config, $data, $invertOrder );
		$graph->stroke( $fileName );
	}

	/**
	 *	Adds a Grid to Graph.
	 *	@access		protected
	 *	@static
	 *	@param		Graph		$graph			Graph Object to work on
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	protected static function setUpGrid( $graph, $config, $data )
	{
		$gridDepth	= self::getConfigValue( $config, "grid.depth", DEPTH_BACK );
		$graph->setGridDepth( $gridDepth );
		Components::setGrid( $graph->xgrid, self::getSubConfig( $config, 'x.grid.' ), $data );
		Components::setGrid( $graph->ygrid, self::getSubConfig( $config, 'y.grid.' ), $data );
	}

	/**
	 *	Adds Margin to Graph.
	 *	@access		protected
	 *	@static
	 *	@param		jpgraph_Graph		$graph			Graph Object to work on
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	protected static function setUpMargin( $graph, $config )
	{
		$marginLeft		= self::getConfigValue( $config, 'margin.left', 0 );
		$marginRight	= self::getConfigValue( $config, 'margin.right', 0 );
		$marginTop		= self::getConfigValue( $config, 'margin.top', 0 );
		$marginBottom	= self::getConfigValue( $config, 'margin.bottom', 0 );
		$marginColor	= self::getConfigValue( $config, 'margin.color' );
		$graph->setMargin( $marginLeft, $marginRight, $marginTop, $marginBottom );
		if( $marginColor )
			$graph->setMarginColor( $marginColor );
	}

	/**
	 *	Adds Plots to Graph.
	 *	@access		protected
	 *	@static
	 *	@param		jpgraph_Graph		$graph			Graph Object to work on
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	protected static function setUpPlots( $graph, $config, $data )
	{
		//  --  CREATE PLOTS  --  //
		$plots	= [
			'y2'	=> [],
			'y1'	=> [],
		];
		$nr	= 1;
		while( 1 )
		{
			$prefix		= 'y1.'.$nr++.'.';
			if( !isset( $config[$prefix.'type'] ) )
				break;
			if( $plot = self::createPlot( $config, $data, $prefix ) )
				array_unshift( $plots['y1'], $plot );
		}

		$scaleY2	= self::getConfigValue( $config, "y2.scale" );
		if( $scaleY2 )
		{
			$graph->setY2Scale( $scaleY2 );
			Components::setAxis( $graph->y2axis, self::getSubConfig( $config, 'y2.axis.' ), $data );
			$nr	= 1;
			while( 1 )
			{
				$prefix		= 'y2.'.$nr++.'.';
				if( !isset( $config[$prefix.'type'] ) )
					break;
				if( $plot = self::createPlot( $config, $prefix ) )
					array_unshift( $plots['y2'], $plot );
			}
		}
		foreach( $plots as $axis => $axisPlots )
			foreach( $axisPlots as $plot )
				( $axis == 'y2' ) ? $graph->addY2( $plot ) : $graph->add( $plot );
	}
}
