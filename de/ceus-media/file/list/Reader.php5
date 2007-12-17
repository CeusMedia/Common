<?php
import( 'de.ceus-media.Object' );
import( 'de.ceus-media.adt.list.StringList' );
import( 'de.ceus-media.file.File' );
/**
 *	A Class for reading List Files.
 *	@package		file.list
 *	@uses			StringList
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	A Class for reading List Files.
 *	@package		file.list
 *	@uses			StringList
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_List_Reader
{
	/**	@var		StringList	$list			StringList */	
	protected $list;
	/**	@var		string		$commentPattern	RegEx Pattern of Comments */	
	protected $commentPattern;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of list
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list = new StringList();
		$this->commentPattern = "^[#:;/*-]{1}";
		$this->read( $fileName );
	}
	
	/**
	 *	Returns the Index of a given String in the List.
	 *	@access		public
	 *	@param		string		$content		content of String
	 *	@return		int
	 */
	public function getIndex( $content )
	{
		return $this->list->getIndex( $content );	
	}
	
	/**
	 *	Returns the List.
	 *	@access		public
	 *	@return		void
	 */
	public function getList()
	{
		return $this->toArray();
	}
	
	/**
	 *	Returns the Size of the List.
	 *	@access		public
	 *	@return		void
	 */
	public function getSize()
	{
		return $this->list->getSize();
	}

	/**
	 *	Returns the List as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->list->toArray();
	}

	/**
	 *	Reads the List.
	 *	@access		protected
	 *	@param		string	fileName		URI of list
	 *	@return		void
	 */
	protected function read( $fileName )
	{
		if( file_exists( $fileName ) )
		{
			$file	= new File_Reader( $fileName );
			$lines	= $file->readArray();
			foreach( $lines as $line )
				if( $line = trim( $line ) )
					if( !ereg( $this->commentPattern, $line ) )
						$this->list->add( $line );
		}
		else
			trigger_error( "File '".$fileName."' is not existing", E_USER_WARNING );
	}
}
?>