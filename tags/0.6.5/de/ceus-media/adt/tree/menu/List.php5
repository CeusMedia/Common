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
	/**	@var		string		$label			Label of Item Link */
	public $label				= NULL;
	/**	@var		array		$attributes		Array of Item Attributes (classItem,classLink,classList) */
	public $attributes			= NULL;
	/**	@var		array		$children		List of nested Tree Menu Items */
	public $children			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$label			Label of Item Link
	 *	@param		array		$attributes		Array of Item Attributes (classItem,classLink,classList)
	 *	@return		void
	 */
	public function __construct( $label = NULL, $attributes = array() )
	{
		$this->label		= $label;
		$this->attributes	= new ADT_List_Dictionary( $attributes );
	}

	/**
	 *	Adds a nested Tree Menu Item to this Tree Menu List.
	 *	@access		public
	 *	@param		ADT_Tree_Menu_Item	$child		Nested Tree Menu Item Data Object
	 *	@return		void
	 */
	public function addChild( ADT_Tree_Menu_List $child )
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
	 *	Returns List of nested Tree Menu Items.
	 *	@access		public
	 *	@return		array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 *	Returns Label of Tree Menu List.
	 *	@access		public
	 *	@return		string
	 */
	public function getLabel()
	{
		return $this->label;
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