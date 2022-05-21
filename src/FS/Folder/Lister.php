<?php
/**
 *	Lists Folders and Files within a Folder.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
/**
 *	Lists Folders and Files within a Folder.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@uses			FS_Folder_RegexFilter
 *	@uses			FS_Folder_Iterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.04.2008
 */
class FS_Folder_Lister
{
	/**	@var		string		$path				Path to Folder */
	protected $path				= NULL;

	/**	@var		string|NULL	$pattern			Regular Expression to match with File Name */
	protected $pattern			= NULL;

	/**	@var		boolean		$showFiles			Flag: show Files */
	protected $showFiles		= TRUE;

	/**	@var		boolean		$showFolders		Flag: show Folders */
	protected $showFolders		= TRUE;

	/**	@var		boolean		$stripDotEntries	Flag: strip Files and Folder with leading Dot */
	protected $stripDotEntries	= TRUE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@return		void
	 */
	public function __construct( string $path )
	{
		$this->path	= $path;
	}

	/**
	 *	Returns List of Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with File Name
	 *	@return		FilterIterator
	 */
	public static function getFileList( string $path, string $pattern = NULL ): FilterIterator
	{
		$index	= new FS_Folder_Lister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( FALSE );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string|NULL	$pattern			RegEx Pattern to match with Folder Name
	 *	@param		boolean		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */
	public static function getFolderList( string $path, ?string $pattern = NULL, bool $stripDotEntries = TRUE ): FilterIterator
	{
		$index	= new FS_Folder_Lister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( FALSE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}

	/**
	 *	Returns List as FilterIterator.
	 *	@access		public
	 *	@return		FilterIterator
	 */
	public function getList(): FilterIterator
	{
		if( $this->pattern )
			return new FS_Folder_RegexFilter(
				$this->path,
				$this->pattern,
				$this->showFiles,
				$this->showFolders,
				$this->stripDotEntries
			);
		return new FS_Folder_Iterator(
			$this->path,
			$this->showFiles,
			$this->showFolders,
			$this->stripDotEntries
		);
	}

	/**
	 *	Returns List of Folders and Files statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$path				Path to Folder
	 *	@param		string|NULL	$pattern			RegEx Pattern to match with Entry Name
	 *	@param		boolean		$stripDotEntries	Flag: strip Files and Folders starting with a Dot
	 *	@return		FilterIterator
	 */
	public static function getMixedList( string $path, ?string $pattern = NULL, bool $stripDotEntries = TRUE ): FilterIterator
	{
		$index	= new FS_Folder_Lister( $path );
		if( $pattern !== NULL )
			$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( TRUE );
		$index->stripDotEntries( $stripDotEntries );
		return $index->getList();
	}

	/**
	 *	Sets Filter for Extensions.
	 *	Caution! Method overwrites Pattern if already set.
	 *	Caution! Flag 'showFiles' needs to be set to TRUE.
	 *	@access		public
	 *	@param		array		$extensions			List of allowed File Extensions.
	 *	@return		self
	 */
	public function setExtensions( array $extensions = array() ): self
	{
		$pattern	= "";
		if( count( $extensions ) !== 0 ){
			$extensions	= join( '|', array_values( $extensions ) );
			$pattern	= '@\.'.$extensions.'$@i';
		}
		$this->pattern	= $pattern;
		return $this;
	}

	/**
	 *	Sets Filter Pattern.
	 *	Caution! Method overwrites Extension Filter if already set.
	 *	@access		public
	 *	@param		string		$pattern			RegEx Pattern for allowed Entries, eg. '@^A@' for all Entries starting with an A.
	 *	@return		self
	 */
	public function setPattern( string $pattern ): self
	{
		$this->pattern	= $pattern;
		return $this;
	}

	/**
	 *	Sets whether Files should be listed.
	 *	@access		public
	 *	@param		boolean		$flag				Flag: show Files, default: yes
	 *	@return		self
	 */
	public function showFiles( bool $flag = TRUE ): self
	{
		$this->showFiles	= (bool) $flag;
		return $this;
	}

	/**
	 *	Sets whether Folders should be listed.
	 *	@access		public
	 *	@param		boolean		$flag				Flag: show Folders, default: yes
	 *	@return		self
	 */
	public function showFolders( bool $flag = TRUE ): self
	{
		$this->showFolders	= (bool) $flag;
		return $this;
	}

	/**
	 *	Sets whether Files and Folders starting with a Dot should be stripped from the List.
	 *	@access		public
	 *	@param		boolean		$flag			Flag: strip Files and Folders starting with a Dot, default: yes
	 *	@return		self
	 */
	public function stripDotEntries( bool $flag = TRUE ): self
	{
		$this->stripDotEntries	= (bool) $flag;
		return $this;
	}
}
