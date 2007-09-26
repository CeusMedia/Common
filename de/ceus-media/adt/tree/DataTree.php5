<?php
import ("de.ceus-media.adt.tree.Tree");
/**
 *	Tree containing Data of any kind.
 *	@package		adt.tree
 *	@extends		Tree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Tree containing Data of any kind.
 *	@package		adt.tree
 *	@extends		Tree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class DataTree extends Tree
{
	/**	@var	mixed		$data		Data of this Tree Node */
	protected $data;
	
	/**
	 *	Sets Data of this Tree Node
	 *	@access		public
	 *	@param		mixed		$data		Data of this Tree Node
	 *	@return 	void
	 */
	public function setData ($data)
	{
		$this->data = $data;
	}
	
	/**
	 *	Returns Data of this Tree Node
	 *	@access		public
	 *	@return 	void
	 */
	public function getData ()
	{
		return $this->data;
	}
}
?>