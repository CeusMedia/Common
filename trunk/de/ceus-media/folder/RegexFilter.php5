<?php
/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
 *	@package	file
 *	@extends	RegexIterator
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 */
/**
 *	Searchs for Folders by given RegEx Pattern (as File Name) in Folder.
 *	@package	file
 *	@extends	RegexIterator
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 *	@todo		Fix Error while comparing File Name to Current File with Path
 */
class Folder_RegexFilter extends RegexIterator
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
	 *	@param		string		$path		Path to seach in
	 *	@param		string		$pattern	Regular Expression to match with File Name
	 *	@param		bool		$showFiles			Flag: show Files
	 *	@param		bool		$showFolders		Flag: show Folders
	 *	@param		bool		$stripDotFolders	Flag: strip Folder with leading Dot
	 *	@return		void
	 */
	public function __construct( $path, $pattern, $showFiles = TRUE, $showFolders = TRUE, $stripDotFolders = TRUE  )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
    	$this->showFiles		= $showFiles;
    	$this->showFolders		= $showFolders;
    	$this->stripDotFolders	= $stripDotFolders;
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
		if( $this->stripDotFolders && $isDir )
			if( preg_match( "@^\.\w@", $this->getFilename() ) )
				return FALSE;
		return parent::accept();
	}
}
?>