<?php
import ("de.ceus-media.adt.tree.BalanceBinaryNode");
/**
 *	AVL Tree.
 *	@package		adt.tree
 *	@extends		ADT_Tree_BalanceBinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	AVL Tree.
 *	@package		adt.tree
 *	@extends		ADT_Tree_BalanceBinaryNode
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class ADT_Tree_AvlNode extends ADT_Tree_BalanceBinaryNode
{
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		mixed	value	Value of Root Element
	 *	@return		void
	 */
	public function __construct( $value = false )
	{
		parent::__construct( 2, $value );
	}
}
?>