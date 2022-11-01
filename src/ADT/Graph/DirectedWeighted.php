<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	DirectedGraph.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Graph;

use CeusMedia\Common\ADT\Collection\Stack;
use CeusMedia\Common\ADT\Collection\Queue;
use Exception;

/**
 *	DirectedGraph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Documentation
 */
class DirectedWeighted extends Weighted
{
	/**
	 *	 Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		int			$value		Value of this Edge
	 *	@return		Edge
	 *	@throws		Exception
	 */
	public function addEdge( Node $source, Node $target, int $value = 1 ): Edge
	{
		return $this->edgeSet->addEdge( $source, $target, $value );
	}

	public function bf( Node $startNode ): array
	{
		$distance = [];
		foreach( $this->nodeSet->getNodes() as $node ){
			$distance[$node->getNodeName()] = 65535;
		}
		$distance[$startNode->getNodeName()] = 0;
		for( $i=0; $i<$this->getNodeSize(); $i++ ) {
			foreach( $this->edgeSet->getEdges() as $edge ) {
				$source = $edge->getSourceNode();
				$target = $edge->getTargetNode();
				$a = $distance[$source->getNodeName()] + $this->getEdgeValue( $source, $target );
				if( $a<$distance [$target->getNodeName()] )
					$distance[$target->getNodeName()] = $a;
			}
		}
		return $distance;
	}

	/**
	 *	 Returns an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node of the Edge
	 *	@param		Node		$target		Target Node of the Edge
	 *	@return		Edge
	 */
	public function getEdge( Node $source, Node $target): Edge
	{
		return $this->edgeSet->getEdge( $source, $target);
	}

	/**
	 *	 Returns distance between two Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		int
	 */
	public function getGrade( Node $source, Node $target ): int
	{
		if( $this->isEdge( $source, $target ) )
			return 1;
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node ) {
			$way = $this->getGrade( $node, $target );
			return ++$way;
		}
		return -1;
	}

	/**
	 *	 Returns the way between two Nodes as Stack.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		Stack|NULL	$stack		Stack to fill with Node on the way
	 *	@return		Stack
	 */
	public function getPath( Node $source, Node $target, ?Stack $stack = NULL ): Stack
	{
		if( $this->isEdge( $source, $target ) ){
			if( !is_null( $stack ) )
				$way = $stack;
			else
				$way = new Stack();
			$way->push( $target );
			return $way;
		}
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node ){
			$way = $this->getPath( $node, $target, $stack );
			if( !$way->isEmpty() ){
				$way->push( $node );
				return $way;
			}
		}
		return new Stack();
	}

	/**
	 *	@param		array			$hadNodes	Array of already visited Nodes
	 */
	public function getPathValue( Node $source, Node $target, array $hadNodes = [] ): int
	{
		if( $this->isEdge( $source, $target ) ) {
			return $this->getEdgeValue( $source, $target );
		}
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node ) {
			$value	= $this->getEdgeValue( $source, $node );
			$way	= $this->getPathValue( $node, $target, $hadNodes );
			return $value + $way;
		}
		return -1;
	}

	/**
	 *	 Indicates whether Graph has closed sequence of Edges.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasCycle(): bool
	{
		if( $this->hasLoop() )
			return TRUE;
		else {
			foreach( $this->getNodes() as $node )
				if( $this->isPath($node, $node ) )
					return TRUE;
		}
		return FALSE;
	}

	/**
	 *	 Indicated whether an Edge is existing in this Graph.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		bool
	 */
	public function isEdge( Node $source, Node $target ): bool
	{
		return $this->edgeSet->isEdge( $source, $target );
	}

	/**
	 *	 Removes an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		void
	 *	@throws		Exception
	 */
	public function removeEdge( Node $source, Node $target )
	{
		if( $this->isEdge( $source, $target ) )
			$this->edgeSet->removeEdge( $source, $target );
	}

	/**
	 *	 Removes a Node.
	 *	@access		public
	 *	@param		Node		$node		Node to be removed
	 *	@return		void
	 *	@throws		Exception
	 */
	public function removeNode( Node $node )
	{
		foreach( $this->getNodes() as $_node ){
			if( $this->isEdge( $_node, $node ) )
				//  remove incoming Edges
				$this->removeEdge( $_node, $node );
			if( $this->isEdge( $node, $_node ) )
				//  remove outgoing Edges
				$this->removeEdge( $node, $_node );
		}
		//  remove Node
		$this->nodeSet->removeNode( $node );
	}

	/**
	 *	 Returns all Nodes and Edges of this Graph as an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		$a	= [];
		$nodes = $this->getNodes();
		for( $i=0; $i<$this->getNodeSize(); $i++ ){
			$source = $nodes[$i];
			$line = [];
			for( $j=0; $j<$this->getNodeSize(); $j++ ){
				$target = $nodes[$j];
				$value = $this->getEdgeValue( $source, $target );
				$line[$target->getNodeName()] = $value;
			}
			$a[$source->getNodeName()] = $line;
		}
		return $a;
	}

	/**
	 *	Breitendurchlauf
	 */
	public function traverseBreadth( $startNode ): array
	{
		$distance	= [];
		$state		= [];
		$q = new Queue();
		foreach( $this->nodeSet->getNodes() as $node )
			$state[$node->getNodeName()] = 0;
		$state[$startNode->getNodeName()] = 1;
		$distance[$startNode->getNodeName()] = 0;
		$q->push( $startNode );
		while( !$q->isEmpty() ){
			$current = $q->top();
			foreach( $this->getTargetNodes($current) as $node ){
				$state[$node->getNodeName()]	= 0;
				$distance[$node->getNodeName()]	= $distance[$current->getNodeName()] + $this->getEdgeValue($current, $node);
				$q->push( $node );
			}
			$q->pop();
		}
		return $distance;
	}
}
