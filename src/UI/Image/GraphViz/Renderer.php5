<?php
class UI_Image_GraphViz_Renderer{

	static public $renderer	= "dot";
	
	static public function checkGraphVizSupport(){
		exec( self::$renderer.' --help', $results, $code );
		if( $code == 127 )
			return FALSE;
		return TRUE;
	}
	
	static public function convertGraphToImage( UI_Image_GraphViz_Graph $graph, $fileName ){
		if( !self::checkGraphVizSupport() )
			throw new RuntimeException( 'Missing graphViz' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC' );
		File_Writer::save( $tempFile, $graph->render() );
		exec( self::$renderer.' -O -Tpng '.$tempFile );
		unlink( $tempFile );
		rename( $tempFile.".png", $fileName );
	}

	public function printGraph( UI_Image_GraphViz_Graph $graph ){
		if( !self::checkGraphVizSupport() )
			throw new RuntimeException( 'Missing graphViz' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC' );
		self::convertGraphToImage( $graph, $tempFile );
		$image		= File_Reader::load( $tempFile );
		@unlink( $tempFile );
		header( 'Content-type: image/png' );
		print( $image );
		exit;
	}
}
?>