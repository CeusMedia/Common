<?php
/**
 *	HTML Image Tag.
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
 *	HTML Image Tag.
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
class UI_HTML_Element_Image extends UI_HTML_Element_Abstract
{
	protected $url			= NULL;
	protected $label		= NULL;
	protected $width		= NULL;
	protected $height		= NULL;
	protected $alternative	= NULL;

	public function __construct( $url = NULL, $label = NULL )
	{
		if( !is_null( $url ) )
			$this->setUrl( $url );
		if( !is_null( $label ) )
			$this->setLabel( $label );
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'src'		=> $this->url,
			'title'		=> $this->label,
			'alt'		=> $this->label,
			'width'		=> $this->width,
			'height'	=> $this->height,
		);
		return $this->renderTag( 'img', NULL, $attributes );
	}
	
	public function setWidth( $width )
	{
		$this->width	= $width;
	}
	
	public function setHeight( $height)
	{
		$this->height	= $height;
	}
	
	public function setUrl( $url )
	{
		$this->url	= $url;
	}
	
	public function setLabel( $label )
	{
		if( $label instanceof UI_HTML_Element_Abstract )
			$label	= $label->render();
		$this->label	= $label;
	}
}
?>