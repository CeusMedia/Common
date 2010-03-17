<?php
class UI_HTML_Input extends UI_HTML_Abstract
{
	public function __construct( $name = NULL, $value = NULL, $attributes = NULL )
	{
		if( !is_null( $name ) )
			$this->setName( $name );
		if( !is_null( $value ) )
			$this->setValue( $value );
		$attributes['type']	= "text";
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );

	}

	public function setName( $name )
	{
		$this->attributes['name']	= $name;
	}

	public function setValue( $value )
	{
		$this->attributes['value']	= $value;
	}

	public function render()
	{
		$attributes	= $this->getAttributes();
		return UI_HTML_Tag::create( "input", NULL, $attributes );
	}
}
?>