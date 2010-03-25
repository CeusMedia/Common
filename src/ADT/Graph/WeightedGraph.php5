<?php
/**
 *	Graph.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		adt.graph
 *	@uses			NodeSet
 *	@uses			EdgeSet
 *	@uses			Queue
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.adt.graph.NodeSet' );
import( 'de.ceus-media.adt.graph.EdgeSet' );
import( 'de.ceus-media.adt.list.Queue' );
import( 'de.ceus-media.adt.list.Stack' );
//import( "de.ceus-media.adt.matrix.AssocFileMatrix");
/**
 *	Graph.
 *	@category		cmClasses
 *	@package		adt.graph
 *	@uses			NodeSet
 *	@uses			EdgeSet
 *	@uses			Queue
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			prove Implementation( AssocFileMatrix)
 *	@todo			Code Documentation
 */
class ADT_Graph_WeightedGraph
{
	/**	@var	NodeSet		$nodeSet		Set of Nodes */
	protected $nodeSet;
	/**	@var	EdgeSet		$edgeSet		Set of Edges */
	protected $edgeSet;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->nodeSet = new ADT_Graph_NodeSet();
		$this->edgeSet = new ADT_Graph_EdgeSet();
	}

	/**
	 *	Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@param		int				$value		Value of this Edge
	 *	@return		ADT_Graph_Edge
	 */
	public function addEdge( $source, $target, $value = 1 )
	{
		if( $source->getNodeName() < $target->getNodeName() )
			return $this->edgeSet->addEdge( $source, $target, $value );
		else
			return $this->edgeSet->addEdge( $target, $source, $value );
	}

	/**
	 *	Adds a new Node and returns the reference on the new Node.
	 *	@access		public
	 *	@param		string			$name		Name of the new Node
	 *	@param		string			$value		Value of the new Node
	 *	@return		ADT_Graph_Node
	 */
	public function addNode( $name, $value = null )
	{
		return $this->nodeSet->addNode( $name, $value );
	}

	/**
	 *	Returns an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of the Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of the Edge
	 *	@return		ADT_Graph_Edge
	 */
	public function getEdge( $source, $target )
	{
		if( $source->getNodeName() < $target->getNodeName() )
			return $this->edgeSet->getEdge( $source, $target );
		else
			return $this->edgeSet->getEdge( $target, $source );
	}

	/**
	 *	Returns an array of all Edges.
	 *	@access		public
	 *	@return		array
	 */
	public function getEdges()
	{
		return $this->edgeSet->getEdges();
	}

	/**
	 *	Returns the amount of Edges.
	 *	@access		public
	 *	@return		int
	 */
	public function getEdgeSize()
	{
		return $this->edgeSet->getEdgeSize();
	}

	/**
	 *	Returns the value of an Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@return		int
	 */
	public function getEdgeValue( $source, $target )
	{
		$value = 0;
		if( $this->isEdge( $source, $target ) )
		{
			$edge	= $this->getEdge( $source, $target );
			$value	= $edge->getEdgeValue();
		}
		return $value;
	}

	/**
	 *	Returns last Node in Graph.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getFinalNode()
	{
		return $this->nodeSet->getLastNode();
	}

	/**
	 *	Returns first Node in Graph.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getStartNode()
	{
		return $this->nodeSet->getFirstNode();
	}

	/**
	 *	Returns entrance grade of this Node.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$node		Node
	 *	@return		int
	 */
	public function getEntranceGrade( $node )
	{
		$nodes = $this->getSourceNodes( $node );
		return sizeof( $nodes );
	}

	/**
	 *	Returns exit grade of this Node.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$node		Node
	 *	@return		void
	 */
	public function getExitGrade( $node )
	{
		$nodes = $this->getTargetNodes( $node );
		return sizeof( $nodes );
	}

	/**
	 *	Returns a Node by its name.
	 *	@access		public
	 *	@param		string			$name		Name of Node
	 *	@return		ADT_Graph_Node
	 */
	public function getNode( $name )
	{
		return $this->nodeSet->getNode( $name );
	}

	/**
	 *	Returns an array of all Nodes.
	 *	@access		public
	 *	@return		array
	 */
	public function getNodes()
	{
		if( $this->getNodeSize() )
			return $this->nodeSet->getNodes();
		return array();
	}

	/**
	 *	Returns the amount of Nodes.
	 *	@access		public
	 *	@return		int
	 */
	public function getNodeSize()
	{
		return count( $this->nodeSet );
	}

	/**
	 *	Returns path between two Nodes as Stack, if way exists.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node
	 *	@param		ADT_Graph_Node	$target		Target Node
	 *	@param		ADT_List_Stack	$stack		Stack to fill with Nodes on path
	 *	@return		ADT_List_Stack
	 */
	public function getPath( $source, $target, $stack = false )
	{
		if( !( $stack && $stack->_getObjectName() == "ADT_List_Stack") )
			$stack = new ADT_List_Stack();
		$hadNodes = array();
		$ways = $this->getWays( $source, $target, $stack, $hadNodes );
		if( sizeof( $ways ) )
		{
			foreach( $ways as $way )
			{
				if( !isset( $fastestWay ) )
					$fastestWay = $way;
				else if( $fastestWay->getSize() > $way->getSize() )
					$fastestWay = $way;
			}
			if( isset( $fastestWay ) )
				if( $fastestWay)
					return $fastestWay;
		}
		return false;
	}

	/**
	 *	Returns all ways between two Nodes as array of Stacks, if way exists.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node
	 *	@param		ADT_Graph_Node	$target		Target Node
	 *	@param		ADT_ListStack	$stack		Stack to fill with Nodes on path
	 *	@param		array			$hadNodes	Array of already visited Nodes
	 *	@return		array
	 */
	public function getWays( $source, $target, $stack, $hadNodes = array() )
	{
		$ways = $newWays = array();
		if( !( $stack && is_a( $stack, "ADT_List_Stack" ) ) )
			$stack = new ADT_List_Stack();
		if( $this->isEdge( $source, $target ) )
		{
			$stack->push( $target );
			return array( $stack );
		}
		$hadNodes[] = $source->getNodeName();
		$ways = array();
		$nodes = $this->getTargetNodes( $source );
		foreach( $nodes as $node )
		{
			if( !in_array( $node->getNodeName(), $hadNodes ) )
			{
				$ways = $this->getWays( $node, $target, $stack, $hadNodes );
				if( sizeof( $ways ) )
				{
					foreach( $ways as $stack )
					{
						$stack->push( $node );
						$newWays[] = $stack;
					}
					$hasnodeSet[] = $node;
					$ways = $newWays;
				}
			}
		}
		return $ways;
	}

	/**
	 *	Returns value of edges of a path, if way exists.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node
	 *	@param		ADT_Graph_Node	$target		Target Node
	 *	@param		array			$hadNodes	Array of already visited Nodes
	 *	@return		int
	 */
	public function getPathValue( $source, $target, $hadNodes = false )
	{
		if( $this->isEdge( $source, $target ) )
		{
			$value = $this->getEdgeValue( $source, $target );
			return $value;
		}
		$nodes = $this->getTargetNodes( $source );
		if( !$hadNodes )
			$hadNodes = array();
		$hadNodes[] = $source->getNodeName();
		foreach( $nodes as $node )
		{
			if( !in_array( $node->getNodeName(), $hadNodes ) )
			{
				if( $way = $this->getPathValue( $node, $target, $hadNodes ) )
				{
					$value = $this->getEdgeValue( $source, $node);
			//		echo "<br>way [".$node->getNodeName()."]: $way => $value";
					return $value + $way;
				}
			}
		}
		return 0;
	}

	/**
	 *	Returns an array of source Nodes of this Node.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@return		array
	 */
	public function getSourceNodes( $target )
	{
		$nodes = array();
		foreach( $this->getNodes() as $node )
			if( $this->isEdge( $node, $target ) )
				$nodes[] = $node;
		return $nodes;
	}

	/**
	 *	Returns an array of target Nodes of this Node.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@return		array
	 */
	public function getTargetNodes( $source )
	{
		$nodes = array();
		foreach( $this->getNodes() as $node )
			if( $this->isEdge( $source, $node ) )
				$nodes[] = $node;
		return $nodes;
	}

	/**
	 *	Hat Zyklus ? --> Zyklus = geschlossene Kantenfolge
	 *	//  hier beim ungerichteten Graphen FEHLERHAFT !!!
	 *	@access		public
	 *	@return		bool
	 */
	public function hasCycle()
	{
		if( $this->hasLoop() )
			return true;
		else
		{
			foreach( $this->getNodes() as $node )
				if( $this->isPath( $node, $node ) )
					return true;
		}
		return false;
	}

	/**
	 *	Hat Schlingen ? --> Schlinge = Kante {u,u}
	 *	@access		public
	 *	@return		bool
	 */
	public function hasLoop()
	{
		foreach( $this->getNodes() as $node )
			if( $this->isLoop( $node ) )
				return true;
		return false;
	}

	/**
	 *	Ist zusammenhängend ? --> mindestens eine Kante pro Knoten
	 *	@access		public
	 *	@return		bool
	 */
	public function isCoherent()
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $source )
		{
			foreach( $nodes as $target )
			{
				if( $source != $target )
				{
					$forward = $this->isPath( $source, $target );
					$backward = $this->isPath( $target, $source );
					if( !$forward && !$backward )
						return false;
				}
			}
		}
		return true;
	}

	/**
	 *	Indicated whether an Edge is existing in this Graph.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@return		bool
	 */
	public function isEdge( $source, $target )
	{
		if( $source != $target )
		{
			if( $source->getNodeName() < $target->getNodeName() )
				return $this->edgeSet->isEdge( $source, $target );
			else
				return $this->edgeSet->isEdge( $target, $source );
		}
	}

	/**
	 *	Indicated whether a Node is existing in this Graph.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$node		Node to be proved
	 *	@return		void
	 */
	public function isNode( $node )
	{
		return $this->nodeSet->isNode( $node );
	}

	/**
	 *	Ist Schlinge ? --> Kante {u,u}
	 *	@access		public
	 *	@param		ADT_Graph_Node	$node		Node to be proved for loops
	 *	@return		bool
	 */
	public function isLoop( $node )
	{
		if( $this->isEdge( $node, $node ) )
			return true;
		return false;
	}

	/**
	 *	Ist Weg ?
	 *	 - ist Folge
	 *	 - keinen Knoten doppelt besucht
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@param		array			$hadNodes	Already visited Node.
	 *	@return		bool
	 */
	public function isPath( $source, $target, $hadNodes = array() )
	{
		if( $this->isEdge( $source, $target ) )
			return true;
		$nodes = $this->getTargetNodes( $source );
		$hadNodes[] = $source->getNodeName();
		foreach( $nodes as $node )
			if( !in_array( $node->getNodeName(), $hadNodes ) )
				if( $this->isPath( $node, $target, $hadNodes ) )
					return true;
		return false;
	}

	/**
	 *	Ist Wald ? -> Eingangsgrad aller Knoten > 1
	 *	@access		public
	 *	@return		bool
	 */
	public function isWood()
	{
		if( !$this->hasCycle() )
		{
			$nodes = $this->getNodes();
			foreach( $nodes as $targetSource )
				foreach( $nodes as $source )
					if( $source != $targetSource )
						if( $this->getEntranceGrade( $source, $targetSource ) > 1 )
							return false;
			return true;
		}
		else return false;
	}

	/**
	 *	Sets transitive closure with values with Warshall algorithm.
	 *	@access		public
	 *	@return		void
	 */
	public function makeTransitive()
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $source )
		{
			foreach( $nodes as $target )
			{
				if( $source != $target && $this->isEdge( $source, $target ) )
				{
					$value1 = $this->getEdgeValue( $source, $target );
					foreach( $nodes as $step )
					{
						if( $source != $step && $target != $step && $this->isEdge( $target, $step ) )
						{
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
	 *	Calculates shortest ways with Warshall algorithm.
	 *	@access		public
	 *	@return		void
	 */
	public function shortest()
	{
		$nodes = $this->getNodes();
		foreach( $nodes as $target )
		{
			foreach( $nodes as $source )
			{
				if( $this->isEdge( $source, $target ) )
				{
					foreach( $nodes as $step )
					{
						if( $this->isEdge( $target, $step ) )
						{
							$value1 = $this->getEdgeValue( $source, $target );
							$value2 = $this->getEdgeValue( $target, $step );
							$value3 = $this->getEdgeValue( $source, $step );
							if( $value1 + $value2 < $value3 )
							{
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
	 *	Removes an Edge by its source and target Nodes.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node of this Edge
	 *	@param		ADT_Graph_Node	$target		Target Node of this Edge
	 *	@return		void
	 */
	public function removeEdge( $source, $target )
	{
		if( $source->getNodeName() < $target->getNodeName() )
			$this->edgeSet->removeEdge( $source, $target );
		else
			$this->edgeSet->removeEdge( $target, $source );
	}

	/**
	 *	Removes a Node.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$node		Node to be removed
	 *	@return		void
	 */
	public function removeNode( $node )
	{
		foreach( $this->getNodes() as $_node )
			$this->removeEdge( $_node, $node );									//  remove all Edges of Node
		$this->nodeSet->removeNode( $node );									//  remove Node
	}

	/**
	 *	Traverses graph in deepth and build queue of all Nodes.
	 *	@access		public
	 *	@param		ADT_Graph_Node	$source		Source Node
	 *	@param		ADT_List_Queue	$queue		Queue to fill with Nodes
	 *	@param		array			$hadNodes	Array of already visited Nodes
	 *	@return		ADT_List_Queue
	 */
	public function traverseDeepth( $source, $queue = array(), $hadNodes = false )
	{
		$nextnodeSet = array();
		if( !$hadNodes) $hadNodes = array();
		$hadNodes[] = $source->getNodeName();
		array_push($queue, $source );
		foreach( $this->getSourceNodes( $source) as $node )
		{
			if( !in_array( $node->getNodeName(), $hadNodes ) )
			{
				$hadNodes[] = $node->getNodeName();
				$nextnodeSet[] = $node;
			}
		}
		foreach( $this->getTargetNodes( $source) as $node )
		{
			if( !in_array( $node->getNodeName(), $hadNodes ) )
			{
				$hadNodes[] = $node->getNodeName();
				$queue = $this->traverseDeepth( $node, $queue, $hadNodes );
			}
		}
		foreach( $nextnodeSet as $node )
		{
			$queue = $this->traverseDeepth( $node, $queue, $hadNodes );
		}
		return $queue;
	}

	/**
	 *	Returns all Nodes and Edges of this Graph as an array.
	 *	@access		public
	 *	@return		array
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
	 *	Returns all Nodes and Edges of this Graph as list.
	 *	@access		public
	 *	@return		array
	 */
	public function toList()
	{
		$list = array();
		$nodes = $this->getNodes();
		foreach( $nodes as $source )
		{
			$sublist = array();
			foreach( $nodes as $target )
			{
				if( $this->isEdge( $source, $target ) )
					$sublist[$target->getNodeName()] = $this->getEdgeValue( $source, $target );
			}
			$list [$source->getNodeName()] = $sublist;
		}
		return $list;
	}

	/**
	 *	Returns all nodes and edges of this graph as an associative file matrix.
	 *	@access		public
	 *	@param		string	$filename		URI of File Matrix to write
	 *	@return		AssocFileMatrix
	 *	@todo		rebuild for KeyMatrix / MatrixWriter
	 */
	public function toMatrix(  $filename = false )
	{
		if( $filename) $m = new AssocFileMatrix( $filename );
		else $m = new AssocMatrix();

		$nodes = $this->getNodes();
		foreach( $nodes as $source )
		{
			echo $source->getNodeName()."<br>";
			foreach( $nodes as $target )
			{
				if( $this->isEdge($source, $target ) || $this->isEdge($target, $source ) )
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
	 *	Returns all nodes and edges of this graph as HTML-table.
	 *	@access		public
	 *	@param		bool		$showNull		flag: show Zero
	 *	@return		string
	 */
	public function toTable( $showNull = false)
	{
		$heading = "";
		$t = "<table class='filledframe' cellpadding=2 cellspacing=0>";
		$nodes = $this->getNodes();
		for( $j=0; $j<$this->getNodeSize(); $j++ )
		{
			$target = $nodes[$j];
			$heading .= "<th width=20>".$target->getNodeName()."</th>";
		}
		$t .= "<tr><th></th>".$heading."</tr>";
		for( $i=0; $i<$this->getNodeSize(); $i++ )
		{
			$source = $nodes[$i];
			$line = "";
			for( $j=0; $j<$this->getNodeSize(); $j++ )
			{

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
}
?>