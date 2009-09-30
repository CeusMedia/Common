<?php
class UI_HTML_Button_Cancel extends UI_HTML_Button_Link
{
	public function __construct( $url = NULL, $label = NULL, $attributes = NULL )
	{
		parent::setClasses( "negative cancel" );
		parent::__construct( $url, $label, $attributes );
	}
}
?>