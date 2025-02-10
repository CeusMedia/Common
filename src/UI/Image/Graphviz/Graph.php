<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */

/**
 *	Graph data class for DOT language (Graphviz).
 *
 *	Copyright (c) 2015-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graphviz
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image\Graphviz;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use DomainException;
use InvalidArgumentException;

/**
 *	Graph data class for DOT language (Graphviz).
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image_Graphviz
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Graph
{
	protected string $type			= "digraph";

	protected array $edges			= [];

	protected array $nodes			= [];

	protected array $nodeOptions	= [];

	protected array $edgeOptions	= [];

	protected string $id;

	protected array $options		= [];

	public function __construct( ?string $id = NULL, array $options = [] )
	{
		if( $id )
			$this->setId( $id );
		$this->setDefaultOptions( $options );
	}

	public function __toString(): string
	{
		return $this->render();
	}

	public function addEdge( string $nodeSource, string $nodeTarget, array $options = [] ): self
	{
		$nodeSourceId	= $this->sanitizeNodeName( $nodeSource );
		$nodeTargetId	= $this->sanitizeNodeName( $nodeTarget );
		if( !array_key_exists( $nodeSourceId, $this->nodes ) )
			throw new DomainException( 'Source node "'.$nodeSource.'" (ID: '.$nodeSourceId.') is not existing' );
		if( !array_key_exists( $nodeTargetId, $this->nodes ) )
			throw new DomainException( 'Target node "'.$nodeTarget.'" (ID: '.$nodeTargetId.') is not existing' );
		if( !isset( $this->edges[$nodeSourceId] ) )
			$this->edges[$nodeSourceId]	= [];
		$this->edges[$nodeSourceId][$nodeTargetId]	= $options;
		return $this;
	}

	public function addNode( string $name, array $options = [] ): self
	{
		$nodeId	= $this->sanitizeNodeName( $name );
		if( array_key_exists( $nodeId, $this->nodes ) )
			throw new DomainException( 'Node "'.$name.'" is already existing' );
		if( !isset( $options['label'] ) )
			$options['label']	= $name;
		$this->nodes[$nodeId]	= $options;
		return $this;
	}

	public function getDefaultEdgeOptions(): array
	{
		return $this->edgeOptions;
	}

	public function getDefaultNodeOptions(): array
	{
		return $this->nodeOptions;
	}

	public function getDefaultOptions(): array
	{
		return $this->options;
	}

	public function getEdges(): array
	{
		return $this->edges;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getNodeOptions( string $name ): ?array
	{
		if( !$this->hasNode( $name ) )
			return NULL;
		return $this->nodes[$this->sanitizeNodeName( $name )];
	}

	public function getNodes(): array
	{
		return $this->nodes;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function hasEdge( string $nameSource, string $nameTarget ): bool
	{
		$idSource	= $this->sanitizeNodeName( $nameSource );
		$idTarget	= $this->sanitizeNodeName( $nameTarget );
		return isset( $this->edges[$idSource][$idTarget] );
	}

	public function hasNode( string $name ): bool
	{
		return isset( $this->nodes[$this->sanitizeNodeName( $name )] );
	}

	public function render( array $options = [] ): string
	{
		$edges	= [];
		$nodes	= [];
		foreach( $this->nodes as $name => $nodeOptions )
			$nodes[]	= $name.' ['.$this->renderOptions( $this->nodeOptions, $nodeOptions ).'];';
		foreach( $this->edges as $source => $targets )
			foreach( $targets as $target => $edgeOptions )
				$edges[]	= $source.' -> '.$target.' ['.$this->renderOptions( $this->edgeOptions, $edgeOptions ).']';
		$rules		= array(
			$this->renderOptions( $this->options, $options, "\n\t" ),
			join( "\n\t", $nodes ),
			join( "\n\t", $edges ),
		);
		return $this->type." ".$this->id." {\n\t".join( "\n\t", $rules )."\n}";
	}

	protected function renderOptions( array $options = [], array $overrideOptions = [], string $delimiter = ' ' ): string
	{
		if( is_array( $overrideOptions ) )
			$options	= array_merge( $options, $overrideOptions );
		$list	= [];
		foreach( $options as $key => $value )
			$list[]	= $key.'="'.addslashes( $value ).'"';
		return join( $delimiter, $list );
	}

	protected function sanitizeNodeName( string $name ): string
	{
		$name	= htmlentities( $name );
		return preg_replace( "/[^\w_:]/", "", $name );
	}

	public function save( string $fileName, array $options = [] ): int
	{
		return FileWriter::save( $fileName, $this->render( $options ) );
	}

	public function setDefaultEdgeOptions( array $options ): self
	{
		$this->edgeOptions	= $options;
		return $this;
	}

	public function setDefaultNodeOptions( array $options ): self
	{
		$this->nodeOptions	= $options;
		return $this;
	}

	public function setDefaultOptions( array $options ): self
	{
		$this->options	= $options;
		return $this;
	}

	public function setEdgeOptions( string $nameSource, string $nameTarget, array $options ): self
	{
		if( $this->hasEdge( $nameSource, $nameTarget ) ){
			$idSource	= $this->sanitizeNodeName( $nameSource );
			$idTarget	= $this->sanitizeNodeName( $nameTarget );
			$options	= array_merge( $this->edges[$idSource][$idTarget], $options );
			$this->edges[$idSource][$idTarget]	= $options;
		}
		return $this;
	}

	public function setId( string $id ): self
	{
		$this->id	= $this->sanitizeNodeName( $id );
		return $this;
	}

	public function setNodeOptions( string $name, array $options ): self
	{
		if( $this->hasNode( $name ) ){
			$nodeId	= $this->sanitizeNodeName( $name );
			$this->nodes[$nodeId]	= array_merge( $this->nodes[$nodeId], $options );
		}
		return $this;
	}

	public function setType( string $type ): self
	{
		if( !in_array( $type, ["digraph", "graph"] ) )
			throw new InvalidArgumentException( 'Invalid graph type "'.$type.'"' );
		$this->type		= $type;
		return $this;
	}
}
