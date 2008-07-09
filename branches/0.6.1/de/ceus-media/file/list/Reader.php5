<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	A Class for reading List Files.
 *	@package		file.list
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	A Class for reading List Files.
 *	@package		file.list
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_List_Reader
{
	/**	@var		array		$list			List */	
	protected $list						= array();
	/**	@var		string		$commentPattern	RegEx Pattern of Comments */	
	protected static $commentPattern	= "^[#:;/*-]{1}";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list	= $this->read( $fileName );
	}

	/**
	 *	Returns current List as String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return "{".implode( ", ", $this->list )."}";
	}
	
	/**
	 *	Returns the Index of a given Item in current List.
	 *	@access		public
	 *	@param		string		$item			Item to get Index for
	 *	@return		int
	 */
	public function getIndex( $item )
	{
		$index	= array_search( $item, $this->list );
		if( $index === FALSE )
			throw new DomainException( 'Item "'.$item.'" is not in List.' );
		return $index;
	}

	/**
	 *	Returns current List.
	 *	@access		public
	 *	@return		void
	 */
	public function getList()
	{
		return $this->list;
	}
	
	/**
	 *	Returns the Size of current List.
	 *	@access		public
	 *	@return		void
	 */
	public function getSize()
	{
		return count( $this->list );
	}

	/**
	 *	Indicates whether an Item is in current List.
	 *	@access		public
	 *	@param		string		$item			Item to check
	 *	@return		bool
	 */
	public function hasItem( $item )
	{
		return in_array( $item, $this->list );	
	}

	/**
	 *	Reads List File.
	 *	@access		public
	 *	@param		string	fileName		URI of list
	 *	@return		void
	 */
	public static function read( $fileName )
	{
		$list	= array();
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );
		$reader	= new File_Reader( $fileName );
		$lines	= $reader->readArray();
		foreach( $lines as $line )
			if( $line = trim( $line ) )
				if( !ereg( self::$commentPattern, $line ) )
					$list[]	= $line;
		return $list;
	}
}
?>