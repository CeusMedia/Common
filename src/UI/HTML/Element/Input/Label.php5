<?php
/**
 *	HTML Label Tag.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Label Tag.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Input_Label extends UI_HTML_Element_Abstract
{
	private $for		= NULL;

	public function __construct( $content = NULL, $relatedId = NULL )
	{
		if( !is_null( $content ) )
			$this->addContent( $content );
		if( !is_null( $relatedId ) )
			$this->setFor( $relatedId );
	}

	public function setFor( $relatedId )
	{
		if( !is_string( $relatedId ) )
			throw new InvalidArgumentException( 'Has to be string' );
		$this->for	= $relatedId;
		return $this;
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> $this->class,
			'for'		=> $this->for,
		);
		return $this->renderTag( 'label', $this->content, $attributes );
	}
}
?>