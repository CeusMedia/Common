<?php
/**
 *	Compresses CSS Files..
 *	@package	file.css
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		26.09.2007
 *	@version	0.1
 */
/**
 *	Compresses CSS Files..
 *	@package	file.css
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		26.09.2007
 *	@version	0.1
 */
class File_CSS_Compressor
{
	/**	@var		string			$prefix			Prefix of compressed File Name */
	var $prefix		= "";
	/**	@var		array			$statistics		Statistical Data */
	var $statistics	= array();
	/**	@var		string			$suffix			Suffix of compressed File Name */
	var $suffix		= ".min";
	
	/**
	 *	Returns statistical Data of last Combination.
	 *	@access		public
	 *	@return		array	
	 */
	public function getStatistics()
	{
		return $this->statistics;
	}
	
	/**
	 *	Compresses a CSS String.
	 *	@access		public
	 *	@param		string		$content		Content of CSS
	 *	@return		string
	 */
	public function compressString( $content )
	{
		// remove comments
		$content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content );
		// remove tabs, spaces, newlines, etc.
		$content = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $content );
		$content = preg_replace( '@( +):@', ':', $content );
		$content = preg_replace( '@:( +)@', ':', $content );
		$content = preg_replace( '@( +){@', '{', $content );
		return $content;
	}
	
	/**
	 *	Reads and compresses a CSS File and returns Length of compressed File.
	 *	@access		public
	 *	@param		string		$fileUri		Full URI of CSS File
	 *	@return		string
	 */
	public function compressFile( $fileUri )
	{
		if( !file_exists( $fileUri ) )
			throw new Exception( "Style File '".$fileUri."' is not existing." );

		$this->statistics	= array();

		$content	= file_get_contents( $fileUri );
		$this->statistics['before']	= strlen( $content );
		$content	= $this->compressString( $content );
		$this->statistics['after']	= strlen( $content );
		
		$pathName	= dirname( $fileUri );
		$styleFile	= basename( $fileUri );
		$styleName	= preg_replace( "@\.css$@", "", $styleFile );
		$fileName	= $this->prefix.$styleName.$this->suffix.".css";
		$fileUri	= $pathName."/".$fileName;
		
		file_put_contents( $fileUri, $content );
		return $fileUri;
	}

	/**
	 *	Sets Prefix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of compressed File Name
	 *	@return		void
	 */
	public function setPrefix( $prefix )
	{
		if( trim( $prefix ) )
			$this->prefix	= $prefix;
	}	
	
	/**
	 *	Sets Suffix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of compressed File Name
	 *	@return		void
	 */
	public function setSuffix( $suffix )
	{
		if( trim( $suffix ) )
			$this->suffix	= $suffix;
	}	
}
?>