<?php
/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@extends		Folder_Lister
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
import( 'de.ceus-media.folder.Lister' );
/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@category		cmClasses
 *	@package		folder
 *	@extends		Folder_Lister
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.04.2008
 *	@version		$Id$
 */
class Folder_RecursiveLister extends Folder_Lister
{
	/**
	 *	Returns List as FilterIterator.
	 *	@access		public
	 *	@return		FilterIterator
	 */
	public function getList()
	{
		if( $this->pattern )
		{
			import( 'de.ceus-media.folder.RecursiveRegexFilter' );
			return new Folder_RecursiveRegexFilter( $this->path, $this->pattern, $this->showFiles, $this->showFolders, $this->stripDotEntries );
		}
		import( 'de.ceus-media.folder.RecursiveIterator' );
		return new Folder_RecursiveIterator( $this->path, $this->showFiles, $this->showFolders, $this->stripDotEntries );
	}

	/**
	 *	Returns List of Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with File Name
	 *	@return		FilterIterator
	 */	
	public static function getFileList( $path, $pattern = NULL )
	{
		$index	= new Folder_RecursiveLister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( FALSE );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Folder Name
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getFolderList( $path, $pattern = NULL, $stripDotEntries = TRUE )
	{
		$index	= new Folder_RecursiveLister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( FALSE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders and Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Entry Name
	 *	@param		bool		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getMixedList( $path, $pattern = NULL, $stripDotEntries = TRUE )
	{
		$index	= new Folder_RecursiveLister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}}
?>