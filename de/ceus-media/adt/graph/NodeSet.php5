<?php
import( "de.ceus-media.adt.graph.Node");
/**
 *	NodeSet to store and manipulate nodes in a graph.
 *	@package		adt
 *	@subpackage		graph
 *	@uses			Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
/**
 *	NodeSet to store and manipulate nodes in a graph.
 *	@package		adt
 *	@subpackage		graph
 *	@uses			Node
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
class ADT_Graph_NodeSet implements Countable
{
	/**	@var		array			$nodes			array of all Nodes */
 	protected $nodes = array();

	/**
	 *	Adds a new Node and returns reference of this Node.
	 *	@access		public
	 *	@param		string			$nodeName		Name of the new Node
	 *	@param		string			$nodeValue		Value of the new Node
	 *	@return 	ADT_Graph_Node
	 */
	public function addNode( $nodeName, $nodeValue = false )
	{
		$newNode = new ADT_Graph_Node( $nodeName, $nodeValue );
		if( !$this->isNode( $newNode ) )
		{
			$this->nodes[] = $newNode;
			return $newNode;
		}
		else
			return $this->getNode( $nodeName );
	}

	/**
	 *	Returns first Node of this NodeSet.
	 *	@access		public
	 *	@return 	ADT_Graph_Node
	 */
	public function getFirstNode()
	{
		if( count( $this->nodes ) )
			return $this->nodes[0];
	}

	/**
	 *	Returns last Node of this NodeSet.
	 *	@access		public
	 *	@return 	ADT_Graph_Node
	 */
	public function getLastNode()
	{
		if( count( $this->nodes ) )
			return $this->nodes[$this->getNodeSize()-1];
	}

	/**
	 *	Returns a Node of this NodeSet.
	 *	@access		public
	 *	@param		string				$node			Name of the new Node
	 *	@return 	ADT_Graph_Node
	 */
	public function getNode( $node )
	{
		$index = $this->getNodeIndex( $node );
		return $this->nodes[$index];
	}

	/**
	 *	Returns index of a node in this NodeSet.
	 *	@access		private
	 *	@param		ADT_Graph_Node		$node			Node to get index for
	 *	@return 	int
	 */
	private function getNodeIndex( $node )
	{
		for( $i=0; $i<$this->getNodeSize(); $i++ )
		{
			if( $this->nodes[$i] == $node )
				return $i;
		}
		return false;
	}

	/**
	 *	Returns an array of all nodes in this NodeSet.
	 *	@access		public
	 *	@param		string				$nodeName		Name of the new Node
	 *	@return 	ADT_Graph_Node
	 */
	public function getNodes()
	{
		return $this->nodes;
	}

	/**
	 *	Returns the amount of nodes in this NodeSet.
	 *	@access		public
	 *	@return 	int
	 */
	public function count()
	{
		return count( $this->getNodes() );
	}

	/**
	 *	Indicates whether a Node is existing in this NodeSet.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$node			Node to be searched for
	 *	@return 	bool
	 */
	public function isNode( $node )
	{
		foreach( $this->nodes as $_node )
			if( $_node == $node )
				return TRUE;
	}

	/**
	 *	Removing a node.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$node			Node to be removed
	 *	@return 	void
	 */
	public function removeNode( $node )
	{
		if( !$this->isNode( $node ) )
			throw new Exception( 'Edge is not existing.' );
		$index = $this->getNodeIndex( $node );
		unset( $this->nodes[$index] );
		sort( $this->nodes );
	}
}
?>