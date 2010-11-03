<?php
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Label extends UI_HTML_Element_Abstract
{
	protected $title	= NULL;

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> $this->class,
			'title'		=> $this->title,
		);
		return $this->renderTag( 'form', $this->renderContent(), $attributes );
	}

	public function setTitle( $title )
	{
		$this->title	= $title;
		return $this;
	}
}
?>
