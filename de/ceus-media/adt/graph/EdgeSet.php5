<?php
import( "de.ceus-media.adt.graph.Edge");
/**
 *	EdgeSet to store and manipulate edges in a graph.
 *	@package		adt.graph
 *	@uses			Edge
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
/**
 *	EdgeSet to store and manipulate edges in a graph.
 *	@package		adt.graph
 *	@uses			Edge
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
class EdgeSet
{
	/**	@var	array	$edges		Array of all Edges */
	protected $edges = array();

	/**
	 *	Adds a new Edge and returns reference of this Edge.
	 *
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@param		int			$value			Value of this Edge
	 *	@return 	Node
	**/
	public function addEdge( $sourceNode, $targetNode, $value = false )
	{
		if( !$this->isEdge( $sourceNode, $targetNode ) )
		{
			$newEdge = new Edge( $sourceNode, $targetNode, $value );
			$this->edges[] = $newEdge;
			return $newEdge;
		}
	}

	/**
	 *	Returns an Edge existing in this EdgeSet.
	 *
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	int
	 */
	public function getEdge( $sourceNode, $targetNode )
	{
		$index = $this->getEdgeIndex( $sourceNode, $targetNode );
		return $this->edges[$index];
	}

	/**
	 *	Returns an Array of all Edges in this EdgeSet.
	 *
	 *	@access		public
	 *	@return 	Node
	 */
	public function getEdges()
	{
		return $this->edges;

	}

	/**
	 *	Returns Index of an Edge in this EdgeSet.
	 *	@access		private
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	int
	 */
	private function getEdgeIndex( $sourceNode, $targetNode )
	{
		for( $i=0; $i<sizeof( $this->edges ); $i++ )
		{
			$edge = $this->edges[$i];
			$isSource = $edge->getSourceNode() == $sourceNode;
			$isTarget = $edge->getTargetNode() == $targetNode;
			if( $isSource && $isTarget )
				return $i;
		}
		return false;
	}

	/**
	 *	Returns the amount of Edges in this EdgeSet.
	 *	@access		public
	 *	@return 	int
	 */
	public function getEdgeSize()
	{
		return sizeof( $this->edges );
	}

	/**
	 *	Indicates whether an Edge is existing in this EdgeSet.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	bool
	 */
	public function isEdge( $sourceNode, $targetNode )
	{
		foreach( $this->edges as $edge )
		{
			$isSource = $edge->getSourceNode() == $sourceNode;
			$isTarget = $edge->getTargetNode() == $targetNode;
			if( $isSource && $isTarget )
				return true;
		}
		return false;
	}

	/**
	 *	Removing an Edge.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	void
	 */
	public function removeEdge( $sourceNode, $targetNode )
	{
		if( $this->isEdge( $sourceNode, $targetNode ) )
		{
			$index = $this->getEdgeIndex( $sourceNode, $targetNode );
			unset( $this->edges[$index] );
			sort( $this->edges );
		}
	}
}
?>