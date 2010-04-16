<?php
/**
 *	HTML Text Input Tag with optional Label.
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
 *	HTML Text Input Tag with optional Label.
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
class UI_HTML_Element_Input_Text extends UI_HTML_Element_Abstract
{
	protected $name			= '';
	protected $value		= '';
	protected $maxLength	= NULL;
	protected $labelText	= NULL;
	protected $labelClass	= NULL;
	protected $labelAlign	= 0;
	protected $type			= 'text';
	protected $checked		= NULL;
	protected $readonly		= NULL;
	protected $disabled		= NULL;

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
			'type'		=> $this->type,
			'name'		=> $this->name,
			'value'		=> $this->value,
			'maxlength'	=> $this->maxLength,
			'checked'	=> $this->checked ? 'checked' : NULL,
			'disabled'	=> $this->disabled ? 'disabled' : NULL,
			'readonly'	=> $this->readonly ? 'readonly' : NULL,
		);
		$input	= $this->renderTag( 'input', NULL, $attributes );
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

	public function setDisabled( $state = TRUE )
	{
		$this->disabled	= (bool) $state;
		return $this;
	}

	public function setLabelAlign( $align )
	{
		$this->labelAlign	= $align;
		return $this;
	}

	public function setLabelClass( $class )
	{
		$this->labelClass	= $class;
		return $this;
	}

	public function setLabelText( $text )
	{
		if( $text instanceof UI_HTML_Element_Abstract )
			$text	= $text->render();
		$this->labelText	= $text;
		return $this;
	}

	public function setMaxLength( $maxLength )
	{
		if( !is_int( $maxLength) )
			throw new InvalidArgumentException( 'Has to be integer' );
		$this->maxLength = $maxLength;
		return $this;
	}

	public function setName( $name )
	{
		$this->name	= $name;
		return $this;
	}

	public function setReadonly( $state = TRUE )
	{
		$this->readonly	= (bool) $state;
		return $this;
	}

	public function setValue( $value )
	{
		$this->value	= addslashes( $value );
		return $this;
	}
}
?>