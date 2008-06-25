<?php
import( 'de.ceus-media.file.RegexFilter' );
/**
 *	Class to find all Files with ToDos inside.
 *	@package		file
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			11.06.2008
 *	@version		0.1
 */
/**
 *	Class to find all Files with ToDos inside.
 *	@package		file
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			11.06.2008
 *	@version		0.1
 */
class File_TodoLister
{
	/**	@var		int			$count			Total Number of scanned Files */
	protected $count			= 0;
	/**	@var		int			$files			Number of found Files */
	protected $files			= 0;
	/**	@var		int			$found			Number of matching Files */
	protected $found			= 0;
	/**	@var		string		$extension		Default File Extension */
	protected $extension		= "php5";
	/**	@var		array		$extensions		Other File Extensions */
	protected $extensions		= array();
	/**	@var		array		$list			List of found Files */
	protected $list				= array();
	/**	@var		string		$pattern		Default Pattern */
	protected $pattern			= "@todo";
	/**	@var		array		$patterns		Other Patterns */
	protected $patterns			= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$additionalExtensions	Other File Extensions than 'php5'
	 *	@param		array		$additionalPatterns		Other Patterns than '@todo'
	 */
	public function __construct( $additionalExtensions = array(), $additionalPatterns = array() )
	{
		if( !is_array( $additionalExtensions ) )
			throw new InvalidArgumentException( 'Additional Extensions must be an Array.' );
		if( !is_array( $additionalPatterns ) )
			throw new InvalidArgumentException( 'Additional Patterns must be an Array.' );
		$this->extensions	= $additionalExtensions;
		$this->patterns		= $additionalPatterns;
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
	
	private function getExtendedPattern( $member = "pattern" )
	{
		$list1	= array( $this->$member );
		$list1	= array_merge( $list1, $this->{$member."s"} );
		$list2	= array();
		foreach( $list1 as $item )
			$list2[]	= str_replace( ".", "\.", $item );
		if( count( $list2 ) == 1 )
			$pattern	= array_pop( $list2 );
		else
			$pattern	= "(".implode( "|", $list2 ).")";
		if( $member == "extension" )
			$pattern	.= "$";
		$pattern	= "/".$pattern."/";
		return $pattern;
	}
	
	protected function getExtensionPattern()
	{
		return $this->getExtendedPattern( "extension" );
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

	/**
	 *	Returns Number of matching Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getFound()
	{
		return $this->found;
	}
	
	protected function getPattern()
	{
		return $this->getExtendedPattern();
	}
	
	/**
	 *	Returns Array of found Files.
	 *	@access		public
	 *	@param		bool		$full		Flag: Return Path Name, File Name and Content also
	 *	@return		array
	 */
	public function getList( $full = NULL )
	{
		if( $full )
			return $this->list;
		$list	= array();
		foreach( $this->list as $pathName => $fileData )
			$list[$pathName]	= $fileData['fileName'];
		return $list;
	}

	protected function getIndexIterator( $path, $filePattern, $contentPattern )
	{
		return new File_RegexFilter( $path, $filePattern, $contentPattern );
	}

	/**
	 *	Scans a Path for Files with Pattern.
	 *	@access		public
	 *	@return		int
	 */
	public function scan( $path )
	{
		$this->count	= 0;
		$this->found	= 0;
		$this->list		= array();
		$extensions		= $this->getExtensionPattern(); 
		$pattern		= $this->getPattern();
		$iterator		= $this->getIndexIterator( $path, $extensions, $pattern );
		foreach( $iterator as $entry )
		{
			$this->found++;
			$content	= file_get_contents( $entry->getPathname() );
			$this->list[$entry->getPathname()]	= array(
				'fileName'	=> $entry->getFilename(),
				'content'	=> $content,
			);
		}
		$this->files	= $finder->getFiles();
		$this->count	= $finder->getCount();
	}
}
?>