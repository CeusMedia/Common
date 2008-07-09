<?php
/**
 *	Filters HTML Content by stripping out unwanted Content Types like Scripts or Styles.
 *	@package		alg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2007
 *	@version		0.6
 */
class Alg_InputFilter
{
	/**
	 *	Strips all Comments from String.
	 *	@access		public
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripComments( $string )
	{
		$string	= preg_replace( "@<![\s\S]*?--[ \t\n\r]*>@", "", $string );
		$string	= preg_replace( "@/\*.+\*/@siU", "", $string );
		return $string;
	}

	/**
	 *	Strips all JavaScripts from HTML String.
	 *	@access		public
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripScripts( $string )
	{
		$string	= preg_replace( "@<script[^>]*?>.*?</script>@si", "", $string );
		return $string;
	}

	/**
	 *	Strips all Styles and Stylesheet Links from HTML String.
	 *	@access		public
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripStyles( $string )
	{
		$string	= preg_replace( "@<style[^>]*?>.*?</style>@siU", "", $string );
		$string	= preg_replace( "@<link .*(('|\")\s*stylesheet\s*('|\")|\.css).+(/>|</link>)@siU", "", $string );
		return $string;
	}
	
	/**
	 *	Strips all Tags (<...>) from String.
	 *	@access		public
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripTags( $string )
	{
		$string	= preg_replace( "@<[\/\!]*?[^<>]*?>@si", "", $string );
		return $string;
	}
	
	/**
	 *	Strips all JavaScript Event Attributes from HTML String.
	 *	@access		public
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripEventAttributes( $string )
	{
		$string	= preg_replace( '@(<[^>]+)\s+on[a-z]{4,}\s*=".+"@iU', "\\1", $string );
		$string	= preg_replace( "@(<[^>]+)\s+on[a-z]{4,}\s*='.+'@iU", "\\1", $string );
		return $string;
	}
}
?>