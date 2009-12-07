<?php
/**
 *	HTML Reset Button.
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
 *	HTML Reset Button.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Button_Reset extends UI_HTML_Element_Abstract
{
	public static $defaultClass	= 'reset';
	protected $url			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		Target URL of linked Button
	 *	@param		mixed		$label		Label String or HTML Object
	 *	@return		void
	 */
	public function __construct( $label = NULL )
	{
		if( !is_null( $label ) )
			$this->setContent( $label );
		$this->setClass( self::$defaultClass );
	}

	public function render()
	{
		$attributes	= array(
			'type'	=> 'reset',
			'class'	=> $this->class,
		);
		$span	= UI_HTML_Tag::create( 'span', $this->renderContent() );
		return $this->renderTag( 'button', $span, $attributes );
	}
}
?>