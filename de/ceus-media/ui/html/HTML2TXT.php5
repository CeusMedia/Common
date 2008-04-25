<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Transformes HTML to Plain Text by removing Tags, Scripts, Styles, Spaces and special Characters.
 *	@package		ui
 *	@subpackage		html
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.10.2006
 *	@version		0.1
 */
/**
 *	Transformes HTML to Plain Text by removing Tags, Scripts, Styles, Spaces and special Characters.
 *	@package		ui
 *	@subpackage		html
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.10.2006
 *	@version		0.1
 */
class HTML2TXT
{
	/**	@var		bool			$_loaded		Flag: HTML is loaded an can be transformed to Text */
	var $_loaded	= false;
	/**	@var		array		$_needle		Array width Needles for Replacement */
	var $_needle = array();
	/**	@var		array		$_subst		Array width Substitutions for Replacement */
	var $_subst = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_loaded	= false;
		$this->_needle	= array(
			'@<script[^>]*?>.*?</script>@si',	// JavaScript entfernen
			'@<style[^>]*?>.*?</style>@si',	// CSS entfernen
			'@<[\/\!]*?[^<>]*?>@si',			// HTML-Tags entfernen
			'@([\r\n])[\s]+@',					// Leerräume entfernen
			'@&(quot|#34);@i',				// HTML-Entitäten ersetzen
			'@&(amp|#38);@i',
			'@&(lt|#60);@i',
			'@&(gt|#62);@i',
			'@&(nbsp|#160);@i',
			'@&(iexcl|#161);@i',
			'@&(cent|#162);@i',
			'@&(pound|#163);@i',
			'@&(copy|#169);@i',
			'@&#(\d+);@e'					// als PHP auswerten
			);
		$this->_subst	= array(
			'',
			'',
			'',
			'\1',
			'"',
			'&',
			'<',
			'>',
			' ',
			chr( 161 ),
			chr( 162 ),
			chr( 163 ),
			chr( 169 ),
			'chr(\1)'
			);
	}
	
	/**
	 *	Loads HTML from HTML File.
	 *	@access		public
	 *	@param		string		$filename		File Name of HTML File
	 *	@return		bool
	 */
	function loadFile( $filename )
	{
		if( file_exists( $filename ) )
		{
			$html_file		= new File_Reader( $filename );
			$html		= $html_file->readString();
			$this->setHTML( $html );
			return true;
		}
		return false;
	}
	
	/**
	 *	Sets HTML for Transformation.
	 *	@access		public
	 *	@param		string		$html		HTML to transform
	 *	@return		void
	 */
	function setHTML( $html )
	{
		$this->_html		= $html;
		$this->_loaded	= true;
	}
	
	/**
	 *	Returns transformed Text.
	 *	@access		public
	 *	@return		string
	 */
	function getText()
	{
		if( !$this->_loaded )
			trigger_error( 'No HTML loaded', E_USER_ERROR );
		$text	= preg_replace( $this->_needle, $this->_subst, $this->_html );
		return $text;
	}
	
}
?>