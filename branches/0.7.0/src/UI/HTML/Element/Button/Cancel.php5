<?php
/**
 *	HTML Cancel Link Button.
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
 *	HTML Cancel Link Button.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Button_Link
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Button_Cancel extends UI_HTML_Element_Button_Link
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		Target URL of linked Button
	 *	@param		mixed		$label		Label String or HTML Object
	 *	@return		void
	 */
	public function __construct( $url = NULL, $label = NULL )
	{
		if( !is_null( $url ) )
			$this->setUrl( $url );
		if( !is_null( $label ) )
			$this->setContent( $label );
		$this->addClass( 'negative cancel' );
	}
}
?>