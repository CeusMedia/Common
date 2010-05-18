<?php
/**
 *	Abstract Generator Class for several Graph Generator Appications.
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
import( 'de.ceus-media.ui.Template' );
import( 'de.ceus-media.ui.image.graph.Builder' );
/**
 *	Abstract Generator Class for several Graph Generator Appications.
 *	@category		cmClasses
 *	@package		UI.Image.Graph
 *	@abstract
 *	@uses			UI_Template
 *	@uses			UI_Image_Graph_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.04.2008
 *	@version		$Id$
 */
abstract class UI_Image_Graph_Generator
{
	protected $config;
	protected $timestampData;
	protected $pathJpGraph		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->prepareConfig();
		$this->prepareData();
	}

	/**
	 *	Builds the Image based on set Configuration and Data and prints it out.
	 *	@access		public
	 *	@return		void
	 */
	public function buildImage()
	{
		UI_Image_Graph_Builder::buildImage( $this->config, $this->data );
	}

	/**
	 *	Extends already set Configuration Data by setting in Labels for Placeholders.
	 *	@access		protected
	 *	@param		array		$labels			Map of Labels to set in
	 *	@param		array		$request		Request Parameters to extend Labels
	 *	@return		void
	 */
	protected function extendConfigByLabels( $labels, $request = array() )
	{
		foreach( $request as $key => $value )
		{
			$key	= str_replace( "_", ".", $key );
			if( array_key_exists( $key, $labels ) )
				$labels[$key]	= $value;
		}

		foreach( $this->config as $key => $value )
			$this->config[$key]	= UI_Template::renderString( $value, $labels );
	}

	/**
	 *	Extends already set Configuration Data by Request Parameters.
	 *	@access		protected
	 *	@param		array		$parameters		Map of Request Parameters
	 *	@return		void
	 */
	protected function extendConfigByRequest( $parameters )
	{
		foreach( $parameters as $key => $value )
		{
			$key	= str_replace( "_", ".", $key );
			if( array_key_exists( $key, $this->config ) )
				$this->config[$key]	= $value;
		}
	}

	/**
	 *	Loads JpGraph Base Class and all given Plot Type Classes.
	 *	@access		protected
	 *	@param		array		$type			List of Plot Types (e.g. 'line' for 'graph_lineplot.php')
	 *	@return		void
	 */
	protected function loadJpGraph( $types = array() )
	{
		if( $this->pathJpGraph === NULL )
			throw new RuntimeException( 'Path to JpGraph has not been set.' );
		$types	= explode( ",", $types );
		require_once( $this->pathJpGraph."src/jpgraph.php" );
		foreach( $types as $type )
		{
			$plotType	= strtolower( trim( $type ) );
			$fileName	= $this->pathJpGraph."src/jpgraph_".$plotType.".php";
			require_once( $fileName );
		}
	}

	/**
	 *	Prepares Graph Configuration, needs to be implemented for each Generator.
	 *	@abstract
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function prepareConfig();
	
	/**
	 *	Prepares Graph Data, needs to be implemented for each Generator.
	 *	@abstract
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function prepareData();

	/**
	 *	Builds the Image based on set Configuration and Data and saves it to a File.
	 *	@access		public
	 *	@param		string		$fileName		File Name to store Image to
	 *	@return		void
	 */
	public function saveImage( $fileName )
	{
		UI_Image_Graph_Builder::saveImage( $fileName, $this->config, $this->data );
	}

	/**
	 *	Sets Path to JpGraph.
	 *	@param		string		$path			Path to JpGraph
	 *	@return		void
	 */
	public function setJpGraphPath( $path )
	{
		$this->pathJpGraph	= $path;
	}

	/**
	 *	Sets Graph Data instead of using self::prepare.
	 *	@access		public
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	public function setData( $data )
	{
		$this->data	= $data;
	}
}
?>