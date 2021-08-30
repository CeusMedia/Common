<?php
/**
 *	DirectedGraph.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\ADT\Graph;

use CeusMedia\Common\ADT\MatrixAssocFileMatrix;
use CeusMedia\Common\ADT\AssocMatrix;
use CeusMedia\Common\ADT\Collection\Stack;
use CeusMedia\Common\ADT\Queue;

/**
 *	DirectedGraph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@extends		ADT_Graph_Weighted
 *	@uses			ADT_List_Stack
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			prove Implementation( AssocFileMatrix)
 *	@todo			Code Documentation
 */
class DirectedWeighted extends Weighted
{
	/**
	 *	 Adds an Edge and returns the reference on the new Edge.
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of this Edge
	 *	 @param			Node		$target		Target Node of this Edge
	 *	 @param			int					$value		Value of this Edge
	 *	 @return		Edge
	 */
	public function addEdge( $source, $target, $value = 1 )
	{
		return $this->edgeSet->addEdge( $source, $target, $value );
	}

	public function bf( $startNode )
	{
		$distance = array();
		foreach( $this->nodeSet->getNodes() as $node )
		{
			$distance[$node->getNodeName()] = 65535;
		}
		$distance[$startNode->getNodeName()] = 0;
		for( $i=0; $i<$this->getNodeSize(); $i++ )
		{
			foreach( $this->edgeSet->getEdges() as $edge )
			{
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
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of the Edge
	 *	 @param			Node		$target		Target Node of the Edge
	 *	 @return		Edge
	 */
	public function getEdge( $source, $target)
	{
		return $this->edgeSet->getEdge( $source, $target);
	}

	/**
	 *	 Returns distance between two Nodes.
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of this Edge
	 *	 @param			Node		$target		Target Node of this Edge
	 *	 @return		int
	 */
	public function getGrade( $source, $target )
	{
		if( $this->isEdge( $source, $target ) )
			return 1;
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node )
		{
			$way = $this->getGrade( $node, $target );
			return ++$way;
		}
		return false;
	}

	/**
	 *	 Returns the way between two Nodes as Stack.
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of this Edge
	 *	 @param			Node		$target		Target Node of this Edge
	 *	 @param			ListStack		$stack		Stack to fill with Node on the way
	 *	 @return		ListStack
	 */
	public function getPath( $source, $target, $stack = false )
	{
		if( $this->isEdge( $source, $target ) )
		{
			if( $stack && is_a( $stack, "stack" ) )
				$way = $stack;
			else $way = new ListStack();
			$way->push( $target );
			return $way;
		}
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node )
		{
			$way = $this->getPath( $node, $target, $stack );
			if( $way )
			{
				$way->push( $node );
				return $way;
			}
		}
		return false;
	}

	public function getPathValue( $source, $target )
	{
		if( $this->isEdge( $source, $target ) )
		{
			$value = $this->getEdgeValue( $source, $target );
			return $value;
		}
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node )
		{
			$value	= $this->getEdgeValue( $source, $node );
			$way	= $this->getPathValue( $node, $target );
			if( $way )
				return $value + $way;
		}
		return false;
	}

	/**
	 *	 Indicates whether Graph has closed sequence of Edges.
	 *	 @access		public
	 *	 @return		bool
	 */
	public function hasCycle()
	{
		if( $this->hasLoop() )
			return true;
		else
		{
			foreach( $this->getNodes() as $node )
				if( $this->isPath($node, $node ) )
					return true;
		}
		return false;
	}

	/**
	 *	 Indicated whether an Edge is existing in this Graph.
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of this Edge
	 *	 @param			Node		$target		Target Node of this Edge
	 *	 @return		bool
	 */
	public function isEdge( $source, $target )
	{
		return $this->edgeSet->isEdge( $source, $target );
	}

	/**
	 *	 Removes an Edge by its source and target Nodes.
	 *	 @access		public
	 *	 @param			Node		$source		Source Node of this Edge
	 *	 @param			Node		$target		Target Node of this Edge
	 *	 @return		void
	 */
	public function removeEdge( $source, $target )
	{
		if( $this->isEdge( $source, $target ) )
			$this->edgeSet->removeEdge( $source, $target );
	}

	/**
	 *	 Removes a Node.
	 *	 @access		public
	 *	 @param			Node		$node		Node to be removed
	 *	 @return		void
	 */
	public function removeNode( $node)
	{
		foreach( $this->getNodes() as $_node )
		{
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
	 *	 @access		public
	 *	 @return		array
	 */
	public function toArray()
	{
		$a = array();
		$nodes = $this->getNodes();
		for( $i=0; $i<$this->getNodeSize(); $i++ )
		{
			$source = $nodes[$i];
			$line = array();
			for( $j=0; $j<$this->getNodeSize(); $j++ )
			{
				$target = $nodes[$j];
				$value = $this->getEdgeValue( $source, $target );
				$line[$target->getNodeName()] = $value;
			}
			$a[$source->getNodeName()] = $line;
		}
		return $a;
	}

	/**
	 *	 Returns all Nodes and Edges of this Graph as an associative file matrix.
	 *	 @access		public
	 *	 @return		AssocFileMatrix
	 */
	public function toMatrix( $filename = false )
	{
		if( $filename) $m = new MatrixAssocFileMatrix( $filename );
		else $m = new AssocMatrix();

		$nodes = $this->getNodes();
		foreach( $nodes as $source )
		{
			echo $source->getNodeName()."<br>";
			foreach( $nodes as $target )
			{
				if( $this->isEdge($source, $target ) )
				{
					$value = $this->getEdgeValue( $source, $target );
				}
				else $value = 0;
				$m->addValueAssoc( $source->getNodeName(), $target->getNodeName(), $value );
			}
		}
		return $m;
	}

	/**
	 *	Breitendurchlauf
	 */
	public function traverseBreadth( $startNode )
	{
		$distance = array();
		$state = array();
		$q = new Queue();
		foreach( $this->nodeSet->getNodes() as $node )
		{
			$state[$node->getNodeName()] = 0;
		}
		$state[$startNode->getNodeName()] = 1;
		$distance[$startNode->getNodeName()] = 0;
		$q->enqueue( $startNode );
		while( !$q->isEmpty() )
		{
			$current = $q->top();
			foreach( $this->getTargetNodes($current) as $node )
			{
				$state[$node->getNodeName()]	= 0;
				$distance[$node->getNodeName()]	= $distance[$current->getNodeName()] + $this->getEdgeValue($current, $node);
				$q->enqueue( $node );
			}
			$q->dequeue();
		}
		return $distance;
	}
}
