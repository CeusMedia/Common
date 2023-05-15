<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */

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
 */

namespace CeusMedia\Common\UI\Image\Graphviz;

use CeusMedia\Common\FS\File\Editor as FileEditor;
use CeusMedia\Common\FS\File\Reader as FileReader;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Renders graphs in DOT language (Graphviz).
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graphviz
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			implement support for other image formats than PNG
 *	@todo			implement support for SVG and PDF
 */
class Renderer
{
	protected string $layoutEngine		= "dot";

	protected Graph $graph;

	protected bool $gvInstalled;

	public static function checkGraphvizSupport(): bool
	{
		exec( 'dot -V', $results, $code );
		if( $code == 127 )
			return FALSE;
		return TRUE;
	}

	public function __construct( Graph $graph, string $layoutEngine = 'dot' )
	{
		$this->setGraph( $graph );
		$this->setLayoutEngine( $layoutEngine );
		$this->gvInstalled	= $this->checkGraphvizSupport();
	}

	public function getGraph(): ?Graph
	{
		return $this->graph;
	}

	public function getLayoutEngines(): array
	{
		return ["circo", "dot", "fdp", "neato", "osage", "sfdp", "twopi"];
	}

	public function getMap( string $type = "cmapx_np", array $graphOptions = [] ): string
	{
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

	public function printGraph( string $type = "png", array $graphOptions = [] ): void
	{
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

	public function saveAsImage( string $fileName, string $type = "png", array $graphOptions = [] ): bool
	{
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

	public function setGraph( Graph $graph ): self
	{
		$this->graph	= $graph;
		return $this;
	}

	public function setLayoutEngine( string $layoutEngine ): self
	{
		if( !in_array( $layoutEngine, $this->getLayoutEngines() ) )
			throw new OutOfBoundsException( 'Invalid layout engine "'.$layoutEngine.'"' );
		$this->layoutEngine	= $layoutEngine;
		return $this;
	}
}
