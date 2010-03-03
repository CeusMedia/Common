<?php
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since		09.06.2007
 *	@version	0.1
 */
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *	@package	file
 *	@author		Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since		09.06.2007
 *	@version	0.1
 *	@todo		Fix Error while comparing File Name to Current File with Path
 */
class File_RegexFilter extends RegexIterator
{
	/**	@var	int				$count			Number of scanned Files */
	protected $count			= 0;
	/**	@var	int				$count			Number of found Files */
	protected $files			= 0;
	/**	@var	string			$contentPattern	Regular Expression to match with File Content */
	private $contentPattern;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		Path to seach in
	 *	@param		string		$pattern	Regular Expression to match with File Name
	 *	@return		void
	 */
	public function __construct( $path, $filePattern, $contentPattern = NULL )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->count			= 0;
		$this->files			= 0;
		$this->contentPattern	= $contentPattern;
		parent::__construct(
			new DirectoryIterator( $path ),
			$filePattern
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept()
	{
		$this->count++;
		if( !parent::accept() )
			return FALSE;
		$this->files++;
		if( !$this->contentPattern )
			return TRUE;
		$filePath	= $this->current()->getPathname();
		$realPath	= realpath( $this->current()->getPathname() );
		if( $realPath )
			$filePath	= $realPath;
		$content	= file_get_contents( $filePath );
		$found		= preg_match( $this->contentPattern, $content );
		return $found;
	}
	
	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 *	Returns Number of found Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getFiles()
	{
		return $this->files;
	}
}
?>