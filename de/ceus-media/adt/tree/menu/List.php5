<?php
/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *	@package		adt.tree.menu
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 */
/**
 *	Tree Menu List Data Object used by UI_HTML_Tree_Menu.
 *	@package		adt.tree.menu
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 */
class ADT_Tree_Menu_List
{
	/**	@var	array		$children		List of nested Tree Menu Items */
	protected $children		= array();

	/**
	 *	Adds a nested Tree Menu Item to this Tree Menu List.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_Item	$child		Nested Tree Menu Item Data Object
	 *	@return		void
	 */
	public function addChild( ADT_Tree_Menu_Item $child )
	{
		$this->children[]	= $child;
	}

	/**
	 *	Indicated whether there are nested Tree Menu Items.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren()
	{
		return (bool) count( $this->children );
	}
	
	/**
	 *	Returns List of nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 *	Returns recursive Array Structure of this List and its nested Tree Menu Items.
	 *	@access		public
	 *	@param		bool		$wrapped		Wrap Array Structure (deprecated)
	 *	@return		array
	 *	@todo		remove param 'wrapped'
	 *	@todo		remove timer
	 */
	public function toArray( $wrapped = FALSE )
	{
		$st	= new StopWatch;
		$children	= array();
		foreach( $this->children as $child )
			$children[]	= $child->toArray();
		if( $wrapped )
			$children	= array(
				'children'	=> $children
			);
		if( $wrapped )
			remark( $st->stop( 6 )."&micro;s" );
		return $children;
	}
}
?>