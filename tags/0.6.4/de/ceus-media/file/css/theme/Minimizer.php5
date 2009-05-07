<?php
import( 'de.ceus-media.file.css.Combiner' );
import( 'de.ceus-media.file.css.Compressor' );
class File_CSS_Theme_Minimizer
{
	/**	@var		string		$cssFolder		Name of CSS Folder within Theme Path (optional) */
	protected $cssFolder		= "";
	/**	@var		string		$cssFolder		Path Medium within CSS Path within Theme (optional) */
	protected $mediumPath		= "";
	/**	@var		string		$prefix			Prefix of combined File Name */
	protected $combinerPrefix	= "";
	/**	@var		string		$suffix			Suffix of combined File Name */
	protected $combinerSuffix	= "";
	/**	@var		string		$prefix			Prefix of compressed File Name */
	protected $compressorPrefix	= "";
	/**	@var		string		$suffix			Suffix of compressed File Name */
	protected $compressorSuffix	= "";
	/**	@var		array		$statistics		Statistical Data */
	protected $statistics		= array();
	/**	@var		string		$themesPath		Path to Themes */
	protected $themesPath;
	/**	@var		string		$themeName		Name of Theme */
	protected $themeName		= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$themesPath		Base Theme Path
	 *	@return		void
	 */
	public function __construct( $themesPath )
	{
		$this->setThemesPath( $themesPath );										//  set Themes Path
		$this->combiner		= new File_CSS_Combiner;
		$this->compressor	= new File_CSS_Compressor;								//  get CSS Compressor Instance
	}
	
	/**
	 *	Returns full Path of Style File (with CSS Folder and Medium).
	 *	@access		public
	 *	@return		void
	 */
	private function getPath()
	{
		$themesPath = $this->themesPath;											//  Basic Themes Path
		if( $this->themeName )														//  Theme Path is set
			$themesPath .= $this->themeName;										//  add Theme Path
		if( $this->cssFolder )														//  CSS Folder is set
			$themesPath .= $this->cssFolder;										//  add CSS Folder
		if( $this->mediumPath )														//  Medium Path is set
			$themesPath .= $this->mediumPath;										//  add Medium Path
		return $themesPath;
	}

	/**
	 *	Combines (and compresses) Theme (Medium).
	 *	@access		public
	 *	@param		string		$styleFile		File Name of main Theme Style File (iE. style.css,import.css,default.css)
	 *	@param		bool		$compress		Flag: compress combined File
	 */
	public function minimize( $styleFile, $compress = FALSE )
	{
		$pathName	= $this->getPath();												//  get full Path to Style File

		//  --  SET COMBINER ENVIRONMENT  --  //
		$this->combiner->setPrefix( $this->combinerPrefix );						//  set Combiner Prefix
		$this->combiner->setPrefix( $this->combinerSuffix );						//  set Combiner Suffix

		//  --  LAUNCH COMBINER  --  //
		$fileUri	= $this->combiner->combineFile( $pathName.$styleFile );			//  combine CSS Files
		$this->statistics	= $this->combiner->getStatistics();						//  collect Statisticts

		if( $compress )																//  Compression is enabled
		{
			//  --  SET COMPRESSOR ENVIRONMENT  --  //
			$this->compressor->setPrefix( $this->compressorPrefix );				//  set Compressor Prefix
			$this->compressor->setPrefix( $this->compressorSuffix );				//  set Compressor Suffix

			//  --  LAUNCH COMPRESSOR  --  //
			$targetFile	= $this->compressor->compressFile( $fileUri );				//  compress CSS File
			$statistics	= $this->compressor->getStatistics();						//  collect Statisticts
			$this->statistics['sizeCompressed']	= $statistics['after'];				//  merge Statisticts
			$this->statistics['fileCompressed']	= realpath( $targetFile );			//  note compressed Target File Path
		}
		$this->statistics['fileSource']		= realpath( $pathName.$styleFile );		//  note Source File Path
		$this->statistics['fileCombined']	= realpath( $fileUri );					//  note combined Target File Path
		return TRUE;
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
	 *	Sets a combiner Object to use.
	 *	@access		public
	 *	@param		File_CSS_Combiner	$combiner		Combiner Object
	 *	@return		void
	 */
	public function setCombinerObject( File_CSS_Combiner $combiner )
	{
		$this->combiner	= $combiner;
	}

	/**
	 *	Sets Prefix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of combined File Name
	 *	@return		void
	 */
	public function setCombinerPrefix( $prefix )
	{
		$this->combinerPrefix	= $prefix;
	}	
	
	/**
	 *	Sets Suffix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of combined File Name
	 *	@return		void
	 */
	public function setCombinerSuffix( $suffix )
	{
		$this->combinerSuffix	= $suffix;
	}	

	/**
	 *	Sets a Compressor Object to use.
	 *	@access		public
	 *	@param		File_CSS_Compressor	$compressor		Compressor Object
	 *	@return		void
	 */
	public function setCompressorObject( File_CSS_Compressor $compressor )
	{
		$this->compressor	= $compressor;
	}

	/**
	 *	Sets Prefix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of compressed File Name
	 *	@return		void
	 */
	public function setCompressorPrefix( $prefix )
	{
		$this->compressorPrefix	= $prefix;
	}	
	
	/**
	 *	Sets Suffix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of compressed File Name
	 *	@return		void
	 */
	public function setCompressorSuffix( $suffix )
	{
		$this->compressorSuffix	= $suffix;
	}	

	/**
	 *	Sets CSS Folder (within Theme Path) within Themes Path.
	 *	@access		public
	 *	@param		string		$cssFolder		CSS Folder within (Theme) Path
	 *	@return		void
	 */
	public function setCssFolder( $cssFolder )
	{
		if( trim( $cssFolder ) )
			$this->cssFolder	= preg_replace( "@(.+)/$@", "\\1", $cssFolder )."/";
	}

	/**
	 *	Sets Medium Name (within CSS Path (within Theme Path)) within Themes Path.
	 *	@access		public
	 *	@param		string		$prefix			Medium Name (within CSS Folder)
	 *	@return		void
	 */
	public function setMedium( $medium )
	{
		$this->mediumPath	= preg_replace( "@(.+)/$@", "\\1", $medium )."/";
	}

	/**
	 *	Sets Theme Name within Themes Path.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of combined File Name
	 *	@return		void
	 */
	public function setTheme( $themeName )
	{
		$this->themeName	= preg_replace( "@(.+)/$@", "\\1", $themeName )."/";
	}
		
	/**
	 *	Sets Path to Themes.
	 *	@access		public
	 *	@param		string		$themesPath		Path to Themes
	 *	@return		void
	 */
	public function setThemesPath( $themesPath )
	{
		$this->themesPath	= preg_replace( "@(.+)/$@", "\\1", $themesPath )."/";
	}
}
?>