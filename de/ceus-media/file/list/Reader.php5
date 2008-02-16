<?php
import( 'de.ceus-media.Object' );
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
	/**	@var		StringList	$list			StringList */	
	protected $list;
	/**	@var		string		$commentPattern	RegEx Pattern of Comments */	
	protected static $commentPattern	= "^[#:;/*-]{1}";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of list
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list = array();
		$this->list	= self::read( $fileName );
	}
	
	/**
	 *	Returns the Index of a given String in the List.
	 *	@access		public
	 *	@param		string		$content		content of String
	 *	@return		int
	 */
	public function getIndex( $content )
	{
		return array_search( $this->list, $content );	
	}
	
	/**
	 *	Returns the List.
	 *	@access		public
	 *	@return		void
	 */
	public function getList()
	{
		return $this->list;
	}
	
	/**
	 *	Returns the Size of the List.
	 *	@access		public
	 *	@return		void
	 */
	public function getSize()
	{
		return count( $this->list );
	}

	/**
	 *	Returns the List as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->list;
	}

	/**
	 *	Reads the List.
	 *	@access		public
	 *	@param		string	fileName		URI of list
	 *	@return		void
	 */
	public static function read( $fileName )
	{
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