<?php
/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		folder
 *	@extends		RegexIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.06.2007
 *	@version		0.6
 */
/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
 *	@category		cmClasses
 *	@package		folder
 *	@extends		RegexIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.06.2007
 *	@version		0.6
 *	@todo			Fix Error while comparing File Name to Current File with Path
 */
class Folder_RegexFilter extends RegexIterator
{
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles;
	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders;
	/**	@var		 bool		$stripDotEntries	Flag: strip Folder with leading Dot */
	protected $stripDotEntries;


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to seach in
	 *	@param		string		$pattern			Regular Expression to match with File Name
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( $path, $pattern, $showFiles = TRUE, $showFolders = TRUE, $stripDotEntries = TRUE  )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
    	$this->showFiles		= $showFiles;
    	$this->showFolders		= $showFolders;
    	$this->stripDotEntries	= $stripDotEntries;
		parent::__construct(
			new DirectoryIterator( $path  ),
			$pattern
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept()
	{
		if( $this->isDot() )
			return FALSE;
		$isDir	= $this->isDir();
		if( !$this->showFiles && !$isDir )
			return FALSE;
		if( !$this->showFolders && $isDir )
			return FALSE;
		if( $this->stripDotEntries )
		{
			if( preg_match( "@^\.\w@", $this->getFilename() ) )
				return FALSE;
		}
		return parent::accept();
	}
}
?>