<?php
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id: Div.php5 679 2010-05-18 17:01:11Z christian.wuerker $
 */
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.Element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id: Div.php5 679 2010-05-18 17:01:11Z christian.wuerker $
 */
class UI_HTML_Element_Span extends UI_HTML_Element_Abstract
{
	public function __construct( $content = NULL )
	{
		$this->setContent( $content );
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> $this->class,
		);
		return $this->renderTag( 'span', $this->renderContent(), $attributes );
	}
}
?>