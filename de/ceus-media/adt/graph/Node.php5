<?php
/**
 *	Node in a graph
 *	@package		adt.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
/**
 *	Node in a graph
 *	@package		adt.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.2
 */
class ADT_Graph_Node
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
	
	public function __toString()
	{
		return "(".$this->nodeName.":".$this->nodeValue.")";
	}
}
?>