<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Graph.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Graph;

use CeusMedia\Common\ADT\Collection\Stack;
use CeusMedia\Common\ADT\Collection\Queue;
use Exception;
use InvalidArgumentException;

/**
 *	Graph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Documentation
 */
class Weighted
{
	/**	@var	NodeSet			$nodeSet		Set of Nodes */
	protected NodeSet $nodeSet;
	/**	@var	EdgeSet			$edgeSet		Set of Edges */
	protected EdgeSet $edgeSet;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->nodeSet = new NodeSet();
		$this->edgeSet = new EdgeSet();
	}

	/**
	 *	Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		int			$value		Value of this Edge
	 *	@return		Edge
	 *	@throws		Exception
	 *	@throws		InvalidArgumentException
	 */
	public function addEdge( Node $source, Node $target, int $value = 1 ): Edge
	{
		if( $source->getNodeName() < $target->getNodeName() )
			return $this->edgeSet->addEdge( $source, $target, $value );
		else
			return $this->edgeSet->addEdge( $target, $source, $value );
	}

	/**
	 *	Adds a new Node and returns the reference on the new Node.
	 *	@access		public
	 *	@param		string		$name		Name of the new Node
	 *	@param		string|NULL	$value		Value of the new Node
	 *	@return		Node
	 */
	public function addNode( string $name, ?string $value = NULL ): Node
	{
		return $this->nodeSet->addNode( $name, $value );
	}

	/**
	 *	Returns an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node of the Edge
	 *	@param		Node		$target		Target Node of the Edge
	 *	@return		Edge|NULL
	 */
	public function getEdge( Node $source, Node $target ): ?Edge
	{
		if( $source->getNodeName() < $target->getNodeName() )
			return $this->edgeSet->getEdge( $source, $target );
		return $this->edgeSet->getEdge( $target, $source );
	}

	/**
	 *	Returns an array of all Edges.
	 *	@access		public
	 *	@return		Edge[]
	 */
	public function getEdges(): array
	{
		return $this->edgeSet->getEdges();
	}

	/**
	 *	Returns the amount of Edges.
	 *	@access		public
	 *	@return		int
	 */
	public function getEdgeSize(): int
	{
		return count($this->edgeSet->getEdges());
	}

	/**
	 *	Returns the value of an Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		int
	 */
	public function getEdgeValue( Node $source, Node $target ): int
	{
		$value = 0;
		if( $this->isEdge( $source, $target ) ) {
			$edge	= $this->getEdge( $source, $target );
			$value	= $edge->getEdgeValue();
		}
		return $value;
	}

	/**
	 *	Returns entrance grade of this Node.
	 *	@access		public
	 *	@param		Node		$node		Node
	 *	@return		int
	 */
	public function getEntranceGrade( Node $node ): int
	{
		$nodes = $this->getSourceNodes( $node );
		return count( $nodes );
	}

	/**
	 *	Returns exit grade of this Node.
	 *	@access		public
	 *	@param		Node		$node		Node
	 *	@return		int
	 */
	public function getExitGrade( Node $node ): int
	{
		$nodes = $this->getTargetNodes( $node );
		return count( $nodes );
	}

	/**
	 *	Returns last Node in Graph.
	 *	@access		public
	 *	@return		Node|NULL
	 */
	public function getFinalNode(): ?Node
	{
		return $this->nodeSet->getLastNode();
	}

	/**
	 *	Returns a Node by its name.
	 *	@access		public
	 *	@param		string			$name		Name of Node
	 *	@return		Node|NULL
	 */
	public function getNode( string $name ): ?Node
	{
		return $this->nodeSet->getNode( $name );
	}

	/**
	 *	Returns an array of all Nodes.
	 *	@access		public
	 *	@return		Node[]
	 */
	public function getNodes(): array
	{
		if( $this->getNodeSize() )
			return $this->nodeSet->getNodes();
		return [];
	}

	/**
	 *	Returns the amount of Nodes.
	 *	@access		public
	 *	@return		int
	 */
	public function getNodeSize(): int
	{
		return count( $this->nodeSet );
	}

	/**
	 *	Returns path between two Nodes as Stack, if way exists.
	 *	@access		public
	 *	@param		Node		$source		Source Node
	 *	@param		Node		$target		Target Node
	 *	@param		Stack|NULL	$stack		Stack to fill with Nodes on path
	 *	@return		Stack
	 */
	public function getPath( Node $source, Node $target, ?Stack $stack = NULL ): Stack
	{
		$stack		??= new Stack();
		$hadNodes	= [];
		$ways		= $this->getWays( $source, $target, $stack, $hadNodes );
		if( sizeof( $ways ) ){
			foreach( $ways as $way ){
				if( !isset( $fastestWay ) )
					$fastestWay = $way;
				else if( $fastestWay->getSize() > $way->getSize() )
					$fastestWay = $way;
			}
			if( isset( $fastestWay ) )
				if( $fastestWay)
					return $fastestWay;
		}
		return $stack;
	}

	/**
	 *	Returns value of edges of a path, if way exists.
	 *	@access		public
	 *	@param		Node		$source		Source Node
	 *	@param		Node		$target		Target Node
	 *	@param		array		$hadNodes	Array of already visited Nodes
	 *	@return		int
	 */
	public function getPathValue( Node $source, Node $target, array $hadNodes = [] ): int
	{
		if( $this->isEdge( $source, $target ) )
			return $this->getEdgeValue( $source, $target );
		$nodes = $this->getTargetNodes( $source );
		if( !$hadNodes )
			$hadNodes = [];
		$hadNodes[] = $source->getNodeName();
		foreach( $nodes as $node ){
			if( !in_array( $node->getNodeName(), $hadNodes, TRUE ) ){
				if( $way = $this->getPathValue( $node, $target, $hadNodes ) ){
					$value = $this->getEdgeValue( $source, $node);
			//		echo "<br>way [".$node->getNodeName()."]: $way => $value";
					return $value + $way;
				}
			}
		}
		return -1;
	}

	/**
	 *	Returns an array of source Nodes of this Node.
	 *	@access		public
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		array
	 */
	public function getSourceNodes( Node $target ): array
	{
		$nodes = [];
		foreach( $this->getNodes() as $node )
			if( $this->isEdge( $node, $target ) )
				$nodes[] = $node;
		return $nodes;
	}

	/**
	 *	Returns first Node in Graph.
	 *	@access		public
	 *	@return		Node|NULL
	 */
	public function getStartNode(): ?Node
	{
		return $this->nodeSet->getFirstNode();
	}

	/**
	 *	Returns an array of target Nodes of this Node.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@return		Node[]
	 */
	public function getTargetNodes( Node $source ): array
	{
		$nodes = [];
		foreach( $this->getNodes() as $node )
			if( $this->isEdge( $source, $node ) )
				$nodes[] = $node;
		return $nodes;
	}

	/**
	 *	Returns all ways between two Nodes as array of Stacks, if way exists.
	 *	@access		public
	 *	@param		Node		$source		Source Node
	 *	@param		Node		$target		Target Node
	 *	@param		Stack|NULL	$stack		Stack to fill with Nodes on path
	 *	@param		array		$hadNodes	Array of already visited Nodes
	 *	@return		array
	 */
	public function getWays( Node $source, Node $target, ?Stack $stack = NULL, array $hadNodes = [] ): array
	{
		$ways	= $newWays = [];
		$stack	??= new Stack();
		if( $this->isEdge( $source, $target ) ){
			$stack->push( $target );
			return [$stack];
		}
		$hadNodes[] = $source->getNodeName();
		$ways		= [];
		$nodes		= $this->getTargetNodes( $source );
		foreach( $nodes as $node ) {
			if( !in_array( $node->getNodeName(), $hadNodes, TRUE ) ) {
				$ways = $this->getWays( $node, $target, $stack, $hadNodes );
				if( 0 !== count( $ways ) ){
					foreach( $ways as $newStack ){
						$newStack->push( $node );
						$newWays[] = $newStack;
					}
					$hadNodes[] = $node;
					$ways = $newWays;
				}
			}
		}
		return $ways;
	}

	/**
	 *	Hat Zyklus ? --> Zyklus = geschlossene Kantenfolge
	 *	//  hier beim ungerichteten Graphen FEHLERHAFT !!!
	 *	@access		public
	 *	@return		bool
	 */
	public function hasCycle(): bool
	{
		if( $this->hasLoop() )
			return TRUE;
		foreach( $this->getNodes() as $node )
			if( $this->isPath( $node, $node ) )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Hat Schlingen ? --> Schlinge = Kante {u,u}
	 *	@access		public
	 *	@return		bool
	 */
	public function hasLoop(): bool
	{
		foreach( $this->getNodes() as $node )
			if( $this->isLoop( $node ) )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Ist zusammenhängend ? --> mindestens eine Kante pro Knoten
	 *	@access		public
	 *	@return		bool
	 */
	public function isCoherent(): bool
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $source ){
			foreach( $nodes as $target ){
				if( $source != $target ){
					$forward = $this->isPath( $source, $target );
					$backward = $this->isPath( $target, $source );
					if( !$forward && !$backward )
						return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**
	 *	Indicated whether an Edge is existing in this Graph.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		bool
	 */
	public function isEdge( Node $source, Node $target ): bool
	{
		if( $source === $target )
			return FALSE;
		if( $source->getNodeName() < $target->getNodeName() )
			return $this->edgeSet->isEdge( $source, $target );
		return $this->edgeSet->isEdge( $target, $source );
	}

	/**
	 *	Ist Schlinge ? --> Kante {u,u}
	 *	@access		public
	 *	@param		Node		$node		Node to be proved for loops
	 *	@return		bool
	 */
	public function isLoop( Node $node ): bool
	{
		if( $this->isEdge( $node, $node ) )
			return TRUE;
		return FALSE;
	}

	/**
	 *	Indicated whether a Node is existing in this Graph.
	 *	@access		public
	 *	@param		Node		$node		Node to be proved
	 *	@return		bool
	 */
	public function isNode( Node $node ): bool
	{
		return $this->nodeSet->isNode( $node );
	}

	/**
	 *	Ist Weg ?
	 *	 - ist Folge
	 *	 - keinen Knoten doppelt besucht
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		array		$hadNodes	Already visited Node.
	 *	@return		bool
	 */
	public function isPath( Node $source, Node $target, array $hadNodes = [] ): bool
	{
		if( $this->isEdge( $source, $target ) )
			return TRUE;
		$nodes = $this->getTargetNodes( $source );
		$hadNodes[] = $source->getNodeName();
		foreach( $nodes as $node )
			if( !in_array( $node->getNodeName(), $hadNodes, TRUE ) )
				if( $this->isPath( $node, $target, $hadNodes ) )
					return TRUE;
		return FALSE;
	}

	/**
	 *	Ist Wald ? -> Eingangsgrad aller Knoten > 1
	 *	@access		public
	 *	@return		bool
	 */
	public function isForrest(): bool
	{
		if( $this->hasCycle() )
			return TRUE;
		$nodes = $this->getNodes();
		foreach( $nodes as $node )
			if( 0 === $this->getEntranceGrade( $node ) )
				return FALSE;
		return TRUE;
	}

	/**
	 *	Sets transitive closure with values with Warshall algorithm.
	 *	@access		public
	 *	@return		void
	 *	@throws		Exception
	 *	@throws		InvalidArgumentException
	 */
	public function makeTransitive(): void
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $source ){
			foreach( $nodes as $target ){
				if( $source !== $target && $this->isEdge( $source, $target ) ){
					$value1 = $this->getEdgeValue( $source, $target );
					foreach( $nodes as $step ){
						if( $source != $step && $target !== $step && $this->isEdge( $target, $step ) ){
							$value2 = $this->getEdgeValue( $target, $step );
							if( $this->getEdgeValue( $source, $step ) != ( $value1 + $value2 ) )
								$this->addEdge( $source, $step, $value1 + $value2 );
						}
					}
				}
			}
		}
	}

	/**
	 *	Removes an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		self
	 *	@throws		Exception
	 */
	public function removeEdge( Node $source, Node $target ): self
	{
		if( $source->getNodeName() < $target->getNodeName() )
			$this->edgeSet->removeEdge( $source, $target );
		else
			$this->edgeSet->removeEdge( $target, $source );
		return $this;
	}

	/**
	 *	Removes a Node.
	 *	@access		public
	 *	@param		Node		$node		Node to be removed
	 *	@return		self
	 *	@throws		Exception
	 */
	public function removeNode( Node $node ): self
	{
		foreach( $this->getNodes() as $_node )
			//  remove all Edges of Node
			$this->removeEdge( $_node, $node );
		//  remove Node
		$this->nodeSet->removeNode( $node );
		return $this;
	}

	/**
	 *	Calculates the shortest ways with Warshall algorithm.
	 *	@access		public
	 *	@return		void
	 *	@throws		Exception
	 */
	public function shortest(): void
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $target ){
			foreach( $nodes as $source ){
				if( $this->isEdge( $source, $target ) ){
					foreach( $nodes as $step ){
						if( $this->isEdge( $target, $step ) ){
							$value1 = $this->getEdgeValue( $source, $target );
							$value2 = $this->getEdgeValue( $target, $step );
							$value3 = $this->getEdgeValue( $source, $step );
							if( $value1 + $value2 < $value3 ){
								$this->removeEdge( $source, $step );
								$this->addEdge( $source, $step, $value1 + $value2 );
							}
						}
					}
				}
			}
		}
	}

	/**
	 *	Returns all Nodes and Edges of this Graph as an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		$a = [];
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
	 *	Returns all Nodes and Edges of this Graph as list.
	 *	@access		public
	 *	@return		array
	 */
	public function toList(): array
	{
		$list = [];
		$nodes = $this->getNodes();
		foreach( $nodes as $source ){
			$sublist = [];
			foreach( $nodes as $target ){
				if( $this->isEdge( $source, $target ) )
					$sublist[$target->getNodeName()] = $this->getEdgeValue( $source, $target );
			}
			$list[$source->getNodeName()] = $sublist;
		}
		return $list;
	}

	/**
	 *	Returns all nodes and edges of this graph as HTML-table.
	 *	@access		public
	 *	@param		bool		$showNull		flag: show Zero
	 *	@return		string
	 */
	public function toTable( bool $showNull = FALSE ): string
	{
		$heading = "";
		$t = "<table class='filledframe' cellpadding=2 cellspacing=0>";
		$nodes = $this->getNodes();
		for( $j=0; $j<$this->getNodeSize(); $j++ ){
			$target = $nodes[$j];
			$heading .= "<th width=20>".$target->getNodeName()."</th>";
		}
		$t .= "<tr><th></th>".$heading."</tr>";
		for( $i=0; $i<$this->getNodeSize(); $i++ ){
			$source = $nodes[$i];
			$line = "";
			for( $j=0; $j<$this->getNodeSize(); $j++ ){
				$target = $nodes[$j];
				if( $this->isEdge( $source, $target ) )
					$value = $this->getEdgeValue( $source, $target );
				else if( $showNull )
					$value = 0;
				else
					$value = "";
				$line .= "<td align=center>".$value."</td>";
			}
			$t .= "<tr><th width=20>".$source->getNodeName()."</th>".$line."</tr>";
		}
		$t .= "</table>";
		return $t;
	}

	/**
	 *	Traverses graph in depth and build queue of all Nodes.
	 *	@access		public
	 *	@param		Node		$source		Source Node
	 *	@param		Queue		$queue		Queue to fill with Nodes
	 *	@param		array		$hadNodes	Array of already visited Nodes
	 *	@return		Queue
	 */
	public function traverseDepth( Node $source, Queue $queue, array $hadNodes = [] ): Queue
	{
		$nextNodeSet = [];
		if( !$hadNodes)
			$hadNodes = [];
		$hadNodes[] = $source->getNodeName();
		$queue->push( $source );
		foreach( $this->getSourceNodes( $source) as $node ){
			if( !in_array( $node->getNodeName(), $hadNodes, TRUE ) ){
				$hadNodes[] = $node->getNodeName();
				$nextNodeSet[] = $node;
			}
		}
		foreach( $this->getTargetNodes( $source) as $node ) {
			if( !in_array( $node->getNodeName(), $hadNodes, TRUE ) ) {
				$hadNodes[] = $node->getNodeName();
				$queue = $this->traverseDepth( $node, $queue, $hadNodes );
			}
		}
		foreach( $nextNodeSet as $node ){
			$queue = $this->traverseDepth( $node, $queue, $hadNodes );
		}
		return $queue;
	}
}
