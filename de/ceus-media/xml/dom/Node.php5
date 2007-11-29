<?php
import ("de.ceus-media.xml.dom.Leaf");
/**
 *	XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_DOM_Node extends XML_DOM_Leaf
{
	/**	@var	array	$children		List of Child Nodes  */
	protected $children	= array();
	
	/**
	 *	Adds a Child Node, returns the Node just added.
	 *	@access		public
	 *	@param		XML_DOM_Node	$xmlNode		XML Node to add
	 *	@return		XML_DOM_Node
	 */
	public function addChild( $xmlNode )
	{
		$this->children[] = $xmlNode;
		return $xmlNode;
	}

	/**
	 *	Returns all Child Nodes.
	 *	@access		public
	 *	@return		array
	 */
	public function getChildren()
	{
		return $this->children;
	}
	
	/**
	 *	Returns a Child Nodes by its name.
	 *	@access		public
	 *	@param		string		$name		Name of Child Node
	 *	@return		XML_DOM_Node
	 */
	public function getChild( $name )
	{
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $name )
				return $this->children[$i];
	}
	
	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren()
	{
		return (bool) count( $this->children ) > 0;
	}
	
	/**
	 *	Removes all Child Nodes with given name.
	 *	@access		public
	 *	@param		string		$name		Name of Child Node to be removed
	 *	@return		void
	 */
	public function removeChild( $name )
	{
		foreach( $this->children as $child )
			if( $child->getNodeName() != $nodeName )
				$children[] = $child;
		$this->children = $children;
	}
}
?>