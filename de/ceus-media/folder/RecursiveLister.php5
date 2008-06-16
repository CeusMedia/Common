<?php
import( 'de.ceus-media.folder.Lister' );
/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@package		folder
 *	@extends		Folder_Lister
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
 */
/**
 *	Lists Folders and Files within a Folder recursive.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@package		folder
 *	@extends		Folder_Lister
 *	@uses			Folder_RecursiveRegexFilter
 *	@uses			Folder_RecursiveIterator
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
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
			return new Folder_RecursiveRegexFilter( $this->path, $this->pattern, $this->showFiles, $this->showFolders, $this->stripDotFolders );
		}
		import( 'de.ceus-media.folder.RecursiveIterator' );
		return new Folder_RecursiveIterator( $this->path, $this->showFiles, $this->showFolders, $this->stripDotFolders );
	}

	/**
	 *	Returns List of Files statically.
	 *	@access		public
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
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Folder Name
	 *	@param		bool		$stripDotFolders	Flag: strip Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getFolderList( $path, $pattern = NULL, $stripDotFolders = TRUE )
	{
		$index	= new Folder_RecursiveLister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( FALSE );
		$index->showFolders( TRUE );
		$index->stripDotFolders( $stripDotFolders );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders and Files statically.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Entry Name
	 *	@param		bool		$stripDotFolders	Flag: strip Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getMixedList( $path, $pattern = NULL, $stripDotFolders = TRUE )
	{
		$index	= new Folder_RecursiveLister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( TRUE );
		$index->stripDotFolders( $stripDotFolders );
		return $index->getList();
	}}
?>