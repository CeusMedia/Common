<?php
class UI_HTML_JQuery
{
	public static function buildOptions( $options, $spaces = 2 )
	{
		$innerIndent	= "";
		$outerIndent	= "";
		if( $spaces > 1 )
		{
			$innerIndent	= str_repeat( " ", $spaces );
			$outerIndent	= str_repeat( " ", $spaces - 2 );
		}

		if( $options )
		{
			$list	= array();
			foreach( $options as $key => $value )
				$list[]	= $key.": ".$value;
			$options	= implode( ",\n    ", $list );
			$options	= "{\n".$innerIndent.$options."\n".$outerIndent."}";
		}
		else
			$options	= "";
		return $options;
	}

	public static function buildPluginCall( $plugin, $selector, $options )
	{
		$options	= self::buildOptions( $options, 4 );
		return '$(document).ready(function(){
  $("'.$selector.'").'.$plugin.'('.$options.');
});';
	}
}
?>