<?php
/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.06.2008
 *	@version		0.1
 */
/**
 *	Builder for jQuery Plugin Calls for HTML Documents.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.06.2008
 *	@version		0.1
 */
class UI_HTML_JQuery
{
	/**
	 *	Builds and returns Plugin Constructor Options.
	 *	@access		protected
	 *	@param		array		$options		Array of Plugin Constructor Options
	 *	@param		int			$spaces			Number of indenting Whitespaces
	 *	@return		string
	 */
	protected static function buildOptions( $options, $spaces = 2 )
	{
		$innerIndent	= "";
		$outerIndent	= "";
		if( $spaces > 1 )
		{
			$innerIndent	= str_repeat( " ", $spaces + 2 );
			$outerIndent	= str_repeat( " ", $spaces );
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

	/**
	 *	Builds and returns JavaScript Code of jQuery Plugin Call.
	 *	@access		public
	 *	@param		string		$plugin			Name of Plugin Constructor Methode
	 *	@param		string		$selector		XPath Selector of HTML Tag(s) to call Plugin on
	 *	@param		array		$option			Array of Plugin Constructor Options
	 *	@param		int			$spaces			Number of indenting Whitespaces
	 *	@return		string
	 */
	public static function buildPluginCall( $plugin, $selector, $options, $spaces = 0 )
	{
		$innerIndent	= str_repeat( " ", $spaces + 2 );
		$outerIndent	= str_repeat( " ", $spaces );
		$options	= self::buildOptions( $options, $spaces + 2 );
		return $outerIndent.'$(document).ready(function(){
'.$innerIndent.'$("'.$selector.'").'.$plugin.'('.$options.');
'.$outerIndent.'});';
	}
}
?>