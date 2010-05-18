<?php
/**
 *	NodeSet to store and manipulate nodes in a graph.
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
 *	@package		ADT.Graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	NodeSet to store and manipulate nodes in a graph.
 *	@category		cmClasses
 *	@package		ADT.Graph
 *	@uses			Node
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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