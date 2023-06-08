<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\ADT\OptionObject;
use DOMException;
use InvalidArgumentException;

/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Storage extends OptionObject
{
	/**	@var	string			$fileName		URI of XML File */
	protected $fileName;

	/**	@var	array			$storage		Array for Storage Operations */
	protected $storage	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return 	void
	 *	@throws		DOMException
	 */
	public function __construct( string $fileName )
	{
		parent::__construct();
		$this->setOption( 'tag_root',	"storage" );
		$this->setOption( 'tag_level',	"rack" );
		$this->setOption( 'tag_pair',	"value" );
		$this->fileName	= $fileName;

		if( !file_exists( $this->fileName ) )
			$this->write();
		$reader	= new FileReader( $this->fileName );
		$tree	= $reader->read();
		$this->readRecursive( $tree, $this->storage );
	}

	/**
	 *	Returns value of a Path in the Storage.
	 *	@access		public
	 *	@param		string			$path			Path to stored Value
	 *	@param		array|NULL		$array			current Position in Storage Array
	 *	@return 	mixed
	 */
	public function get( string $path, ?array $array = NULL )
	{
		if( $array == NULL )
			$array	= $this->storage;
		if( substr_count( $path, "." ) ){
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			$array	= (array) $array[$step];
			return $this->get( $path, $array );
		}
		else{
			if( in_array( $path, array_keys( $array ), TRUE ) )
				return $array[$path];
			else
				return NULL;
		}
	}

	/**
	 *	Reads XML File recursive into array for Storage Operations.
	 *	@access		protected
	 *	@param		Node			$node		Current Node to read
	 *	@param		array			$array		Current Array in Storage
	 *	@return 	void
	 */
	protected function readRecursive( Node $node, array &$array )
	{
		$nodeTag	= $node->getNodename();
		$nodeName	= $node->getAttribute( 'name' );
		if( $nodeTag == $this->getOption( 'tag_root' ) )
			foreach( $node->getChildren() as $child )
				$this->readRecursive( $child, $array );
		else if( $nodeTag == $this->getOption( 'tag_level' ) )
			foreach( $node->getChildren() as $child )
				$this->readRecursive( $child, $array[$nodeName] );
		else if( $nodeTag	== $this->getOption( 'tag_pair' ) ){
			$value	= $node->getContent();
			if( $type = $node->getAttribute( 'type' ) )
				settype( $value, $type );
			if( gettype( $value ) == "string" )
				$array[$nodeName]	= utf8_decode( $value );
			else
				$array[$nodeName]	= $value;
		}
	}

	/**
	 *	Removes a Value from the Storage by its Path.
	 *	@access		public
	 *	@param		string		$path			Path to value
	 *	@param		bool		$write			Flag: write on Update
	 *	@return 	bool
	 *	@throws		DOMException
	 */
	public function remove( string $path, bool $write = FALSE ): bool
	{
		$result	= $this->removeRecursive( $path, $this->storage );
		if( $write && $result )
			return 0 !== $this->write();
		return $result;
	}

	/**
	 *	Recursive removes a Value From the Storage by its Path.
	 *	@access		protected
	 *	@param		string		$path			Path to value
	 *	@param		array		$array			Current Array in Storage
	 *	@return 	bool
	 */
	protected function removeRecursive( string $path, array &$array ): bool
	{
		if( substr_count( $path, "." ) ){
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			return $this->removeRecursive( $path, $array[$step] );
		}
		else if( isset( $array[$path] ) ){
			unset( $array[$path] );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Sets a Value in the Storage by its Path.
	 *	@access		public
	 *	@param		string		$path			Path to value
	 *	@param		mixed		$value			Value to set at Path
	 *	@param		bool		$write			Flag: write on Update
	 *	@return 	bool
	 *	@throws		DOMException
	 */
	public function set( string $path, $value, bool $write = FALSE ): bool
	{
		$type	= gettype( $value );
		if( !in_array( $type, ["double", "integer", "boolean", "string"], TRUE ) )
			throw new InvalidArgumentException( "Value must be of type double, integer, boolean or string. ".ucfirst( $type )." given", E_USER_WARNING );
		$result	=	$this->setRecursive( $path, $value, $this->storage );
		if( $write && $result )
			return 0 !== $this->write();
		return $result;
	}

	/**
	 *	Recursive sets a Value in the Storage by its Path.
	 *	@access		protected
	 *	@param		string		$path			Path to value
	 *	@param		mixed		$value			Value to set at Path
	 *	@param		array		$array			Current Array in Storage
	 *	@return 	bool
	 */
	protected function setRecursive( string $path, $value, array &$array ): bool
	{
		if( substr_count( $path, "." ) ){
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			if( !isset( $array[$step] ) )
				$array[$step] = [];
			return $this->setRecursive( $path, $value, $array[$step] );
		}
		else if( !(isset( $array[$path] ) && $array[$path] == $value ) ){
			$array[$path] = $value;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Writes XML File from Storage.
	 *	@access		public
	 *	@return 	int
	 *	@throws		DOMException
	 */
	public function write(): int
	{
		$writer	= new FileWriter( $this->fileName );
		$root	= new Node( $this->getOption( 'tag_root' ) );
		$this->writeRecursive( $root, $this->storage );
		return $writer->write( $root );
	}

	/**
	 *	Writes XML File recursive from Storage.
	 *	@access		protected
	 *	@param		Node			$node		Current Node to read
	 *	@param		array			$array		Current Array in Storage
	 *	@return 	void
	 */
	protected function writeRecursive( Node &$node, array $array )
	{
		foreach( $array as $key => $value ){
			if( is_array( $value ) ){
				$child	= new Node( $this->getOption( 'tag_level' ) );
				$child->setAttribute( 'name', $key );
				$this->writeRecursive( $child, $value );
			}
			else{
				$child	= new Node( $this->getOption( 'tag_pair' ) );
				$child->setAttribute( 'name', $key );
				$child->setAttribute( 'type', gettype( $value ) );
				$child->setContent( utf8_encode( $value ) );
			}
			$node->addChild( $child );
		}
	}
}
