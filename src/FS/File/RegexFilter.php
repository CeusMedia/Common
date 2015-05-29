<?php
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *
 *	Copyright (c) 2007-2015 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			09.06.2007
 *	@version		$Id$
 */
/**
 *	Searchs for Files by given RegEx Pattern (as File Name) in Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@extends		RegexIterator
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			09.06.2007
 *	@version		$Id$
 *	@todo			Fix Error while comparing File Name to Current File with Path
 */
class FS_File_RegexFilter extends RegexIterator
{
	/**	@var	int				$numberFound			Number of found Files */
	protected $numberFound		= 0;
	/**	@var	int				$numberScanned			Number of scanned Files */
	protected $numberScanned	= 0;
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
		$this->numberFound		= 0;
		$this->numberScanned	= 0;
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
		$this->numberScanned++;
		if( !parent::accept() )
			return FALSE;
		$this->numberFound++;
		if( !$this->contentPattern )
			return TRUE;
		$filePath	= $this->current()->getPathname();
		$realPath	= realpath( $this->current()->getPathname() );
		if( $realPath )
			$filePath	= $realPath;
		$content	= FS_File_Reader::load( $filePath );
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