<?php
class UI_HTML_AHAH_Link
{
	public static function render( $url, $label, $targetId, $class = NULL )
	{
		$attributes	= array(
			'href'		=> "#".$targetId,
			'class'		=> $class,
			'onclick'	=> "ahah('".$url."','".$targetId."');",
		);
		return UI_HTML_Tag::create( 'a', $label, $attributes );
	}
}
?>