<?php
/**
 *	Abstract Generator Class for several Graph Generator Appications.
 *	@package		ui.image.graph
 *	@uses			UI_Template
 *	@uses			UI_Image_Graph_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.04.2008
 *	@version		0.1
 */
import( 'de.ceus-media.ui.Template' );
import( 'de.ceus-media.ui.image.graph.Builder' );
/**
 *	Abstract Generator Class for several Graph Generator Appications.
 *	@package		ui.image.graph
 *	@uses			UI_Template
 *	@uses			UI_Image_Graph_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.04.2008
 *	@version		0.1
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
			throw RuntimeException( 'Path to JpGraph has not been set.' );
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
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function prepareConfig();
	
	/**
	 *	Prepares Graph Data, needs to be implemented for each Generator.
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