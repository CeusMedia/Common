<?php
/**
 *	Searchs for a File by given File Name in Folder recursive.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 */
/**
 *	Searchs for a File by given File Name in Folder recursive.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 *	@todo		Fix Error while comparing File Name to Current File with Path
 */
class File_RecursiveNameFilter extends FilterIterator
{
	/**	@var	string		$fileName		Name of File to be found */
	private $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		Path to seach in
	 *	@param		string		$fileName	Name of File to be found
	 *	@return		void
	 */
	public function __construct( $path, $fileName )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->fileName = $fileName;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $path )
			)
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept()
	{
		return !strcmp( basename( $this->current() ), $this->fileName );
	}
}
?>