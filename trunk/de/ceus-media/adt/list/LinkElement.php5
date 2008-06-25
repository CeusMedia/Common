<?php
import ("de.ceus-media.adt.list.ListElement");
/**
 *	Element with Link to another Element.
 *	@package		adt.list
 *	@extends		ListElement
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.5
 */
/**
 *	Element with Link to another Element.
 *	@package		adt.list
 *	@extends		ListElement
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.5
 */
class LinkElement extends ListElement
{
	/**	@var	LinkElement		$link			Pointer to another Element */
	protected $link	= null;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$content		Primitive data type  or Object	to hold
	 *	@return		void
	 */
	public function __construct( $content, $linkedElement = null )
	{
		parent::__construct( $content );
		if( $linkedElement )
			$this->setLink( $linkedElement );
	}
	
	/**
	 *	Indicated wheter this Element links to another Element.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasLink()
	{
		return $this->link !== null;
	}
	
	/**
	 *	Returns the linked Element.
	 *	@access		public
	 *	@return		LinkElement
	 */
	public function getLink()
	{
		return $this->link;
	}
	
	/**
	 *	Sets a link to another Link Element.
	 *	@access		public
	 *	@param		LinkElement	$element		Next Element to be linked to
	 */
	public function setLink( $element )
	{
		$this->link = $element;	
	}
}
?>