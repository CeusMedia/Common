<?php
/**
 *	HTML Link Anchor Tag.
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
 *	HTML Link Anchor Tag.
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
class UI_HTML_Element_Link extends UI_HTML_Element_Abstract
{
	protected $content		= NULL;
	protected $relation		= NULL;
	protected $url			= NULL;

	public function __construct( $url = NULL, $content = NULL )
	{
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $url ) )
			$this->setUrl( $url );
	}

	public function render()
	{
		$attributes	= array(
			'href'	=> $this->url,
			'class'	=> $this->class,
			'rel'	=> $this->relation,
		);
		$content	= $this->renderContent();
		return $this->renderTag( 'a', $content, $attributes );
	}
	
	public function setUrl( $url )
	{
		$this->url	= $url;
		return $this;
	}
	
	public function setContent( $content )
	{
		if( $content instanceof UI_HTML_Element_Abstract )
			$content	= $content->render();
		$this->content	= $content;
		return $this;
	}

	public function setRelation( $relation )
	{
		$this->relation	= $relation;	
		return $this;
	}
}
?>