<?php
import( 'de.ceus-media.adt.graph.DirectedWeightedGraph' );
/**
 *	Directed Acyclic Graph.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@extends		DirectedGraph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.3
 */
/**
 *	Directed Acyclic Graph.
 *	@package		adt.graph
 *	@extends		DirectedGraph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.3
 */
class ADT_Graph_DirectedAcyclicWeightedGraph extends ADT_Graph_DirectedWeightedGraph
{
	/**
	 *	Adds an Edge and returns the reference on the new Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@param		int			$value		Value of this Edge
	 *	@return		Edge
	 */
	public function addEdge( $source, $target, $value = 1 )
	{
		$edge	= $this->edgeSet->addEdge( $source, $target, $value );
		if( $this->hasCycle() )
		{
			$this->edgeSet->removeEdge( $source, $target );
			return false;
		}
		return $edge;
	}

	/**
	 *	Removes an Edge.
	 *	@access		public
	 *	@param		Node		$source		Source Node of this Edge
	 *	@param		Node		$target		Target Node of this Edge
	 *	@return		void
	 */
	public function removeEdge( $source, $target )
	{
		$value	= $this->getEdgeValue( $source, $target );
		$this->edgeSet->removeEdge( $source, $target );
		if( !$this->isCoherent() )
			$this->edgeSet->addEdge( $source, $target, $value );
	}
}
?>