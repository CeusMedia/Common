<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	EdgeSet to store and manipulate edges in a graph.
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

use Countable;
use Exception;
use InvalidArgumentException;

/**
 *	EdgeSet to store and manipulate edges in a graph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class EdgeSet implements Countable
{
	/**	@var		Edge[]				$edges			Array of all Edges */
	protected $edges = [];

	/**
	 *	Adds a new Edge and returns reference of this Edge.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@param		int			$value			Value of this Edge
	 *	@return		Edge
	 *	@throws		Exception
	 *	@throws		InvalidArgumentException
	 */
	public function addEdge( Node $sourceNode, Node $targetNode, int $value = 0 ): Edge
	{
		if( $this->isEdge( $sourceNode, $targetNode ) ) {
			$edge	= $this->getEdge( $sourceNode, $targetNode );
 			if( $value == $edge->getEdgeValue() )
				throw new InvalidArgumentException( 'Edge is already set.' );
			else
				$this->removeEdge( $sourceNode, $targetNode );
		}
		$newEdge = new Edge( $sourceNode, $targetNode, $value );
		$this->edges[] = $newEdge;
		return $newEdge;
	}

	/**
	 *	Returns the amount of Edges in this EdgeSet.
	 *	@access		public
	 *	@return 	int
	 */
	public function count(): int
	{
		return count( $this->edges );
	}

	/**
	 *	Returns an Edge existing in this EdgeSet.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	Edge|NULL
	 */
	public function getEdge( Node $sourceNode, Node $targetNode ): ?Edge
	{
		$index = $this->getEdgeIndex( $sourceNode, $targetNode );
		if( $index !== -1 )
			return $this->edges[$index];
		return NULL;
	}

	/**
	 *	Returns Index of an Edge in this EdgeSet.
	 *	@access		private
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	int
	 */
	private function getEdgeIndex( Node $sourceNode, Node $targetNode ): int
	{
		for( $i=0; $i<sizeof( $this->edges ); $i++ ) {
			$edge = $this->edges[$i];
			$isSource = $edge->getSourceNode() === $sourceNode;
			$isTarget = $edge->getTargetNode() === $targetNode;
			if( $isSource && $isTarget )
				return $i;
		}
		return -1;
	}

	/**
	 *	Returns an Array of all Edges in this EdgeSet.
	 *	@access		public
	 *	@return 	Edge[]
	 */
	public function getEdges(): array
	{
		return $this->edges;
	}

	/**
	 *	Indicates whether an Edge is existing in this EdgeSet.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return 	bool
	 */
	public function isEdge( Node $sourceNode, Node $targetNode ): bool
	{
		foreach( $this->edges as $edge ) {
			$isSource = $edge->getSourceNode() === $sourceNode;
			$isTarget = $edge->getTargetNode() === $targetNode;
			if( $isSource && $isTarget )
				return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Removing an Edge.
	 *	@access		public
	 *	@param		Node		$sourceNode		Source Node of this Edge
	 *	@param		Node		$targetNode		Target Node of this Edge
	 *	@return		void
	 *	@throws		Exception
	 */
	public function removeEdge( Node $sourceNode, Node $targetNode )
	{
		if( !$this->isEdge( $sourceNode, $targetNode ) )
			throw new Exception( 'Edge is not existing.' );
		$index = $this->getEdgeIndex( $sourceNode, $targetNode );
		unset( $this->edges[$index] );
		sort( $this->edges );
	}
}
