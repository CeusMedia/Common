<?php
import ("de.ceus-media.adt.tree.BalanceBinaryTree");
/**
 *	AVL Tree.
 *	@package		adt.tree
 *	@extends		BalanceBinaryTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	AVL Tree.
 *	@package		adt.tree
 *	@extends		BalanceBinaryTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class AvlTree extends BalanceBinaryTree
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