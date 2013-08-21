<?php
/**
 *	Graph data class for DOT language (Graphviz).
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
 *	Graph data class for DOT language (Graphviz).
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
class UI_Image_Graphviz_Graph{

	protected $type				= "digraph";
	protected $edges			= array();
	protected $nodes			= array();
	protected $nodeOptions		= array();
	protected $edgeOptions		= array();

	public function __construct( $id = NULL, $options = array() ){
		if( $id )
			$this->setId( $id );
		$this->setDefaultOptions( $options );
	}

	public function __toString(){
		return $this->render();
	}
	
	public function addEdge( $nodeSource, $nodeTarget, $options = array() ){
		$nodeSourceId	= $this->sanitizeNodeName( $nodeSource );
		$nodeTargetId	= $this->sanitizeNodeName( $nodeTarget );
		if( !array_key_exists( $nodeSourceId, $this->nodes ) )
			throw new DomainException( 'Source node "'.$nodeSource.'" (ID: '.$nodeSourceId.') is not existing' );
		if( !array_key_exists( $nodeTargetId, $this->nodes ) )
			throw new DomainException( 'Target node "'.$nodeTarget.'" (ID: '.$nodeTargetId.') is not existing' );
		if( !isset( $this->edges[$nodeSourceId] ) )
			$this->edges[$nodeSourceId]	= array();
		$this->edges[$nodeSourceId][$nodeTargetId]	= $options;
	}

	public function addNode( $name, $options = array() ){
		$nodeId	= $this->sanitizeNodeName( $name );
		if( array_key_exists( $nodeId, $this->nodes ) )
			throw new DomainException( 'Node "'.$name.'" is already existing' );
		if( !isset( $options['label'] ) )
			$options['label']	= $name;
		$this->nodes[$nodeId]	= $options;
	}

	public function getDefaultEdgeOptions(){
		return $this->edgeOptions;
	}

	public function getDefaultNodeOptions(){
		return $this->nodeOptions;
	}

	public function getDefaultOptions(){
		return $this->options;
	}

	public function getEdges(){
		return $this->edges;
	}

	public function getId(){
		return $this->id;
	}

	public function getNodeOptions( $name ){
		if( !$this->hasNode( $name ) )
			return NULL;
		return $this->nodes[$this->sanitizeNodeName( $name )];
	}

	public function getNodes(){
		return $this->nodes;
	}

	public function getType(){
		return $this->type;
	}

	public function hasEdge( $nameSource, $nameTarget ){
		$idSource	= $this->sanitizeNodeName( $nameSource );
		$idTarget	= $this->sanitizeNodeName( $nameTarget );
		return isset( $this->edges[$idSource][$idTarget] );
	}

	public function hasNode( $name ){
		return isset( $this->nodes[$this->sanitizeNodeName( $name )] );
	}

	public function render( $options = array() ){
		$edges	= array();
		$nodes	= array();
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

	protected function renderOptions( $options = array(), $overrideOptions = array(), $delimiter =" " ){
		if( is_null( $options ) )
			return "";
		if( is_array( $overrideOptions ) )
			$options	= array_merge( $options, $overrideOptions );
		$list	= array();
		foreach( $options as $key => $value )
			$list[]	= $key.'="'.addslashes( $value ).'"';
		return join( $delimiter, $list );
	}
	
	protected function sanitizeNodeName( $name ){
		$name	= htmlentities( $name );
		return preg_replace( "/[^\w_:]/", "", $name );
	}

	public function save( $fileName, $options = array() ){
		return File_Writer::save( $fileName, $this->render( $options ) );
	}

	public function setDefaultEdgeOptions( $options ){
		$this->edgeOptions	= $options;
	}

	public function setDefaultNodeOptions( $options ){
		$this->nodeOptions	= $options;
	}

	public function setDefaultOptions( $options ){
		$this->options	= $options;
	}

	public function setEdgeOptions( $nameSource, $nameTarget, $options ){
		if( !$this->hasEdge( $nameSource, $nameTarget ) )
			return FALSE;
		$idSource	= $this->sanitizeNodeName( $nameSource );
		$idTarget	= $this->sanitizeNodeName( $nameTarget );
		$options	= array_merge( $this->edges[$idSource][$idTarget], $options );
		$this->edges[$idSource][$idTarget]	= $options;
		return TRUE;
	}

	public function setId( $id ){
		$this->id	= $this->sanitizeNodeName( $id );
	}

	public function setNodeOptions( $name, $options ){
		if( !$this->hasNode( $name ) )
			return FALSE;
		$nodeId	= $this->sanitizeNodeName( $name );
		$this->nodes[$nodeId]	= array_merge( $this->nodes[$nodeId], $options );
		return TRUE;
	}

	public function setType( $type ){
		if( !in_array( $type, array( "digraph", "graph" ) ) )
			throw new InvalidArgumentException( 'Invalid graph type "'.$type.'"' );
		$this->type		= $type;
	}
}
?>