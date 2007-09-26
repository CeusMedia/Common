<?php
/**
 *	Element for Lists.
 *	@package		adt.list
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.5
 */
/**
 *	Element for Lists.
 *	@package		adt.list
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.5
 */
class ListElement
{
	/**	@var	mixed	$content		Primitive Data Type or Object to hold */
	protected $content;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$content		Content of Element
	 *	@return		void
	 */
	public function __construct( $content )
	{
		$this->setContent( $content );
	}
	
	/**
	 *	Returns the Content of this Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getContent ()
	{
		return $this->content;
	}
	
	/**
	 *	Sets the Content of this Element.
	 *	@access		public
	 *	@param		mixed		Primitive Data Type or Object to hold
	 *	@return		void
	 */
	function setContent( $content )
	{
		$this->content = $content;
	}
}
?>