<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Finds Web Application Themes in cmFrameworks.
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

use DirectoryIterator;

/**
 *	Finds Web Application Themes in cmFrameworks.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Finder
{
	protected string $themePath;

	protected string $cssPath;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$themePath			Path to Themes
	 *	@param		string			$cssPath			Path to Stylesheets within Themes
	 *	@return		void
	 */
	public function __construct( string $themePath, string $cssPath = "css/screen/" )
	{
		$this->themePath	= $themePath;
		$this->cssPath		= $cssPath;
	}

	/**
	 *	Returns found Themes as List.
	 *	@access		public
	 *	@param		bool			$withBrowsers		Flag: Stylesheets with Browser Folders
	 *	@return		array
	 */
	public function getThemes( bool $withBrowsers = FALSE ): array
	{
		$list	= [];
		$dir	= new DirectoryIterator( $this->themePath );
		foreach( $dir as $entry ){
			if( !$entry->isDir() )
				continue;
			if( str_starts_with( $entry->getFilename(), '.' ) )
				continue;
			$themeName		= $entry->getFilename();
			if( $withBrowsers ){
				$cssPath	= $this->themePath.$entry->getFilename()."/".$this->cssPath;
				$subdir	= new DirectoryIterator( $cssPath );
				foreach( $subdir as $browser ){
					if( !$browser->isDir() )
						continue;
					if( str_starts_with( $browser->getFilename(), '.' ) )
						continue;
					$browserName	= $browser->getFilename();
					$list[$themeName][$themeName.":".$browserName]	= $browserName;
				}
			}
			else
				$list[]	= $themeName;
		}
		return $list;
	}
}
