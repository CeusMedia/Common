<?php
/**
 *	Combines CSS Files imported in one CSS File.
 *	@package	file.css
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		26.09.2007
 *	@version	0.1
 */
/**
 *	Combines CSS Files imported in one CSS File.
 *	@package	file.css
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		26.09.2007
 *	@version	0.1
 */
class File_Css_Combiner
{
	/**	@var	string			$prefix			Prefix of combined File Name */
	var $prefix			= "";
	/**	@var	string			$suffix			Suffix of combined File Name */
	var $suffix			= ".combined";
	/**	@var	string			$importPattern	Pattern of imported CSS Files */
	var $importPattern	= '#^@import "(.*)";?$#i';
	/**	@var	array			$statistics		Statistical Data */
	var $statistics	= array();
	
	/**
	 *	Combines all CSS Files imported in CSS String and returns Combination String;
	 *	@access		public
	 *	@param		string		$path			Style Path
	 *	@param		string		$styleFile		File Name of Style without Extension (iE. style.css,import.css,default.css)
	 *	@return		string
	 */
	public function combineString( $path, $content )
	{
		$this->statistics['size']		= 0;
		$this->statistics['fileCount']	= 0;
		$this->statistics['fileList']	= array();
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( !$line )
				continue;
			if( !preg_match( $this->importPattern, $line ) )
				continue;
			preg_match_all( $this->importPattern, $line, $matches );
			$fileName	= $matches[1][0];
			$this->statistics['fileList'][]	= $fileName;

			$content	= file_get_contents( $path.$fileName );
			$this->statistics['fileCount']	++;
			$this->statistics['size']		+= strlen( $content );
//			$depth	= substr
			if( substr_count( $fileName, "/" ) )
				$content	= preg_replace( "@(\.\./){1}([^\.])@i", "\\2", $content );
			$list[]	= "";
			$list[]	= "/*  --  ".$fileName."  --  */";
			$list[]	= $content;
		}
		$content	= implode( "\n", $list );
		$this->statistics['combined']	= strlen( $content );
		return $content;
	}

	/**
	 *	Combines all CSS Files imported in Style File, saves Combination File and returns File URI of Combination File.
	 *	@access		public
	 *	@param		string		$styleFile		File Name of Style without Extension (iE. style.css,import.css,default.css)
	 *	@param		bool		$verbose		Flag: list loaded CSS Files
	 *	@return		string		
	 */
	public function combineFile( $fileUri )
	{
		$pathName	= dirname( realpath( $fileUri ) )."/";
		$fileBase	= preg_replace( "@\.css@", "", basename( $fileUri  ));
		
		if( !file_exists( $fileUri ) )
			throw new Exception( "Style File '".$fileUri."' is not existing." );
			
		$this->statistics	= array();
		$content	= file_get_contents( $fileUri );
	
		$content	= $this->combineString( $pathName, $content );
		$fileName	= $this->prefix.$fileBase.$this->suffix.".css";
		$fileUri	= $pathName.$fileName;
		file_put_contents( $fileUri, $content );
		return $fileUri;
	}

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
	 *	Sets Prefix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of combined File Name
	 *	@return		void
	 */
	public function setPrefix( $prefix )
	{
		if( trim( $prefix ) )
			$this->prefix	= $prefix;
	}	
	
	/**
	 *	Sets Suffix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of combined File Name
	 *	@return		void
	 */
	public function setSuffix( $suffix )
	{
		if( trim( $suffix ) )
			$this->suffix	= $suffix;
	}	
}
?>