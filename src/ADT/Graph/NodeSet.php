<?php
/**
 *	NodeSet to store and manipulate nodes in a graph.
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
/**
 *	NodeSet to store and manipulate nodes in a graph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@uses			Node
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ADT_Graph_NodeSet implements Countable
{
	/**	@var		array			$nodes			array of all Nodes */
 	protected $nodes = array();

	/**
	 *	Adds a new Node and returns reference of this Node.
	 *	@access		public
	 *	@param		string			$nodeName		Name of the new Node
	 *	@param		mixed			$nodeValue		Value of the new Node
	 *	@return 	ADT_Graph_Node
	 */
	public function addNode( string $nodeName, $nodeValue = '' )
	{
		$newNode = new ADT_Graph_Node( $nodeName, $nodeValue );
		if( !$this->isNode( $newNode ) ){
			$this->nodes[] = $newNode;
			return $newNode;
		}
		else
			return $this->getNode( $nodeName );
	}

	/**
	 *	Returns the amount of nodes in this NodeSet.
	 *	@access		public
	 *	@return 	int
	 */
	public function count(): int
	{
		return count( $this->getNodes() );
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
	 *	@return 	ADT_Graph_Node|NULL
	 */
	public function getLastNode()
	{
		if( 0 !== $this->count() )
			return $this->nodes[$this->count() - 1];
		return NULL;
	}

	/**
	 *	Returns a Node of this NodeSet.
	 *	@access		public
	 *	@param		string				$nodeName		Name of the new Node
	 *	@return 	ADT_Graph_Node
	 */
	public function getNode( string $nodeName )
	{
		for( $i=0; $i<$this->count(); $i++ )
			if( $this->nodes[$i]->getNodeName() == $nodeName )
				return $this->nodes[$i];
		return NULL;
	}

	/**
	 *	Returns index of a node in this NodeSet.
	 *	@access		private
	 *	@param		ADT_Graph_Node		$node			Node to get index for
	 *	@return 	int
	 */
	private function getNodeIndex( $node )
	{
		for( $i=0; $i<$this->count(); $i++ )
			if( $this->nodes[$i] == $node )
				return $i;
		return NULL;
	}

	/**
	 *	Returns an array of all nodes in this NodeSet.
	 *	@access		public
	 *	@return 	array
	 */
	public function getNodes(): array
	{
		return $this->nodes;
	}

	/**
	 *	Indicates whether a Node is existing in this NodeSet.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$node			Node to be searched for
	 *	@return 	bool
	 */
	public function isNode( $node ): bool
	{
		foreach( $this->nodes as $_node )
			if( $_node == $node )
				return TRUE;
		return FALSE;
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
