<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileReader' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileWriter' );
/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Storage with unlimited depth to store pairs of data in XML Files.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileReader
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class XML_DOM_Storage extends OptionObject
{
	/**	@var	string	_filename		URI of XML File */
	var $_filename;
	/**	@var	array	_storage		Array for Storage Operations */
	var $_storage	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		URI of XML File
	 *	@return 		void
	 */
	public function __construct( $filename )
	{
		parent::__construct();
		$this->setOption( 'tag_root',	"storage" );
		$this->setOption( 'tag_level',	"rack" );
		$this->setOption( 'tag_pair',	"value" );
		$this->_filename	= $filename;	
	}
	
	function fromArray( $array )
	{
		$this->_storage	= $array;
	}
	
	/**
	 *	Returns value of a Path in the Storage.
	 *	@access		public
	 *	@param		string	path		Path to stored Value
	 *	@param		array	array	current Position in Storage Array
	 *	@return 		mixed
	 */
	function get( $path, $array = NULL )
	{
		if( $array == NULL )
			$array	= $this->_storage;
		if( substr_count( $path, "." ) )
		{
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			$array	= (array)$array[$step];
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
	 *	Reads XML File into array for Storage Operations.
	 *	@access		public
	 *	@param		bool		abort		Flag: break on Errors showing Messages
	 *	@param		bool		verbose		Flag: show Warnings
	 *	@return 		void
	 */
	function read( $abort = true, $verbose = true)
	{
		$xr	= new XML_DOM_FileReader();
		if( $xr->loadFile( $this->_filename, $abort, $verbose ) )
		{
			$tree	= $xr->parse();
			$this->_read( $tree, $this->_storage );
		}
		else 	if( $verbose )
			trigger_error( "XML_DOM_Storage[read]: XML File '".$this->_filename."' could not been read.", E_USER_WARNING );
	}
	
	/**
	 *	Sets a Value in the Storage by its Path.
	 *	@access		public
	 *	@param		string	path		Path to value
	 *	@param		mixed	value	Value to set at Path
	 *	@return 		void
	 */
	function set( $path, $value )
	{
		$type	= gettype( $value );
		if( in_array( $type, array( "double", "integer", "boolean", "string" ) ) )
			$this->_set( $path, $value, $this->_storage );
		else
			trigger_error( "Value must be of type double, integer, boolean or string. ".ucfirst( $type )." given", E_USER_WARNING );
	}

	/**
	 *	Returns Storage as Array.
	 *	@access		public
	 *	@return 		array
	 */
	function toArray()
	{
		return $this->_storage;
	}
	
	/**
	 *	Writes XML File from Storage.
	 *	@access		public
	 *	@return 		void
	 */
	function write()
	{
		$root	= new XML_DOM_Node( $this->getOption( 'tag_root' ) );
		$this->_write( $root, $this->_storage );
		$xw	= new XML_DOM_FileWriter( $this->_filename );
		$xw->write( $root );
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Reads XML File recursive into array for Storage Operations.
	 *	@access		private
	 *	@param		XML_DOM_Node	node	Current Node to read
	 *	@param		array			array	Current Array in Storage
	 *	@return 		void
	 */
	function _read( $node, &$array )
	{
		$nodetag		= $node->getNodename();
		$nodename	= $node->getAttribute( 'name' );
		if( $nodetag == $this->getOption( 'tag_root' ) )
			foreach( $node->getChildren() as $child )
				$this->_read( $child, $array );
		else if( $nodetag == $this->getOption( 'tag_level' ) )
			foreach( $node->getChildren() as $child )
				$this->_read( $child, $array[$nodename] );
		else if( $nodetag	== $this->getOption( 'tag_pair' ) )
		{
			$value	= $node->getContent();
			if( $type = $node->getAttribute( 'type' ) )
				settype( $value, $type );
			if( gettype( $value ) == "string" )
				$array[$nodename]	= utf8_decode( $value );
			else
				$array[$nodename]	= $value;
		}
	}
	
	/**
	 *	Sets recursive a Value in the Storage by its Path.
	 *	@access		private
	 *	@param		string	path		Path to value
	 *	@param		mixed	value	Value to set at Path
	 *	@param		array	array	Current Array in Storage
	 *	@return 		void
	 */
	function _set( $path, $value, &$array )
	{
		if( substr_count( $path, "." ) )
		{
			$parts	= explode( ".", $path );
			$step	= array_shift( $parts );
			$path	= implode( ".", $parts );
			$this->_set( $path, $value, $array[$step] );
		}
		else
			$array[$path] = $value;
	}
	
	/**
	 *	Writes XML File recursive from Storage.
	 *	@access		private
	 *	@param		XML_DOM_Node	node	Current Node to read
	 *	@param		array			array	Current Array in Storage
	 *	@return 		void
	 */
	function _write( &$node, $array )
	{
		foreach( $array as $key => $value )
		{
			if( is_array( $value ) )
			{
				$child	=& new XML_DOM_Node( $this->getOption( 'tag_level' ) );
				$child->setAttribute( 'name', $key );
				$this->_write( $child, $array[$key] );
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