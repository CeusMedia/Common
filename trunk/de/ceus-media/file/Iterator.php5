<?php
/**
 *	Iterates all Files within a Folder.
 *	@package		file
 *	@extends		FilterIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
/**
 *	Iterates all Files within a Folder.
 *	@package		file
 *	@extends		FilterIterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
class File_Iterator extends FilterIterator
{
	/**	@var		 bool		$stripDotFiles		Flag: strip Files with leading Dot */
	protected $stripDotFiles;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		bool		$stripDotFiles		Flag: strip Files with leading Dot
	 *	@return		void
	 */
	public function __construct( $path, $stripDotFiles = TRUE )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->stripDotFiles	= $stripDotFiles;
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
		if( $this->getInnerIterator()->isDir() )
			return FALSE;

		if( $this->stripDotFiles )
		{
			$fileName	= $this->getInnerIterator()->getFilename();
			if( $fileName[0] == "." )
				return FALSE;
		}
		return TRUE;
	}
}
?>