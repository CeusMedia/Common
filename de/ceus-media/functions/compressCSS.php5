<?php
/**
 *	Returns compress CSS.
 *	@access		public
 *	@param		string	$css		CSS String
 *	@return		string
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		06.01.2007
 *	@version	0.1
 */
function compressCSS( $css )
{
	// remove comments
	$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
	// remove tabs, spaces, newlines, etc.
	$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
	$css = preg_replace( '@( +):@', ':', $css );
	$css = preg_replace( '@:( +)@', ':', $css );
	$css = preg_replace( '@( +){@', '{', $css );
	return $css;
}
?>