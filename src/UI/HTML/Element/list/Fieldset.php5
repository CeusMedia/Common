<?php
/**
 *	
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Fieldset
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_List_Fieldset extends UI_HTML_Element_Fieldset
{
	public function render()
	{
		$legend	= $this->legend;
		if( $legend instanceof UI_HTML_Element_Abstract )
			$legend	= $legend->render();
		if( $legend )
			$legend	= '<legend>'.$legend.'</legend>';

		$list	= new UI_HTML_Element_List_Ordered();
		foreach( $this->content as $content )
		{
			if( $content instanceof UI_HTML_Element_Abstract )
				$content	= $content->render();
			$list->add( new UI_HTML_Element_List_Item( $content ) );
		}
		$content	= $list->render();
		$attributes	= $this->renderAttributes();
		return '<fieldset'.$attributes.'>'.$legend.$content.'</fieldset>';
	}
}
?>