<?php
/**
 *	Magic Node.
 *
 *	Copyright (c) 2014 Christian Würker (ceusmedia.de)
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
 *	@package		ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2014 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Magic Node.
 *	@category		cmClasses
 *	@package		ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2014 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class ADT_Tree_MagicNode{

	/**	@var	array		$data		Map of nested nodes */
	public $data	= array();

	/**	@var	mixed		$value		Node value */
	public $value;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$value		Value to set for node
	 *	@return		void
	 */
	public function __construct( $value = NULL ){
		$this->value	= $value;
	}

	/**
	 *	Magic function to get value of current node or node for next magic call level.
	 *	@access		public
	 *	@param		string		$key		Key of nested node
	 *	@return		ADT_Tree_MagicNode
	 */
	public function __get( $key ){
		if( !isset( $this->data[$key] ) )
			return new ADT_Tree_MagicNode( NULL );
		return $this->data[$key];
	}

	/**
	 *	Magic function to set value of current node or get node for next magic call level.
	 *	@access		public
	 *	@param		string		$key		Key of nested node
	 *	@param		mixed		$value		Value to set on current or nested node
	 *	@return		void
	 */
	public function __set( $key, $value ){
		if( !isset( $this->data[$key] ) )
			$this->data[$key]	= new ADT_Tree_MagicNode( $value );
		else
			$this->data[$key]->value	= $value;
	}

	/**
	 *	Magic function to get string value of node.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(){
		return (string) $this->value;
	}

	/**
	 *	Imports array.
	 *	@access		public
	 *	@param		array		$array		Array to import
	 *	@return		void
	 */
	public function fromArray( $array ){
		foreach( $array as $key => $value ){
			if( is_array( $value ) ){
				$this->__set( $key, NULL );
				$this->data[$key]->fromArray( $value );
			}
			else{
				$this->__set( $key, $value );
			}
		}

	}

	/**
	 *	Imports array.
	 *	@access		public
	 *	@param		array		$array		Array to import
	 *	@return		void
	 */
	public function fromJson( $json ){
		$this->fromArray( json_decode( $json, TRUE ) );
	}

	/**
	 *	Returns nested nodes as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(){
		$array	= array();
		foreach( $this->data as $key => $node ){
			if( count( $node->data ) )
				$array[$key]	= $node->toArray();
			else
				$array[$key]	= $node->value;
		}
		return $array;
	}

	/**
	 *	Returns nested nodes as JSON.
	 *	@access		public
	 *	@return		array
	 */
	public function toJson(){
		return json_encode( $this->toArray() );
	}

	/**
	 *	Returns or sets value of node.
	 *	Returns node value of no new value is given.
	 *	Sets node value of new value is given.
	 *	@access		public
	 *	@param		mixed		$value		Value to set on node
	 *	@return		mixed|NULL
	 */
	public function value( $value = NULL ){
		if( is_null( $value ) )
			return $this->value;
		$this->value	= $value;
	}
}
?>
