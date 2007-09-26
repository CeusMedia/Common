<?php
import( 'de.ceus-media.adt.LinkElement' );
/**
 *	Element for Chain List.
 *	@package		adt
 *	@extends		LinkElement
 *	@uses			Link
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.4
 */
/**
 *	Element for Chain List.
 *	@package		adt
 *	@extends		LinkElement
 *	@uses			Link
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.4
 */
class ChainElement extends LinkElement
{
	/**	@var		Link		$_origin		Link to the previous Element */
	var $_origin;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $content )
	{
		parent::__construct( $content );
		$this->_origin = new Link();
	}
	
	/**
	 *	Indicates wheter this Element has a previous Element.
	 *	@access		public
	 *	@return		bool
	 */
	function hasOrigin()
	{
		if( $this->_origin->hasLink() )
			return true;
		return false;
	}
	
	/**
	 *	Returns the previous Element of this Element.
	 *	@access		public
	 *	@return		mixed
	 */
	function & getOrigin()
	{
		return $this->_origin->getLink();
	}
	
	/**
	 *	Sets the previous Element of this Element.
	 *	@access		public			
	 *	@param		ChainElement	$element		Previous Element to be linked to
	 *	@return		void
	 */
	function setOrigin( &$element )
	{
		$this->_origin->setLink( $element );	
	}
}
?>