<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Combines and compresses Stylesheet Files of cmFramework Themes.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS\Theme;

use CeusMedia\Common\FS\File\CSS\Combiner as CssCombiner;
use CeusMedia\Common\FS\File\CSS\Compressor as CssCompressor;

/**
 *	Combines and compresses Stylesheet Files of cmFramework Themes.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Minimizer
{
	/**	@var		CssCombiner					$combiner		Combiner instance */
	protected $combiner;

	/**	@var		CssCompressor				$compressor		Compressor instance */
	protected $compressor;

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
	protected $statistics		= [];

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
	public function __construct( string $themesPath )
	{
		//  set Themes Path
		$this->setThemesPath( $themesPath );
		$this->combiner		= new CssCombiner;
		//  get CSS Compressor Instance
		$this->compressor	= new CssCompressor;
	}

	/**
	 *	Returns full Path of Style File (with CSS Folder and Medium).
	 *	@access		private
	 *	@return		string
	 */
	private function getPath(): string
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
	public function getStatistics(): array
	{
		return $this->statistics;
	}

	/**
	 *	Combines (and compresses) Theme (Medium).
	 *	@access		public
	 *	@param		string		$styleFile		File Name of main Theme Style File (iE. style.css,import.css,default.css)
	 *	@param		bool		$compress		Flag: compress combined File
	 */
	public function minimize( string $styleFile, bool $compress = FALSE ): bool
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
		//  collect Statistics
		$this->statistics	= $this->combiner->getStatistics();

		//  Compression is enabled
		if( $compress ){
			//  --  SET COMPRESSOR ENVIRONMENT  --  //
			//  set Compressor Prefix
			$this->compressor->setPrefix( $this->compressorPrefix );
			//  set Compressor Suffix
			$this->compressor->setPrefix( $this->compressorSuffix );

			//  --  LAUNCH COMPRESSOR  --  //
			//  compress CSS File
			$targetFile	= $this->compressor->compressFile( $fileUri );
			//  collect Statistics
			$statistics	= $this->compressor->getStatistics();
			//  merge Statistics
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
	 *	@param		CssCombiner		$combiner		Combiner Object
	 *	@return		self
	 */
	public function setCombinerObject( CssCombiner $combiner ): self
	{
		$this->combiner	= $combiner;
		return $this;
	}

	/**
	 *	Sets Prefix of combined File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of combined File Name
	 *	@return		self
	 */
	public function setCombinerPrefix( string $prefix ): self
	{
		$this->combinerPrefix	= $prefix;
		return $this;
	}

	/**
	 *	Sets Suffix of combined File Name.
	 *	@access		public
	 *	@param		string		$suffix			Suffix of combined File Name
	 *	@return		self
	 */
	public function setCombinerSuffix( string $suffix ): self
	{
		$this->combinerSuffix	= $suffix;
		return $this;
	}

	/**
	 *	Sets a Compressor Object to use.
	 *	@access		public
	 *	@param		CssCompressor	$compressor		Compressor Object
	 *	@return		self
	 */
	public function setCompressorObject( CssCompressor $compressor ): self
	{
		$this->compressor	= $compressor;
		return $this;
	}

	/**
	 *	Sets Prefix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of compressed File Name
	 *	@return		self
	 */
	public function setCompressorPrefix( string $prefix ): self
	{
		$this->compressorPrefix	= $prefix;
		return $this;
	}

	/**
	 *	Sets Suffix of compressed File Name.
	 *	@access		public
	 *	@param		string		$suffix			Suffix of compressed File Name
	 *	@return		self
	 */
	public function setCompressorSuffix( string $suffix ): self
	{
		$this->compressorSuffix	= $suffix;
		return $this;
	}

	/**
	 *	Sets CSS Folder (within Theme Path) within Themes Path.
	 *	@access		public
	 *	@param		string		$cssFolder		CSS Folder within (Theme) Path
	 *	@return		self
	 */
	public function setCssFolder( string $cssFolder ): self
	{
		if( trim( $cssFolder ) )
			$this->cssFolder	= preg_replace( "@(.+)/$@", "\\1", $cssFolder )."/";
		return $this;
	}

	/**
	 *	Sets Medium Name (within CSS Path (within Theme Path)) within Themes Path.
	 *	@access		public
	 *	@param		string		$medium			Medium Name (within CSS Folder)
	 *	@return		self
	 */
	public function setMedium( string $medium ): self
	{
		$this->mediumPath	= preg_replace( "@(.+)/$@", "\\1", $medium )."/";
		return $this;
	}

	/**
	 *	Sets Theme Name within Themes Path.
	 *	@access		public
	 *	@param		string		$themeName			Suffix of combined File Name
	 *	@return		self
	 */
	public function setTheme( string $themeName ): self
	{
		$this->themeName	= preg_replace( "@(.+)/$@", "\\1", $themeName )."/";
		return $this;
	}

	/**
	 *	Sets Path to Themes.
	 *	@access		public
	 *	@param		string		$themesPath		Path to Themes
	 *	@return		self
	 */
	public function setThemesPath( string $themesPath ): self
	{
		$this->themesPath	= preg_replace( "@(.+)/$@", "\\1", $themesPath )."/";
		return $this;
	}
}
