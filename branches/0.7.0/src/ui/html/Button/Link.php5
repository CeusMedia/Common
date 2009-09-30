<?php
class UI_HTML_Button_Link extends UI_HTML_Button_Abstract
{
	public function __construct( $url = NULL, $label = NULL, $attributes = NULL )
	{
		if( !is_null( $attributes ) )
			$this->addAttributes( $attributes );
		if( !is_null( $label ) )
			$this->setContent( $label );
		if( !is_null( $url ) )
			$this->setUrl( $url );
	}

	public function render()
	{
		$this->validateSetting();
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['href'] ) )
			$attributes['href']	= ADT_URL_Inference::buildStatic( $attributes['href'] );
		return UI_HTML_Tag::create( 'a', $this->content, $attributes );
	}
	
	public function setUrl( $url )
	{
		$this->attributes['href']	= $url;
	}
	
	protected function validateSetting()
	{
		parent::validateSetting();
		if( empty( $this->attributes['href'] ) )
			throw RuntimeException( 'LinkButton has no url' );
	}
}
?>