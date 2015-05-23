<?php
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		File
 *	@extends		RegexIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.06.2007
 *	@version		$Id$
 */
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *	@category		cmClasses
 *	@package		File
 *	@extends		RegexIterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.06.2007
 *	@version		$Id$
 *	@todo			Fix Error while comparing File Name to Current File with Path
 */
class File_RecursiveRegexFilter extends RegexIterator
{
	/**	@var	int				$numberFound			Number of found Files */
	protected $numberFound		= 0;
	/**	@var	int				$numberScanned	Number of scanned Files */
	protected $numberScanned	= 0;
	/**	@var	string			filePattern		Regular Expression to match with File Name */
	private $filePattern;
	/**	@var	string			$contentPattern	Regular Expression to match with File Content */
	private $contentPattern;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to seach in
	 *	@param		string		$pattern		Regular Expression to match with File Name
	 *	@return		void
	 */
	public function __construct( $path, $filePattern, $contentPattern = NULL )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->numberScanned	= 0;
		$this->numberFound		= 0;
		$this->filePattern		= $filePattern;
		$this->contentPattern	= $contentPattern;
		parent::__construct(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					$path,
					0
				)
			),
			$filePattern,
			parent::MATCH
		);
	}

	/**
	 *	Filter Callback.
	 *	@access		public
	 *	@return		bool
	 */
	public function accept()
	{
		$this->numberScanned++;
		if( !preg_match( $this->filePattern, $this->current()->getFilename() ) )
			return FALSE;
		$this->numberFound++;
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
	 *	Returns Number of found Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberFound()
	{
		return $this->numberFound;
	}
	
	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberScanned()
	{
		return $this->numberScanned;
	}

	/**
	 *	Resets inner Iterator and numbers.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->numberFound		= 0;
		$this->numberScanned	= 0;
		parent::rewind();
	}
}
?>