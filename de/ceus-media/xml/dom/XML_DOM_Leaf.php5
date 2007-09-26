<?php
/**
 *	XML Leaf Node DOM Implementation.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	XML Leaf Node DOM Implementation.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class XML_DOM_Leaf
{
	/**	@var	string	_nodename	Name of XML Leaf Node */
	var $_nodename;
	/**	@var	int		_oid			Object ID */
	var $_oid;
	/**	@var	array	_attributes	Map of XML Leaf Node attributes */
	var $_attributes	= array ();
	/**	@var	string	_content		Content of XML Leaf Node */
	var $_content;
	/**	@var	array	_children		Array of Children for XML_DOM_Node and sorting with DevOutput::print_m */
	var $_children	= array ();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		nodename	Name of XML Leaf Node
	 *	@param		string		content		Content of XML Leaf Node
	 *	@return		void
	 */
	public function __construct ($nodename, $content = NULL)
	{
		$this->_setNodename ($nodename);
		if ($content !== NULL)
			$this->setContent ($content);
	}
	
	/**
	 *	Returns an attribute if it is set.
	 *	@access		public
	 *	@param		string		key			Key of attribute
	 *	@return		string
	 */
	function getAttribute ($key)
	{
		if ($this->hasAttribute ($key))
			return $this->_attributes[$key];
	}

	/**
	 *	Returns all attributes as associative array.
	 *	@access		public
	 *	@param		string		key			Key of attribute
	 *	@return		string
	 */
	function getAttributes ()
	{
		return $this->_attributes;
	}
	
	/**
	 *	Returns Content if it is set.
	 *	@access		public
	 *	@return		string
	 */
	function getContent ()
	{
		return $this->_content;
	}
	
	/**
	 *	Returns the Object ID.
	 *	@access		public
	 *	@return		int
	 */
	function getOid()
	{
		return $this->_oid;
	}

	/**
	 *	Returns Nodename.
	 *	@access		public
	 *	@return		string
	 */
	function getNodename ()
	{
		return $this->_nodename;
	}
	
	/**
	 *	Indicates whether XML Node has attributes.
	 *	@access		public
	 *	@return		bool
	 */
	function hasAttributes ()
	{
		return count ($this->_attributes) > 0;
	}
	
	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@param		string		key		Name of attribute to check
	 *	@return		bool
	 */
	function hasAttribute ($key)
	{
		return array_key_exists ($key, $this->_attributes);
	}

	/**
	 *	Indicated whether XML Node has content.
	 *	@access		public
	 *	@param		string		content		Content of XML Node
	 *	@return		void
	 */
	function hasContent ()
	{
		return (string)$this->_content != "";
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string		key			Key of attribute to be removed
	 *	@return		void
	 */
	function removeAttribute ($key)
	{
		if ($this->hasAttribute ($key))
			unset ($this->_attributes[$key]);
	}

	/**
	 *	Removes content of XML Node.
	 *	@access		public
	 *	@return		void
	 */
	function removeContent ()
	{
		if ($this->hasContent ())
			$this->setContent ("");
	}
	
	/**
	 *	Sets an attribute.
	 *	@access		public
	 *	@param		string		key			Key of attribute
	 *	@param		string		value		Value of attribute
	 *	@return		void
	 */
	function setAttribute ($key, $value)
	{
		if ($this->getAttribute ($key) !== $value)
			$this->_attributes[$key] = $value;
	}
	
	/**
	 *	Sets content of XML Node.
	 *	@access		public
	 *	@param		string		content		Content of XML Node
	 *	@return		bool
	 */
	function setContent ($content)
	{
		$this->_content = $content;
		return true;
	}

	/**
	 *	Sets a Object ID.
	 *	@access		public
	 *	@param		int			oid			Object ID of XML Node
	 *	@return		void
	 */
	function setOid( $oid )
	{
		$this->_oid	= $oid;
	}

	/**
	 *	Sets Name of XML Leaf Node.
	 *	@access		public
	 *	@param		string		name		Name of XML Node
	 *	@return		void
	 */
	function _setNodename ($name)
	{
		$this->_nodename = $name;
	}
}
?>