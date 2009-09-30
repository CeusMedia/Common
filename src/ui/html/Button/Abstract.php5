<?php
abstract class UI_HTML_Button_Abstract extends UI_HTML_Abstract
{
	public function setLabel( $label )
	{
		$this->setContent( $label );
	}
	
	protected function validateSetting()
	{
		if( empty( $this->content ) )
			throw new RuntimeException( 'Button has no label' );
	}
}
?>