<?php
/**
 *	Binary Tree.
 *	@package		adt.tree
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Binary Tree.
 *	@package		adt.tree
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class ADT_Tree_BinaryNode
{
	/**	@var 	mixed		$value		Value of the Root Element of this Tree */
	protected $value		= null;
	/**	@var	ADT_Tree_BinaryNode		$left		Left Child Tree */
	protected $left			= null;
	/**	@var	ADT_Tree_BinaryNode		$right		Right Child Tree */
	protected $right		= null;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$value		Value to be added to the Tree
	 *	@return		void
	 */
	public function __construct( $value = false )
	{
		if( $value )
			$this->add( $value );
	}

	/**
	 *	Adds a Node to the Tree.
	 *	@access		public
	 *	@param		mixed		$value		Value to be added to the Tree
	 *	@return		void
	 */
	public function add( $value )
	{
		if( !isset( $this->value ) )
			return $this->value = $value;
		if( $value == $this->value)
			return -1;
		if( $value < $this->value )
		{
			if( $this->left )
				$this->left->add( $value );
			else
				$this->left =& new ADT_Tree_BinaryNode( $value );
		}
		else if( $value > $this->value )
		{
			if( $this->right )
				$this->right->add( $value );
			else
				$this->right =& new ADT_Tree_BinaryNode( $value );
		}
	}
	
	/**
	 *	Returns the amount of Nodes in the Tree.
	 *	@access		public
	 *	@return		int
	 */
	public function countNodes()
	{
		$nodes = 1;
		if( $this->left || $this->right )
		{
			if( $this->left )
				$nodes += $this->left->countNodes();
			if( $this->right )
				$nodes += $this->right->countNodes();
		}
		return $nodes;
	}

	/**
	 *	Returns value of the Tree.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 *	Returns Left Child Tree.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getLeft()
	{
		return $this->left;
	}
	
	/**
	 *	Returns right Child Tree.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getRight()
	{
		return $this->right;
	}
	
	/**
	 *	Returns the height of the Tree.
	 *	@access		public
	 *	@return		int
	 */
	public function getHeight()
	{
		$left_height = $right_hight = 0;
		if( $this->left )
			$left_height	= $this->left->getHeight();				//Rekursiver Aufruf des linken Teilbaumes
		if( $this->right )
			$right_height	= $this->right->getHeight();			//Rekursiver Aufruf des rechten Teilbaumes
		$height = max( $left_height, $right_height ); 				//Vergleichen welcher der beiden Teilbäume höher ist
		$height++;													//Höhe hochzählen
		return $height;
	}

	/**
	 *	Indicates wheter a Value can be found in the Tree.
	 *	@access		public
	 *	@param		mixed		$value		Value to be found in the Tree
	 *	@return		void
	 */
	public function search( $value )
	{
		if( $value == $this->value )
			return $this;
		else if( $value < $this->value )
		{
			if( $this->left )
				return $this->left->search( $value );
		}
		else if( $value > $this->value )
		{
			if( $this->right )
				return $this->right->search( $value );
		}
		return false;
	}

	/**
	 *	Returns the Tree as HTML-Table.
	 *	@access		public
	 *	@return		string
	 */
	public function toTable()
	{
		$code = "<table cellspacing=1 cellpadding=0>\n<tr><td colspan=2 align=center><hr>".$this->value."</td></tr>";
		if( $this->left || $this->right )
		{
			$code .= "<tr><td align=center valign=top>";
			if( $this->left )
				$code .= $this->left->toTable();
			else $code .= "&nbsp;";
			$code .= "</td><td align=center valign=top>";
			if( $this->right )
				$code .= $this->right->toTable();
			else $code .= "&nbsp;";
			$code .= "</td></tr>\n";
		}
		$code .= "</table>\n";
		return $code;
	}

	/**
	 *	Runs through the Tree in any Directions and returns the Tree as Array.
	 *	@access		public
	 *	@param		string		$dir		Direction to run through the Tree (lwr|rwl|wlr|wrl)
	 *	@return		array
	 */
	public function toArray( $dir = false )
	{
		if( !$dir || $dir = "lwr" )
		{
			if( $this->left )
				$array = array_merge( $array, $this->left->toArray( $dir ) );
			$array = array_merge( $array, array( $this->value ) );
			if( $this->right )
				$array = array_merge( $array, $this->right->toArray( $dir ) );
		}
		else if( $dir = "rwl" )
		{
			if( $this->right )
				$array = array_merge( $array, $this->right->toArray( $dir ) );
			$array = array_merge( $array, array ($this->value));
			if( $this->left)
				$array = array_merge( $array, $this->left->toArray( $dir ) );
		}
		else if( $dir = "wlr" )
		{
			$array = array_merge( $array, array ($this->value));
			if( $this->left )
				$array = array_merge( $array, $this->left->toArray( $dir ) );
			if( $this->right )
				$array = array_merge( $array, $this->right->toArray( $dir ) );
		}
		else if( $dir = "wrl" )
		{
			$array = array_merge( $array, array ($this->value));
			if( $this->right )
				$array = array_merge( $array, $this->right->toArray( $dir ) );
			if( $this->left )
				$array = array_merge( $array, $this->left->toArray( $dir ) );
		}
		return $array;
	}
}
?>