<?php
/**
 *	Combines and compresses Stylesheet Files of cmFramework Themes.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Combines and compresses Stylesheet Files of cmFramework Themes.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@uses			FS_File_CSS_Combiner
 *	@uses			FS_File_CSS_Compressor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FS_File_CSS_Theme_Minimizer
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
		//  set Themes Path
		$this->setThemesPath( $themesPath );
		$this->combiner		= new FS_File_CSS_Combiner;
		//  get CSS Compressor Instance
		$this->compressor	= new FS_File_CSS_Compressor;
	}
	
	/**
	 *	Returns full Path of Style File (with CSS Folder and Medium).
	 *	@access		public
	 *	@return		void
	 */
	private function getPath()
	{
		//  Basic Themes Path
		$themesPath = $this->themesPath;
		//  Theme Path is set
		if( $this->themeName )
			//  add Theme Path
			$themesPath .= $this->themeName;
		//  CSS Folder is set
		if( $this->cssFolder )
			//  add CSS Folder
			$themesPath .= $this->cssFolder;
		//  Medium Path is set
		if( $this->mediumPath )
			//  add Medium Path
			$themesPath .= $this->mediumPath;
		return $themesPath;
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
	 *	Combines (and compresses) Theme (Medium).
	 *	@access		public
	 *	@param		string		$styleFile		File Name of main Theme Style File (iE. style.css,import.css,default.css)
	 *	@param		bool		$compress		Flag: compress combined File
	 */
	public function minimize( $styleFile, $compress = FALSE )
	{
		//  get full Path to Style File
		$pathName	= $this->getPath();

		//  --  SET COMBINER ENVIRONMENT  --  //
		//  set Combiner Prefix
		$this->combiner->setPrefix( $this->combinerPrefix );
		//  set Combiner Suffix
		$this->combiner->setPrefix( $this->combinerSuffix );

		//  --  LAUNCH COMBINER  --  //
		//  combine CSS Files
		$fileUri	= $this->combiner->combineFile( $pathName.$styleFile );
		//  collect Statisticts
		$this->statistics	= $this->combiner->getStatistics();

		//  Compression is enabled
		if( $compress )
		{
			//  --  SET COMPRESSOR ENVIRONMENT  --  //
			//  set Compressor Prefix
			$this->compressor->setPrefix( $this->compressorPrefix );
			//  set Compressor Suffix
			$this->compressor->setPrefix( $this->compressorSuffix );

			//  --  LAUNCH COMPRESSOR  --  //
			//  compress CSS File
			$targetFile	= $this->compressor->compressFile( $fileUri );
			//  collect Statisticts
			$statistics	= $this->compressor->getStatistics();
			//  merge Statisticts
			$this->statistics['sizeCompressed']	= $statistics['after'];
			//  note compressed Target File Path
			$this->statistics['fileCompressed']	= realpath( $targetFile );
		}
		//  note Source File Path
		$this->statistics['fileSource']		= realpath( $pathName.$styleFile );
		//  note combined Target File Path
		$this->statistics['fileCombined']	= realpath( $fileUri );
		return TRUE;
	}
	
	/**
	 *	Sets a combiner Object to use.
	 *	@access		public
	 *	@param		FS_File_CSS_Combiner	$combiner		Combiner Object
	 *	@return		void
	 */
	public function setCombinerObject( FS_File_CSS_Combiner $combiner )
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
	 *	@param		FS_File_CSS_Compressor	$compressor		Compressor Object
	 *	@return		void
	 */
	public function setCompressorObject( FS_File_CSS_Compressor $compressor )
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
