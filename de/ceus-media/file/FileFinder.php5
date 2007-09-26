<?php
/**
 *	Searchs for a File by given File Name in Folder recursive.
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 */
/**
 *	Searchs for a File by given File Name in Folder recursive.
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 *	@todo		Fix Error while comparing File Name to Current File with Path
 */
class FileFinder extends FilterIterator
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
	function __construct( $path, $fileName )
	{
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
	function accept()
	{
		return !strcmp( basename( $this->current() ), $this->fileName );
	}
}
?>