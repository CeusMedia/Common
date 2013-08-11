<?php
/**
 *	Renderer graphs in DOT language (Graphviz).
 *
 *	Copyright (c) 2013 Christian Würker (ceusmedia.com)
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
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Renderer graphs in DOT language (Graphviz).
 *
 *	@category		cmClasses
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 *	@todo			implement support for other image formats than PNG
 *	@todo			implement support for SVG and PDF
 */
class UI_Image_Graphviz_Renderer{

	protected $layoutEngine			= "dot";
	protected $graph;
	protected $gvInstalled			= NULL;
	
	
	static public function checkGraphvizSupport(){
		exec( 'dot -V', $results, $code );
		if( $code == 127 )
			return FALSE;
		return TRUE;
	}

	public function __construct( UI_Image_Graphviz_Graph $graph, $layoutEngine = "dot" ){
		$this->setGraph( $graph );
		$this->setLayoutEngine( $layoutEngine );
		$this->gvInstalled	= $this->checkGraphvizSupport();
	}
	
	public function getGraph(){
		return $this->graph;
	}

	public function getLayoutEngines(){
		return array( "circo", "dot", "fdp", "neato", "osage", "sfdp", "twopi" );	
	}
	
	public function getMap( $type = "cmapx_np", $graphOptions = array() ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
		if( !in_array( $type, array( "ismap", "imap", "imap_np", "cmap", "cmapx", "cmapx_np" ) ) )
			throw new OutOfBoundsException( 'Map type "'.$type.'" is unknown or not supported' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->graph->save( $tempFile, $graphOptions );
		exec( $this->layoutEngine.' -O -T'.$type.' '.$tempFile );
		unlink( $tempFile );
		$mapFile	= $tempFile.".".$type;
		if( !file_exists( $mapFile ) )
			throw new RuntimeException( 'Map file could not been created' );
		$map	= File_Reader::load( $mapFile );
		unlink( $mapFile );
		return $map;
	}
	
	public function printGraph( $type = "png", $graphOptions = array() ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->saveAsImage( $tempFile, $type, $graphOptions );
		$image		= File_Reader::load( $tempFile );
		@unlink( $tempFile );
		header( 'Content-type: image/png' );
		print( $image );
		exit;
	}

	public function saveAsImage( $fileName, $type = "png", $graphOptions = array() ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
#		if( !in_array( $type, array( "ismap", "imap", "imap_np", "cmap", "cmapx", "cmapx_np" ) ) )
#			throw new OutOfBoundsException( 'Map type "'.$type.'" is unknown or not supported' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->graph->save( $tempFile, $graphOptions );
		exec( $this->layoutEngine.' -O -T'.$type.' '.$tempFile );
		unlink( $tempFile );
		if( !file_exists( $tempFile.".".$type ) )
			throw new RuntimeException( 'Image file could not been created' );
		$file	= new File_Editor( $tempFile.".".$type );
		return $file->rename( $fileName );
	}

	public function setGraph( UI_Image_Graphviz_Graph $graph ){
		$this->graph	= $graph;
	}

	public function setLayoutEngine( $layoutEngine ){
		if( !in_array( $layoutEngine, $this->getLayoutEngines() ) )
			throw new OutOfBoundsException( 'Invalid layout engine "'.$layoutEngine.'"' );
		$this->layoutEngine	= $layoutEngine;
	}
}
?>