<?php
/**
 *	Node in a graph
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
namespace CeusMedia\Common\ADT\Graph;

/**
 *	Node in a graph
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Graph
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Node
{
	/**	@var	string		$nodeName 		Name of this Node */
	protected $nodeName;
	/**	@var	mixed		$nodeValue		Value of this Node */
	protected $nodeValue;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$nodeName		Name of this Node
	 *	@param		mixed		$value			Value of this Node
	 *	@return		void
	 */
	public function __construct( $nodeName, $nodeValue = false )
	{
		$this->setNodeName( $nodeName );
		$this->setNodeValue( $nodeValue );
	}

	/**
	 *	Returns the Name of this Node.
	 *	@access		public
	 *	@return		string
	 */
	public function getNodeName()
	{
		return $this->nodeName;
	}

	/**
	 *	Returns the Value of this Node.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getNodeValue()
	{
		return $this->nodeValue;
	}

	/**
	 *	Setting the Name of this Node.
	 *	@access		public
	 *	@param		string		$nodeName		Name of this Node
	 *	@return		void
	 */
	public function setNodeName( $nodeName )
	{
		$this->nodeName = $nodeName;
	}

	/**
	 *	Setting the Value of this Node.
	 *	@access		public
	 *	@param		mixed		$nodeValue		Value of this Node
	 *	@return		void
	 */
	public function setNodeValue( $nodeValue )
	{
		$this->nodeValue = $nodeValue;
	}

	public function __toString()
	{
		return "(".$this->nodeName.":".$this->nodeValue.")";
	}
}
