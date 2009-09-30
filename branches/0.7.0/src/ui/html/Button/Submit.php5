<?php
class UI_HTML_Button_Submit extends UI_HTML_Button_Abstract
{
	protected $name				= NULL;
	public static $classes		= "positive submit";
	public static $requiresName	= FALSE;

	public function __construct( $label = NULL, $attributes = NULL )
	{
		if( !is_null( $name ) )
			$this->setName( $name );
		if( !is_null( $label ) )
			$this->setContent( $label );
		if( self::$classes )
			$this->setClasses( self::$classes );
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
	}

	public function render()
	{
		$this->validateSetting();
		$attributes	= $this->getAttributes();
		$attributes['type']	= 'submit';
		return UI_HTML_Tag::create( "button", $this->content, $attributes );
	}

	protected function validateSetting()
	{
		parent::validateSetting();
		if( self::$requiresName )
			if( empty( $this->name ) )
				throw RuntimeException( 'Button has no name' );
	}	
}
?>