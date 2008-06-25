<?php
/**
 *	Edge in a graph
 *	@package		adt.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Edge in a graph
 *	@package		adt.graph
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class ADT_Graph_Edge
{
	/**	@var		ADT_Graph_Node		$sourceNode		Source Node of Edge */
 	protected $sourceNode;
	/**	@var		ADT_Graph_Node		$targetNode		Target Node of Edge */
	protected $targetNode;
	/**	@var		int					$edgeValue		Value of Edge */
	protected $edgeValue				= 1;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $sourceNode, $targetNode, $edgeValue = false )
	{
		$this->setSourceNode( $sourceNode );
		$this->setTargetNode( $targetNode );
		$this->setEdgeValue( $edgeValue );
	}

	/**
	 *	Setting the Value of this Edge.
	 *	@access		public
	 *	@param		int					$edgeValue		Value of this Edge
	 *	@return		void
	 */
	public function setEdgeValue( $edgeValue )
	{
		$this->edgeValue = $edgeValue;
	}

	/**
	 *	Setting the Source Node of this Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$sourceNode		Source Node of this Edge
	 *	@return		void
	 */
	public function setSourceNode( $sourceNode )
	{
		$this->sourceNode = $sourceNode;
	}

	/**
	 *	Setting the Target Node of this Edge.
	 *	@access		public
	 *	@param		ADT_Graph_Node		$targetNode		Target Node of this Edge
	 *	@return		void
	 */
	public function setTargetNode( $targetNode )
	{
		$this->targetNode = $targetNode;
	}

	/**
	 *	Returns the Value of this Edge.
	 *	@access		public
	 *	@return		int
	 */
	public function getEdgeValue()
	{
		return $this->edgeValue;
	}

	/**
	 *	Returns the Source Node of this Edge.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getSourceNode()
	{
		return $this->sourceNode;
	}

	/**
	 *	Returns the Target Node of this Edge.
	 *	@access		public
	 *	@return		ADT_Graph_Node
	 */
	public function getTargetNode()
	{
		return $this->targetNode;
	}
}
?>