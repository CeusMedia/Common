<?php
/**
 *	HTML Textarea Tag with optional Label.
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
 *	HTML Textarea Tag with optional Label.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Input_Textarea extends UI_HTML_Element_Abstract
{
	protected $name			= '';
	protected $value		= '';
	protected $maxLength	= NULL;
	protected $labelText	= NULL;
	protected $labelClass	= NULL;
	protected $labelAlign	= 0;

	public function __construct( $name, $value = NULL )
	{
		if( !is_string( $name ) )
			throw new InvalidArgumentException( 'Name has to be string' );
		$this->setName( $name );
		$this->setValue( $value );
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> $this->class,
			'name'		=> $this->name,
			'maxlength'	=> $this->maxLength,
		);
		$input	= $this->renderTag( 'textarea', $this->value, $attributes );
		if( $this->labelText )
		{
			$label	= new UI_HTML_Element_Input_Label( $this->labelText, $this->id );
			if( !is_null( $this->labelClass ) )
				$label->setClass( $this->labelClass );
			$label	= $label->render();
			$input	= $this->labelAlign ? $input.$label : $label.$input;
		}
		return $input;
	}

	public function setLabelAlign( $align )
	{
		$this->labelAlign	= $align;
	}

	public function setLabelClass( $class )
	{
		$this->labelClass	= $class;
	}

	public function setLabelText( $text )
	{
		if( $text instanceof UI_HTML_Element_Abstract )
			$text	= $text->render();
		$this->labelText	= $text;
	}

	public function setMaxLength( $maxLength )
	{
		if( !is_int( $maxLength) )
			throw new InvalidArgumentException( 'Has to be integer' );
		$this->maxLength = $maxLength;
	}

	public function setName( $name )
	{
		$this->name	= $name;
	}

	public function setValue( $value )
	{
		$this->value	= addslashes( $value );
	}
}
?>