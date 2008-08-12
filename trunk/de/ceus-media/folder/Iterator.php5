<?php
/**
 *	Iterates all Folders and Files within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
/**
 *	Iterates all Folders and Files within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
class Folder_Iterator extends FilterIterator
{
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles;
	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders;
	/**	@var		 bool		$stripDotFolders	Flag: strip Folder with leading Dot */
	protected $stripDotFolders;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotFolders	Flag: strip Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( $path, $showFiles = TRUE, $showFolders = TRUE, $stripDotFolders = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->showFiles		= $showFiles;
		$this->showFolders		= $showFolders;
		$this->stripDotFolders	= $stripDotFolders;
		parent::__construct( new DirectoryIterator( $path ) );
	}

	/**
	 *	Decides which Entry should be indexed.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept()
	{
		if( $this->getInnerIterator()->isDot() )
			return FALSE;
		$isDir	= $this->getInnerIterator()->isDir();
		if( !$this->showFolders && $isDir ) 
			return FALSE;
		if( !$this->showFiles && !$isDir ) 
			return FALSE;

		if( $this->stripDotFolders && $isDir )
		{
			$folderName	= $this->getInnerIterator()->getFilename();
			if( preg_match( "@^\.\w@", $folderName ) )
				return FALSE;
		}
		return TRUE;
	}
}
?>