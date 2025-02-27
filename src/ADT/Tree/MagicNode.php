<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Magic Node.
 *
 *	Copyright (c) 2015-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Tree;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;

/**
 *	Magic Node.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MagicNode
{
	/**	@var	array		$data		Map of nested nodes */
	public $data	= [];

	/**	@var	mixed		$value		Node value */
	public $value;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$value		Value to set for node
	 *	@return		void
	 */
	public function __construct( $value = NULL )
	{
		$this->value	= $value;
	}

	/**
	 *	Magic function to get value of current node or node for next magic call level.
	 *	@access		public
	 *	@param		string		$key		Key of nested node
	 *	@return		MagicNode
	 */
	public function __get( string $key ): self
	{
		if( !isset( $this->data[$key] ) )
			return new MagicNode( NULL );
		return $this->data[$key];
	}

	/**
	 *	Magic function to set value of current node or get node for next magic call level.
	 *	@access		public
	 *	@param		string		$key		Key of nested node
	 *	@param		mixed		$value		Value to set on current or nested node
	 *	@return		void
	 */
	public function __set( string $key, $value )
	{
		if( !isset( $this->data[$key] ) )
			$this->data[$key]	= new MagicNode( $value );
		else
			$this->data[$key]->value	= $value;
	}

	/**
	 *	Magic function to get string value of node.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(): string
	{
		return (string) $this->value;
	}

	/**
	 *	Imports array.
	 *	@access		public
	 *	@param		array		$array		Array to import
	 *	@return		void
	 */
	public function fromArray( array $array )
	{
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
	 *	@param		string		$json		JSON to import
	 *	@return		void
	 */
	public function fromJson( string $json )
	{
		$this->fromArray( json_decode( $json, TRUE ) );
	}

	/**
	 *	Returns nested nodes as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		$array	= [];
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
	 *	@return		string
	 */
	public function toJson(): string
	{
		return JsonEncoder::create()->encode( $this->toArray() );
	}

	/**
	 *	Returns or sets value of node.
	 *	Returns node value of no new value is given.
	 *	Sets node value of new value is given.
	 *	@access		public
	 *	@param		mixed		$value		Value to set on node
	 *	@return		mixed|NULL
	 */
	public function value( $value = NULL )
	{
		if( !is_null( $value ) )
			$this->value	= $value;
		return $this->value;
	}
}
