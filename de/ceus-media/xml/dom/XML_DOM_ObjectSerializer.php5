<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
import( 'de.ceus-media.xml.dom.XML_DOM_Builder' );
/**
 *	Serializer for Data Object into XML.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
/**
 *	Serializer for Data Object into XML.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
class XML_DOM_ObjectSerializer
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Builds XML String from an Object.
	 *	@access		public
	 *	@param		mixed		object	Object to serialize
	 *	@param		string		encoding	Encoding Type
	 *	@return		string
	 */
	function serialize( $object, $encoding = "utf-8" )
	{
		$root	= $this->serializeToTree( $object );
		$builder	= new XML_DOM_Builder();
		$serial	= $builder->build( $root, $encoding );
		return $serial;
	}

	/**
	 *	Builds XML Tree from an Object.
	 *	@access		public
	 *	@param		mixed		object	Object to serialize
	 *	@return		string
	 */
	function serializeToTree( $object )
	{
		$root	= new XML_DOM_Node( "object" );
		$root->setAttribute( 'class', get_class( $object ) );
		$attributes	= array();
		$vars	= get_object_vars( $object );
		$this->_serializeVarsRec( $vars, $root );
		return $root;
	}
	
	/**
	 *	Adds XML Nodes to a XML Tree by their Type while supporting nested Arrays.
	 *	@access		private
	 *	@param		array			array	Array of Vars to add
	 *	@param		XML_DOM_Node	node	current XML Tree Node
	 *	@return		string
	 */
	function _serializeVarsRec( $array, &$node )
	{
		foreach( $array as $key => $value)
		{
			switch( gettype( $value ) )
			{
				case 'NULL':
					$child	=& new XML_DOM_Node( "null" );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'boolean':
					$child	=& new XML_DOM_Node( "boolean", (int) $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'string':
					$child	=& new XML_DOM_Node( "string", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'integer':
					$child	=& new XML_DOM_Node( "integer", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'double':
					$child	=& new XML_DOM_Node( "double", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'array':
					$child	=& new XML_DOM_Node( "array" );
					$child->setAttribute( "name", $key );
					$this->_serializeVarsRec( $value, $child );
					$node->addChild( $child );
					break;
			}
		}
	}
}
?>