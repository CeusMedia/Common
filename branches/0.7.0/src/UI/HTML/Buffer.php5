<?php
class UI_HTML_Buffer extends UI_HTML_Abstract
{
	protected $list	= array();
	public function __construct()
	{
		foreach( func_get_args() as $content )
		{
			$this->list[]	= $content;
		}
	}

	public function render()
	{
		$buffer	= '';
		foreach( $this->list as $content )
			$buffer	.= $this->renderInner( $content );
		return $buffer;
	}
}
?>