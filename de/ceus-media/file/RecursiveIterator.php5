<?php
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
/**
 *	Iterates all Folders and Files recursive within a Folder.
 *	@package		folder
 *	@extends		FilterIterator
 *	@uses			RecursiveIteratorIterator
 *	@uses			RecursiveDirectoryIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
class File_RecursiveIterator extends RecursiveIteratorIterator
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
	 *	@return		void
	 */
	public function __construct( $path )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path				= $path;
		$selfIterator			= RecursiveIteratorIterator::LEAVES_ONLY;
		parent::__construct(
			new RecursiveDirectoryIterator(
				$path,
				0
			),
			$selfIterator
		);
	}

	/**
	 *	Returns Path to Folder to iterate.
	 *	@access		public
	 *	@return		string		Path to Folder to iterate
	 */
	public function getPath()
	{
		return $this->path;
	}
}
?>