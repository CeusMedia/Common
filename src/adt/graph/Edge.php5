<?php
/**
 *	Edge in a graph
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		adt.graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Edge in a graph
 *	@package		adt.graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class ADT_Graph_Edge
{
	/**	@var		ADT_Graph_Node		$sourceNode		Source Node of Edge */
 	protected $sourceNode;
	/**	@var		ADT_Graph_Node		$targetNode		Target Node of Edge */
	protected $targetNode;
	/**	@var		int					$edgeValue		Value of Edge */
	protected $edgeValue				= 1;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $sourceNode, $targetNode, $edgeValue = false )
	{
		$this->setSourceNode( $sourceNode );
		$this->setTargetNode( $targetNode );
		$this->setEdgeValue( $edgeValue );
	}

	/**
	 *	Setting the Value of this Edge.
	 *	@access		public
	 *	@param		int					$edgeValue		Value of this Edge
	 *	@return		void
	 */
	public function setEdgeValue( $edgeValue )
	{
		$this->edgeValue = $edgeValue;
	}

	/**
	 *	Setting the Source Node of this Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@return		void
	 */
	public function setSourceNode( $sourceNode )
	{
		$this->sourceNode = $sourceNode;
	}

	/**
	 *	Setting the Target Node of this Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@return		void
	 */
	public function setTargetNode( $targetNode )
	{
		$this->targetNode = $targetNode;
	}

	/**
	 *	Returns the Value of this Edge.
	 *	@access		public
	 *	@return		int
	 */
	public function getEdgeValue()
	{
		return $this->edgeValue;
	}

	/**
	 *	Returns the Source Node of this Edge.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getSourceNode()
	{
		return $this->sourceNode;
	}

	/**
	 *	Returns the Target Node of this Edge.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getTargetNode()
	{
		return $this->targetNode;
	}
}
?>