<?php
/**
 *	HTML Submit Button.
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
 *	HTML Submit Button.
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
class UI_HTML_Element_Button_Submit extends UI_HTML_Element_Abstract
{
	protected static $defaultClass	= 'positive submit';
	protected $name					= NULL;
	protected $value				= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name send by Form
	 *	@param		mixed		$label		Label String or HTML Object
	 *	@return		void
	 */
	public function __construct( $name = NULL, $label = NULL )
	{
		if( !is_null( $name ) )
			$this->setName( $name );
		if( !is_null( $label ) )
			$this->setContent( $label );
		$this->addClass( self::$defaultClass );
	}

	public function render()
	{
		$attributes	= array(
			'type'		=> 'submit',
			'id'		=> $this->id,
			'name'		=> $this->name,
			'value'		=> $this->value,
			'class'		=> $this->class,
		);
		$span	= UI_HTML_Tag::create( 'span', $this->renderContent() );
		return $this->renderTag( 'button', $span, $attributes );
	}
	
	public function setName( $name )
	{
		$this->name	= $name;
	}
	
	public function setValue( $value )
	{
		$this->value	= $value;
	}
}
?>