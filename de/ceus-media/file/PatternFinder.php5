<?php
/**
 *	Searchs for a File by given RegEx Pattern (as File Name) in Folder recursive.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 */
/**
 *	Searchs for a File by given RegEx Pattern (as File Name) in Folder recursive.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since		09.06.2007
 *	@version	0.1
 *	@todo		Fix Error while comparing File Name to Current File with Path
 */
class File_PatternFinder extends FilterIterator
{
	/**	@var	string		$pattern		Regular Expression to match with File Name */
	private $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		Path to seach in
	 *	@param		string		$pattern	Regular Expression to match with File Name
	 *	@return		void
	 */
	function __construct( $path, $pattern )
	{
		$this->pattern	= $pattern;
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
		return preg_match( $this->pattern, basename( $this->current() ) );
	}
}
?>