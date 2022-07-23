<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Directed Acyclic Graph.
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

use Exception;

/**
 *	Directed Acyclic Graph.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DirectedAcyclicWeighted extends DirectedWeighted
{
	/**
	 *	Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		int			$value		Value of this Edge
	 *	@param		bool		$allowCycle	Flag: allow cycle or quit with exception
	 *	@return		Edge
	 *	@throws		Exception
	 */
	public function addEdge( Node $source, Node $target, int $value = 1, bool $allowCycle = FALSE ): Edge
	{
		$edge	= $this->edgeSet->addEdge( $source, $target, $value );
		if( !$allowCycle && $this->hasCycle() ){
			$this->edgeSet->removeEdge( $source, $target );
			throw new Exception( 'Graph would have a cycle' );
		}
		return $edge;
	}

	/**
	 *	Removes an Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		void
	 *	@throws		Exception
	 */
 	public function removeEdge( Node $source, Node $target )
	{
		$value	= $this->getEdgeValue( $source, $target );
		$this->edgeSet->removeEdge( $source, $target );
		if( !$this->isCoherent() )
			$this->edgeSet->addEdge( $source, $target, $value );
	}
}
