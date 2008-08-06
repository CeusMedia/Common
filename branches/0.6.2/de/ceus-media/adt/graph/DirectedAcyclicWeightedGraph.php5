<?php
import( 'de.ceus-media.adt.graph.DirectedWeightedGraph' );
/**
 *	Directed Acyclic Graph.
 *	@package		adt.graph
 *	@extends		DirectedGraph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.3
 */
/**
 *	Directed Acyclic Graph.
 *	@package		adt.graph
 *	@extends		DirectedGraph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.3
 */
class ADT_Graph_DirectedAcyclicWeightedGraph extends ADT_Graph_DirectedWeightedGraph
{
	/**
	 *	Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		int			$value		Value of this Edge
	 *	@return		Edge
	 */
	public function addEdge( $source, $target, $value = 1 )
	{
		$edge	= $this->edgeSet->addEdge( $source, $target, $value );
		if( $this->hasCycle() )
		{
			$this->edgeSet->removeEdge( $source, $target );
			return false;
		}
		return $edge;
	}

	/**
	 *	Removes an Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		void
	 */
	public function removeEdge( $source, $target )
	{
		$value	= $this->getEdgeValue( $source, $target );
		$this->edgeSet->removeEdge( $source, $target );
		if( !$this->isCoherent() )
			$this->edgeSet->addEdge( $source, $target, $value );
	}
}
?>