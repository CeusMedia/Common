<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.FileReader' );
import( 'de.ceus-media.xml.dom.FileWriter' );
/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *	@package		xml.dom
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *	@package		xml.dom
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class XML_DOM_Storage extends ADT_OptionObject
{
	/**	@var	string			$fileName		URI of XML File */
	protected $fileName;
	/**	@var	array			$storage		Array for Storage Operations */
	protected $storage	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return 	void
	 */
	public function __construct( $fileName )
	{
		parent::__construct();
		$this->setOption( 'tag_root',	"storage" );
		$this->setOption( 'tag_level',	"rack" );
		$this->setOption( 'tag_pair',	"value" );
		$this->fileName	= $fileName;

		if( !file_exists( $this->fileName ) )
			$this->write();
		$reader	= new XML_DOM_FileReader( $this->fileName );
		$tree	= $reader->read();
		$this->readRecursive( $tree, $this->storage );
	}
	
	/**
	 *	Returns value of a Path in the Storage.
	 *	@access		public
	 *	@param		string		$path			Path to stored Value
	 *	@param		array		$array			current Position in Storage Array
	 *	@return 	mixed
	 */
	public function get( $path, $array = NULL )
	{
		if( $array == NULL )
			$array	= $this->storage;
		if( substr_count( $path, "." ) )
		{
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			$array	= (array) $array[$step];
			return $this->get( $path, $array );
		}
		else
		{
			if( in_array( $path, array_keys( $array ) ) )
				return $array[$path];
			else
				return NULL;
		}
	}
	
	/**
	 *	Reads XML File recursive into array for Storage Operations.
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node		Current Node to read
	 *	@param		array			$array		Current Array in Storage
	 *	@return 	void
	 */
	protected function readRecursive( $node, &$array )
	{
		$nodeTag	= $node->getNodename();
		$nodeName	= $node->getAttribute( 'name' );
		if( $nodeTag == $this->getOption( 'tag_root' ) )
			foreach( $node->getChildren() as $child )
				$this->readRecursive( $child, $array );
		else if( $nodeTag == $this->getOption( 'tag_level' ) )
			foreach( $node->getChildren() as $child )
				$this->readRecursive( $child, $array[$nodeName] );
		else if( $nodeTag	== $this->getOption( 'tag_pair' ) )
		{
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
	 */
	public function remove( $path, $write = false )
	{
		$result	= $this->removeRecursive( $path, $this->storage );
		if( $write && $result )
			return (bool) $this->write();
		return $result;
	}

	/**
	 *	Recursive removes a Value From the Storage by its Path.
	 *	@access		protected
	 *	@param		string		$path			Path to value
	 *	@param		mixed		$value			Value to set at Path
	 *	@param		array		$array			Current Array in Storage
	 *	@return 	bool
	 */
	protected function removeRecursive( $path, &$array )
	{
		if( substr_count( $path, "." ) )
		{
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			return $this->removeRecursive( $path, $array[$step] );
		}
		else if( isset( $array[$path] ) )
		{
			unset( $array[$path] );
			return true;
		}
		return false;
	}
	
	/**
	 *	Sets a Value in the Storage by its Path.
	 *	@access		public
	 *	@param		string		$path			Path to value
	 *	@param		mixed		$value			Value to set at Path
	 *	@param		bool		$write			Flag: write on Update
	 *	@return 	bool
	 */
	public function set( $path, $value, $write = false )
	{
		$type	= gettype( $value );
		if( !in_array( $type, array( "double", "integer", "boolean", "string" ) ) )
			throw new InvalidArgumentException( "Value must be of type double, integer, boolean or string. ".ucfirst( $type )." given", E_USER_WARNING );
		$result	=	$this->setRecursive( $path, $value, $this->storage );
		if( $write && $result )
			return (bool) $this->write();
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
	protected function setRecursive( $path, $value, &$array )
	{
		if( substr_count( $path, "." ) )
		{
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			return $this->setRecursive( $path, $value, $array[$step] );
		}
		else if( !(isset( $array[$path] ) && $array[$path] == $value ) )
		{
			$array[$path] = $value;
			return true;
		}
		return false;
	}

	/**
	 *	Writes XML File from Storage.
	 *	@access		public
	 *	@return 	bool
	 */
	public function write()
	{
		$writer	= new XML_DOM_FileWriter( $this->fileName );
		$root	= new XML_DOM_Node( $this->getOption( 'tag_root' ) );
		$this->writeRecursive( $root, $this->storage );
		return $writer->write( $root );
	}
	
	/**
	 *	Writes XML File recursive from Storage.
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node		Current Node to read
	 *	@param		array			$array		Current Array in Storage
	 *	@return 	void
	 */
	protected function writeRecursive( &$node, $array )
	{
		foreach( $array as $key => $value )
		{
			if( is_array( $value ) )
			{
				$child	=& new XML_DOM_Node( $this->getOption( 'tag_level' ) );
				$child->setAttribute( 'name', $key );
				$this->writeRecursive( $child, $array[$key] );
				$node->addChild( $child );
			}
			else
			{
				$child	=& new XML_DOM_Node( $this->getOption( 'tag_pair' ) );
				$child->setAttribute( 'name', $key );
				$child->setAttribute( 'type', gettype( $value ) );
				$child->setContent( utf8_encode( $value ) );
				$node->addChild( $child );
			}
		}
	}
}
?>