<?php
/**
 *	
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Fieldset extends UI_HTML_Element_Abstract
{
	protected $legend	= NULL;
	protected $content	= array();

	public function addContent( $content )
	{
		$this->content[]	= $content;
		return $this;
	}

	public function render()
	{
		$legend	= $this->legend;
		if( $legend instanceof UI_HTML_Element_Abstract )
			$legend	= $legend->render();
		if( $legend )
			$legend	= '<legend>'.$legend.'</legend>';

		$list	= array();
		foreach( $this->content as $content )
		{
			if( $content instanceof UI_HTML_Element_Abstract )
				$content	= $content->render();
			$list[]	= $content;
		}
		$content	= implode( '<br/>', $list );
		$attributes	= $this->renderAttributes();
		return '<fieldset'.$attributes.'>'.$legend.$content.'</fieldset>';
	}
	
	public function setLegendLabel( $label )
	{
		$this->legend	= $label;
		return $this;
	}
}
?>