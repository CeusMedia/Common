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
	/**	@var		array		$attributes		Array of Item Attributes (classItem,classLink,classList) */
	protected $attributes	= NULL;
	/**	@var		string		$label			Label of Item Link */
	public $label		= NULL;
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
		$this->url			= $url;
		$this->label		= $label;
		$this->attributes	= new ADT_List_Dictionary( $attributes );
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
	 *	Returns Value of a set Attribute by its Key.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@return		string
	 */
	public function getAttribute( $key )
	{
		return $this->attributes->get( $key );
	}
	
	/**
	 *	Returns all set Attributes as Dictionary or Array.
	 *	@access		public
	 *	@param		bool		$asArray		Return Array instead of Dictionary
	 *	@return		mixed
	 */
	public function getAttributes( $asArray = FALSE )
	{
		if( $asArray )
			return $this->attributes->toArray();
		return $this->attributes;
	}

	/**
	 *	Sets an Attribute.
	 *	@access		public
	 *	@param		string		$key			Attribute Key
	 *	@param		string		$value			Attribute Value
	 *	@return		string
	 */
	public function setAttribute( $key, $value )
	{
		$this->attributes->set( $key, $value );
	}

	/**
	 *	Sets Attributes from Map Array or Dictionary.
	 *	@access		public
	 *	@param		mixed		$array			Map Array or Dictionary of Attributes to set
	 *	@return		void
	 */
	public function setAttributes( $array )
	{
		if( is_a( $array, 'ADT_List_Dictionary' ) )
			$array	= $array->getAll();
		foreach( $array as $key => $value )
			$this->attributes->set( $key, $value );
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