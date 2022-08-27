<?php
/**
 *	Renderer graphs in DOT language (Graphviz).
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_Image_Graphviz
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 */

namespace CeusMedia\Common\UI\Image\Graphviz;

use CeusMedia\Common\FS\File\Editor as FileEditor;
use CeusMedia\Common\FS\File\Reader as FileReader;
use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Renderer graphs in DOT language (Graphviz).
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graphviz
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.6
 *	@todo			implement support for other image formats than PNG
 *	@todo			implement support for SVG and PDF
 */
class Renderer
{
	protected $layoutEngine			= "dot";
	protected $graph;
	protected $gvInstalled			= NULL;


	static public function checkGraphvizSupport(){
		exec( 'dot -V', $results, $code );
		if( $code == 127 )
			return FALSE;
		return TRUE;
	}

	public function __construct( Graph $graph, $layoutEngine = "dot" ){
		$this->setGraph( $graph );
		$this->setLayoutEngine( $layoutEngine );
		$this->gvInstalled	= $this->checkGraphvizSupport();
	}

	public function getGraph(){
		return $this->graph;
	}

	public function getLayoutEngines(){
		return ["circo", "dot", "fdp", "neato", "osage", "sfdp", "twopi"];
	}

	public function getMap( $type = "cmapx_np", $graphOptions = [] ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
		if( !in_array( $type, ["ismap", "imap", "imap_np", "cmap", "cmapx", "cmapx_np"] ) )
			throw new OutOfBoundsException( 'Map type "'.$type.'" is unknown or not supported' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->graph->save( $tempFile, $graphOptions );
		exec( $this->layoutEngine.' -O -T'.$type.' '.$tempFile );
		unlink( $tempFile );
		$mapFile	= $tempFile.".".$type;
		if( !file_exists( $mapFile ) )
			throw new RuntimeException( 'Map file could not been created' );
		$map	= FileReader::load( $mapFile );
		unlink( $mapFile );
		return $map;
	}

	public function printGraph( $type = "png", $graphOptions = [] ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->saveAsImage( $tempFile, $type, $graphOptions );
		$image		= FileReader::load( $tempFile );
		@unlink( $tempFile );
		$mimeType	= "image/png";
		if( $type == "jpg" )
			$mimeType	= "image/jpeg";
		if( $type == "svg" )
			$mimeType	= "image/svg+xml";
		header( 'Content-type: '.$mimeType );
		print( $image );
		exit;
	}

	public function saveAsImage( $fileName, $type = "png", $graphOptions = [] ){
		if( !$this->gvInstalled )
			throw new RuntimeException( 'Missing graphViz' );
#		if( !in_array( $type, ["ismap", "imap", "imap_np", "cmap", "cmapx", "cmapx_np"] ) )
#			throw new OutOfBoundsException( 'Map type "'.$type.'" is unknown or not supported' );
		$tempFile	= tempnam( sys_get_temp_dir(), 'CMC_GV_' );
		$this->graph->save( $tempFile, $graphOptions );
		exec( $this->layoutEngine.' -O -T'.$type.' '.$tempFile );
		unlink( $tempFile );
		if( !file_exists( $tempFile.".".$type ) )
			throw new RuntimeException( 'Image file could not been created' );
		$file	= new FileEditor( $tempFile.".".$type );
		return $file->rename( $fileName );
	}

	public function setGraph( Graph $graph ){
		$this->graph	= $graph;
	}

	public function setLayoutEngine( $layoutEngine ){
		if( !in_array( $layoutEngine, $this->getLayoutEngines() ) )
			throw new OutOfBoundsException( 'Invalid layout engine "'.$layoutEngine.'"' );
		$this->layoutEngine	= $layoutEngine;
	}
}
