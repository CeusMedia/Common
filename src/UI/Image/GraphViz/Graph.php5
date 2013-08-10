<?php
class UI_Image_GraphViz_Graph{

	protected $type;
	protected $edges		= array();
	protected $nodes		= array();

	public function __construct( $type = "digraph" ){
		$this->type		= $type;
	}

	public function addEdge( $nodeSource, $nodeTarget, $options = NULL ){
		if( !isset( $this->edges[$nodeSource] ) )
			$this->edges[$nodeSource]	= array();
		$this->edges[$nodeSource][$nodeTarget]	= $options;
	}

	public function addNode( $name, $options = NULL ){
		$this->nodes[$name]	= $options;
	}

	public function render(){
		$edges	= array();
		$nodes	= array();
		foreach( $this->nodes as $name => $options )
			$nodes[]	= $name.' ['.$this->renderOptions( $options ).'];';
		foreach( $this->edges as $source => $targets )
			foreach( $targets as $target => $options )
				$edges[]	= $source.' -> '.$target.' ['.$this->renderOptions( $options ).']';
		return $this->type." {\n\t".join( "\n\t", $nodes )."\n\t".join( "\n\t", $edges )."\n}";
	}

	protected function renderOptions( $options = array() ){
		if( is_null( $options ) )
			return "";
		$list	= array();
		foreach( $options as $key => $value )
			$list[]	= $key.'="'.addslashes( $value ).'"';
		return join( " ", $list );
	}
}
?>