<?php
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
 */
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6a
 */
class Folder_RecursiveIterator extends FilterIterator
{
	/**	@var		 string		$path				Path to iterate */
	protected $path;
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
		$this->path				= $path;
		$this->showFiles		= $showFiles;
		$this->showFiles		= $showFiles;
		$this->stripDotFolders	= $stripDotFolders;
		$selfIterator			= $showFolders ? RecursiveIteratorIterator::SELF_FIRST : RecursiveIteratorIterator::LEAVES_ONLY;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					$path,
					0
				),
				$selfIterator
			)
		);
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
		if( !$this->showFiles && !$isDir )
			return FALSE;

		if( $this->stripDotFolders )
		{
			$folderName	= $this->getInnerIterator()->getFilename();
			if( preg_match( "@^\.\w@", $folderName ) )
				return FALSE;
		}
		return TRUE;
	}
	
	public function getPath()
	{
		return $this->path;
	}
}
?>