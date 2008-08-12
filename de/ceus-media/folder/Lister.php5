<?php
import( 'de.ceus-media.folder.RegexFilter' );
/**
 *	Lists Folders and Files within a Folder.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@package		folder
 *	@uses			Folder_RegexFilter
 *	@uses			Folder_Iterator
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
/**
 *	Lists Folders and Files within a Folder.
 *	Entries can be filtered with a RegEx Pattern or allowed Extensions.
 *	The resulting List is a FilterIterator and contains SplInfo Entries.
 *	It is possible to hide Folders or Files from the List.
 *	Folders starting with a Dot can be stripped from the List.
 *	@package		folder
 *	@uses			Folder_RegexFilter
 *	@uses			Folder_Iterator
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			15.04.2008
 *	@version		0.6
 */
class Folder_Lister
{
	/**	@var		string		$path				Path to Folder */
	protected $path				= NULL;
	/**	@var		string		$pattern			Regular Expression to match with File Name */
	protected $pattern			= NULL;
	/**	@var		 bool		$showFiles			Flag: show Files */
	protected $showFiles		= TRUE;
	/**	@var		 bool		$showFolders		Flag: show Folders */
	protected $showFolders		= TRUE;
	/**	@var		 bool		$stripDotFolders	Flag: strip Folder with leading Dot */
	protected $stripDotFolders	= TRUE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@return		void
	 */
	public function __construct( $path )
	{
		$this->path	= $path;
	}

	/**
	 *	Returns List as FilterIterator.
	 *	@access		public
	 *	@return		FilterIterator
	 */
	public function getList()
	{
		if( $this->pattern )
		{
			import( 'de.ceus-media.folder.RegexFilter' );
			return new Folder_RegexFilter( $this->path, $this->pattern, $this->showFiles, $this->showFolders, $this->stripDotFolders );
		}
		import( 'de.ceus-media.folder.Iterator' );
		return new Folder_Iterator( $this->path, $this->showFiles, $this->showFolders, $this->stripDotFolders );
	}

	/**
	 *	Returns List of Files statically.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with File Name
	 *	@return		FilterIterator
	 */	
	public static function getFileList( $path, $pattern = NULL )
	{
		$index	= new Folder_Lister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( FALSE );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders statically.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Folder Name
	 *	@param		bool		$stripDotFolders	Flag: strip Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getFolderList( $path, $pattern = NULL, $stripDotFolders = TRUE )
	{
		$index	= new Folder_Lister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( FALSE );
		$index->showFolders( TRUE );
		$index->stripDotFolders( $stripDotFolders );
		return $index->getList();
	}

	/**
	 *	Returns List of Folders and Files statically.
	 *	@access		public
	 *	@param		string		$path				Path to Folder
	 *	@param		string		$pattern			RegEx Pattern to match with Entry Name
	 *	@param		bool		$stripDotFolders	Flag: strip Folders starting with a Dot
	 *	@return		FilterIterator
	 */	
	public static function getMixedList( $path, $pattern = NULL, $stripDotFolders = TRUE )
	{
		$index	= new Folder_Lister( $path );
		$index->setPattern( $pattern );
		$index->showFiles( TRUE );
		$index->showFolders( TRUE );
		$index->stripDotFolders( $stripDotFolders );
		return $index->getList();
	}

	/**
	 *	Sets Filter for Extensions.
	 *	Caution! Method overwrites Pattern if already set.
	 *	Caution! Flag 'showFiles' needs to be set to TRUE.
	 *	@access		public
	 *	@param		array		$extensions			List of allowed File Extensions.
	 *	@return		void
	 */
	public function setExtensions( $extensions = array() )
	{
		if( !is_array( $extensions ) )
			throw new InvalidArgumentException( 'Extensions must be given as Array.' );

		$pattern	= "";
		if( count( $extensions ) )
		{
			$extensions	= implode( "|", array_values( $extensions ) );
			$pattern	= '@\.'.$extensions.'$@i';
		}
		$this->pattern	= $pattern;
	}
	
	/**
	 *	Sets Filter Pattern.
	 *	Caution! Method overwrites Extension Filter if already set.
	 *	@access		public
	 *	@param		string		$pattern			RegEx Pattern for allowed Entries, eg. '@^A@' for all Entries starting with an A.
	 *	@return		void
	 */
	public function setPattern( $pattern )
	{
		$this->pattern	= $pattern;		
	}

	/**
	 *	Sets whether Files should be listed.
	 *	@access		public
	 *	@param		bool		$flag				Flag: show Files
	 *	@return		void
	 */
	public function showFiles( $flag )
	{
		$this->showFiles	= (bool) $flag;
	}

	/**
	 *	Sets whether Folders should be listed.
	 *	@access		public
	 *	@param		bool		$flag				Flag: show Folders
	 *	@return		void
	 */
	public function showFolders( $flag )
	{
		$this->showFolders	= (bool) $flag;
	}

	/**
	 *	Sets whether Folders starting with a Dot should be stripped from the List.
	 *	@access		public
	 *	@param		bool		$flag			Flag: strip Folders starting with a Dot
	 *	@return		void
	 */
	public function stripDotFolders( $flag )
	{
		$this->stripDotFolders	= (bool) $flag;
	}
}
?>