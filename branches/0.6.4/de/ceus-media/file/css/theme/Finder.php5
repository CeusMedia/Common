<?php
class File_CSS_Theme_Finder
{
	public function __construct( $themePath, $cssPath = "css/screen/" )
	{
		$this->themePath	= $themePath;
		$this->cssPath		= $cssPath;
	}
	
	public function getThemes( $withBrowsers = FALSE )
	{
		$list	= array();
		$dir	= new DirectoryIterator( $this->themePath );
		foreach( $dir as $entry )
		{
			if( !$entry->isDir() )
				continue;
			if( substr( $entry->getFilename(), 0, 1 ) == "." )
				continue;
			$themeName		= $entry->getFilename();
			if( $withBrowsers )
			{
				$cssPath	= $this->themePath.$entry->getFilename()."/".$this->cssPath;
				$subdir	= new DirectoryIterator( $cssPath );
				foreach( $subdir as $browser )
				{
					if( !$browser->isDir() )
						continue;
					if( substr( $browser->getFilename(), 0, 1 ) == "." )
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
?>