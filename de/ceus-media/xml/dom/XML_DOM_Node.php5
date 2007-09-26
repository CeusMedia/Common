<?php
import ("de.ceus-media.xml.dom.XML_DOM_Leaf");
/**
 *	XML Node DOM Implementation.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	XML Node DOM Implementation.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo			cd
 */
class XML_DOM_Node extends XML_DOM_Leaf
{
	/**	@var	array	_children		List of Child Nodes  */
//	var $_children	= array ();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		nodename	Name of XML Leaf Node
	 *	@param		string		content		Content of XML Leaf Node
	 *	@return		void
	 */
	public function __construct( $nodename, $content = false )
	{
		parent::__construct( $nodename, $content );
	}
	
	/**
	 *	Adds a Child Node.
	 *	@access		public
	 *	@param		XML_DOM_Node	xml_node		Reference to XML Node
	 *	@return		bool
	 */
	function addChild( &$xml_node )
	{
		$this->_children [] =& $xml_node;
		return true;
	}
	
	function addComment( &$xml_node )
	{
		$this->_comments [] =& $xml_node;
	}

	/**
	 *	Returns all Child Nodes.
	 *	@access		public
	 *	@return		array
	 */
	function & getChildren()
	{
		return $this->_children;
	}
	
	/**
	 *	Returns a Child Nodes by its name.
	 *	@access		public
	 *	@param		string		name		Name of Child Node
	 *	@return		XML_DOM_Node
	 */
	function & getChild( $name )
	{
		for( $i=0; $i<count( $this->_children ); $i++ )
		{
			if( $this->_children[$i]->getNodename() == $name )
				return $this->_children[$i];
			
		}
	}
	
	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@return		bool
	 */
	function hasChildren()
	{
		return count( $this->_children ) > 0;
	}
	
	/**
	 *	Removes all Child Nodes with given name.
	 *	@access		public
	 *	@param		string		name		Name of Child Node to be removed
	 *	@return		bool
	 */
	function removeChild ($name)
	{
		foreach( $this->_children as $child )
		{
			if( $child->getNodename() != $nodename )
				$children[] =& $child;
		}
		$this->_children = $children;
	}
}
?>