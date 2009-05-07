<?php
/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *	@package		adt.tree.menu
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 */
import( 'de.ceus-media.adt.tree.menu.List' );
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Tree Menu List Item Data Object used by UI_HTML_Tree_Menu.
 *	@package		adt.tree.menu
 *	@extends		ADT_Tree_Menu_List
 *	@uses			ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.11.2008
 *	@version		0.1
 */
class ADT_Tree_Menu_Item extends ADT_Tree_Menu_List
{
	/**	@var		string		$url			URL of Item Link */
	public $url			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL of Item Link
	 *	@param		string		$label			Label of Item Link
	 *	@param		array		$attributes		Array of Item Attributes (classItem,classLink,classList)
	 *	@return		void
	 */
	public function __construct( $url, $label, $attributes = array() )
	{
		parent::__construct( $label, $attributes );
		$this->url			= $url;
	}

	/**
	 *	Returns Attribute Value by a Key if set or NULL.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@return		mixed
	 */
	public function __get( $key )
	{
		return $this->attributes->get( $key );
	}
	
	/**
	 *	Returns URL of Tree Menu List Item.
	 *	@access		public
	 *	@return		array
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 *	Returns recursive Array Structure of this Item and its nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array	= array(
			'url'		=> $this->url,
			'label'		=> $this->label,
			'classLink'	=> $this->attributes->get( 'classLink' ),
			'classItem'	=> $this->attributes->get( 'classItem' ),
			'classList'	=> $this->attributes->get( 'classList' ),
			'children'	=> 	parent::toArray()
		);
		return $array;
	}
}
?>