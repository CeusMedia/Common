<?php
/**
 *	HTML Select Tag with optional Label.
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
 *	HTML Select Tag with optional Label.
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
class UI_HTML_Element_Input_Select extends UI_HTML_Element_Abstract
{
	protected $name			= '';
	protected $options		= array();
	protected $labelText	= NULL;
	protected $labelClass	= NULL;
	protected $labelAlign	= 0;
	protected $multiple		= NULL;

	public function __construct( $name = NULL )
	{
		if( !is_string( $name ) )
			throw new InvalidArgumentException( 'Name has to be string' );
		$this->setName( $name );
	}

	protected function compareValue( $value )
	{
		if( is_array( $this->value ) )
			return in_array( $value, $this->value );
		else
			return $this->value === $value;
	}

	public function addOption( $value, $label, $selected = FALSE )
	{
		$selected	= $selected || $this->compareValue( $value );
		$attributes	= array(
			'value'		=> $value,
			'selected'	=> $selected
		);
		$this->options[]	= $this->renderTag( 'option', $label, $attributes );
		return $this;
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'class'		=> $this->class,
			'name'		=> $this->name,
			'multiple'	=> $this->multiple,
		);
		foreach( $this->events as $event => $action )
			$attributes[$event]	= $action;

		$options	= implode( $this->options );
		$select		= $this->renderTag( 'select', $options, $attributes );
		if( $this->labelText )
		{
			$label		= new UI_HTML_Element_Input_Label( $this->labelText, $this->id );
			if( !is_null( $this->labelClass ) )
				$label->setClass( $this->labelClass );
			$label		= $label->render();
			$select	= $this->labelAlign ? $select.$label : $label.$select;
		}
		return $select;
	}

	public function setCurrentValue( $value )
	{
		$this->value	= $value;
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

	public function setMultiple( $state = TRUE )
	{
		$this->multiple	= (bool) $state;
		if( (bool) $state && !preg_match( '/\[\]$/', $this->name ) )
			$this->name	.= '[]';
		else if( !(bool) $state )
			$this->name	= preg_replace( '/\[\]$/', '', $this->name );
		return $this;
	}

	public function setName( $name )
	{
		$this->name	= $name;
		return $this;
	}
}
?>