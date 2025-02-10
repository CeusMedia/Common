<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Edge in a graph
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

/**
 *	Edge in a graph
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Edge
{
	/**	@var		Node		$sourceNode		Source Node of Edge */
 	protected Node $sourceNode;
	/**	@var		Node		$targetNode		Target Node of Edge */
	protected Node $targetNode;
	/**	@var		int			$edgeValue		Value of Edge */
	protected int $edgeValue	= 1;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( Node $sourceNode, Node $targetNode, int $edgeValue = 0 )
	{
		$this->setSourceNode( $sourceNode );
		$this->setTargetNode( $targetNode );
		$this->setEdgeValue( $edgeValue );
	}

	/**
	 *	Returns the Value of this Edge.
	 *	@access		public
	 *	@return		int
	 */
	public function getEdgeValue(): int
	{
		return $this->edgeValue;
	}

	/**
	 *	Returns the Source Node of this Edge.
	 *	@access		public
	 *	@return		Node
	 */
	public function getSourceNode(): Node
	{
		return $this->sourceNode;
	}

	/**
	 *	Returns the Target Node of this Edge.
	 *	@access		public
	 *	@return		Node
	 */
	public function getTargetNode(): Node
	{
		return $this->targetNode;
	}

	/**
	 *	Setting the Value of this Edge.
	 *	@access		public
	 *	@param		int					$edgeValue		Value of this Edge
	 *	@return		self
	 */
	public function setEdgeValue( int $edgeValue ): self
	{
		$this->edgeValue = $edgeValue;
		return $this;
	}

	/**
	 *	Setting the Source Node of this Edge.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@return		self
	 */
	public function setSourceNode( Node $sourceNode ): self
	{
		$this->sourceNode = $sourceNode;
		return $this;
	}

	/**
	 *	Setting the Target Node of this Edge.
	 *	@access		public
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return		self
	 */
	public function setTargetNode( Node $targetNode ): self
	{
		$this->targetNode = $targetNode;
		return $this;
	}
}
