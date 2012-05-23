<?php
/**
 *	EdgeSet to store and manipulate edges in a graph.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	EdgeSet to store and manipulate edges in a graph.
 *	@category		cmClasses
 *	@package		ADT.Graph
 *	@implements		Countable
 *	@uses			ADT_Graph_Edge
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class ADT_Graph_EdgeSet implements Countable
{
	/**	@var		array				$edges			Array of all Edges */
	protected $edges = array();

	/**
	 *	Adds a new Edge and returns reference of this Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@param		int					$value			Value of this Edge
	 *	@return 	ADT_Graph_Node
	 */
	public function addEdge( $sourceNode, $targetNode, $value = NULL )
	{
		if( $this->isEdge( $sourceNode, $targetNode ) )
		{
			$edge	= $this->getEdge( $sourceNode, $targetNode );
 			if( $value == $edge->getEdgeValue( $sourceNode, $targetNode ) )
				throw new InvalidArgumentException( 'Edge is already set.' );
			else
				$this->removeEdge( $sourceNode, $targetNode );
		}
		$newEdge = new ADT_Graph_Edge( $sourceNode, $targetNode, $value );
		$this->edges[] = $newEdge;
		return $newEdge;
	}

	/**
	 *	Returns an Edge existing in this EdgeSet.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@return 	int
	 */
	public function getEdge( $sourceNode, $targetNode )
	{
		$index = $this->getEdgeIndex( $sourceNode, $targetNode );
		return $this->edges[$index];
	}

	/**
	 *	Returns an Array of all Edges in this EdgeSet.
	 *	@access		public
	 *	@return 	ADT_Graph_Node
	 */
	public function getEdges()
	{
		return $this->edges;
	}

	/**
	 *	Returns Index of an Edge in this EdgeSet.
	 *	@access		private
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
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
		return -1;
	}

	/**
	 *	Returns the amount of Edges in this EdgeSet.
	 *	@access		public
	 *	@return 	int
	 */
	public function count()
	{
		return count( $this->edges );
	}

	/**
	 *	Indicates whether an Edge is existing in this EdgeSet.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@return 	bool
	 */
	public function isEdge( $sourceNode, $targetNode )
	{
		foreach( $this->edges as $edge )
		{
			$isSource = $edge->getSourceNode() == $sourceNode;
			$isTarget = $edge->getTargetNode() == $targetNode;
			if( $isSource && $isTarget )
				return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Removing an Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@return 	void
	 */
	public function removeEdge( $sourceNode, $targetNode )
	{
		if( !$this->isEdge( $sourceNode, $targetNode ) )
			throw new Exception( 'Edge is not existing.' );
		$index = $this->getEdgeIndex( $sourceNode, $targetNode );
		unset( $this->edges[$index] );
		sort( $this->edges );
	}
}
?>