<?php
/**
 *	Balanced Binary Tree.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Balanced Binary Tree.
 *	@category		cmClasses
 *	@package		ADT.Tree
 *	@extends		ADT_Tree_BinaryNode
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class ADT_Tree_BalanceBinaryNode extends ADT_Tree_BinaryNode
{
	/**	@var		int			balance		Balance Tolerance */
	protected $balance;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			balance		Balance Tolerance
	 *	@param		mixed		value		Value of Node
	 *	@return		void
	 */
	public function __construct( $balance, $value = FALSE )
	{
		$this->balance	= $balance;
		parent::__construct( $value );
	}

	/**
	 *	Adds a new Node To Tree.
	 *	@access		public
	 *	@param		mixed		value		Value of new Node
	 *	@return		int
	 */
	public function add( $value )
	{
		if( !isset( $this->value ) )
			return $this->value = $value;
		if( $value == $this->value )
			return -1;
		if( $value < $this->value )
		{
			if( $this->left )
				$this->left->add( $value );
			else
				$this->left = new ADT_Tree_BalanceBinaryNode( $this->balance, $value );
		}
		else if( $value > $this->value )
		{
			if( $this->right )
				$this->right->add( $value );
			else
				$this->right = new ADT_Tree_BalanceBinaryNode( $this->balance, $value );
		}
		if ($this->balance)
		{
			$bf = $this->getBalance();
			if( $bf <= -1 * $this->balance || $bf >= $this->balance )
				$this->balanceTree();
		}
	}

	/**
	 *	Returns  current Balance.
	 *	@access		public
	 *	@param		mixed		value		Value of new Node
	 *	@return		int
	 */
	public function getBalance()
	{
		$la = $this->left  ? $this->left->getHeight()  : 0;
		$lb = $this->right ? $this->right->getHeight() : 0;
		return ( $la - $lb );
	}

	/**
	 *	Balances unbalanced Tree with Rotations.
	 *	@access		public
	 *	@return		void
	 */
	protected function balanceTree()
	{
		$bf	= $this->getBalance();
		if( $bf >= $this->balance ) 												// LR or LL rotation
		{
			$ll_height	= $this->left->left  ? $this->left->left->getHeight()  : 0;
			$lr_height	= $this->left->right ? $this->left->right->getHeight() : 0;
			if( $ll_height < $lr_height )
				$this->left->rotateRR(); 											// LR rotation
			$this->rotateLL();
		}
		else if( $bf <= $this->balance )											// RR or RL rotation
		{
			$rr_height	= $this->right->right ? $this->right->right->getHeight() : 0;
			$rl_height	= $this->right->left  ? $this->right->left->getHeight()  : 0;
			if( $rl_height > $rr_height )
				$this->right->rotateLL();											// RR rotation
			$this->rotateRR();
		}
	}

	/**
	 *	Rotates Tree.
	 *	@access		private
	 *	@return		void
	 */
	private function rotateRR()
	{
		$value_before		=& $this->value;
		$left_before		=& $this->left;
		$this->value		=& $this->right->value;
		$this->left			=& $this->right;
		$this->right		=& $this->right->right;
		$this->left->right	=& $this->left->left;
		$this->left->left	=& $left_before;
		$this->left->value	=& $value_before;
	}

	/**
	 *	Rotates Tree.
	 *	@access		private
	 *	@return		void
	 */
	private function rotateLL()
	{
		$value_before		=& $this->value;
		$right_before		=& $this->right;
		$this->value		=& $this->left->value;
		$this->right		=& $this->left;
		$this->left			=& $this->left->left;
		$this->right->left	=& $this->right->right;
		$this->right->right	=& $right_before;
		$this->right->value	=& $value_before;
	}

	/**
	 *	Returns Tree as HTML Table.
	 *	@access		public
	 *	@param		bool		[showBalanceFactor]		Flag: show Balance Factor
	 *	@return		void
	 */
	public function toTable( $showBalanceFactor = FALSE )
	{
		$la		= $this->left  ? $this->left->getHeight()  : 0;
		$lb		= $this->right ? $this->right->getHeight() : 0;
		$depth	= $this->getHeight ();
		$color	= 240 - ( 3 * $depth );
		if( $showBalanceFactor )
		{
			$k = $la - $lb;
			if( $k <= -1*$this->balance || $k >= $this->balance )
				$k = "<b style='color:red'>$k</b>";
			$ins_bf = "<b class='small' style='font-weight:normal; font-size:7pt;'>".$k."</b>";
		}
		$code = "<table cellspacing='1' cellpadding='0' border='0'>\n<tr><td colspan='2' align='center' style='background:rgb($color, $color, $color); font-size: 7pt'>".$this->value.$ins_bf."</td></tr>";
		if( $this->left || $this->right )
		{
			$code .= "<tr><td align=center valign=top>";
			if( $this->left )
				$code .= $this->left->toTable( $showBalanceFactor);
			else
				$code .= "&nbsp;";
			$code .= "</td><td align=center valign=top>";
			if( $this->right )
				$code .= $this->right->toTable( $showBalanceFactor );
			else
				$code .= "&nbsp;";
			$code .= "</td></tr>\n";
		}
		$code .= "</table>\n";
		return $code;
	}
}
?>