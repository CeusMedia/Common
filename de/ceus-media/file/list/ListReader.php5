<?php
import( 'de.ceus-media.Object' );
import( 'de.ceus-media.adt.list.StringList' );
import( 'de.ceus-media.file.File' );
/**
 *	A Class for reading List Files.
 *	@package		file
 *	@extends		Object
 *	@uses			StringList
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	A Class for reading List Files.
 *	@package		file
 *	@extends		Object
 *	@uses			StringList
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class ListReader
{
	/**	@var	StringList		$_list			StringList */	
	var $_list;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		URI of list
	 *	@return		void
	 */
	function ListReader( $filename )
	{
		$this->_list = new StringList();
		$this->_comment_pattern = "^[#:;/*-]{1}";
		$this->_read ($filename);
	}
	
	/**
	 *	Returns the Index of a given String in the List.
	 *	@access		public
	 *	@param		string		$content		content of String
	 *	@return		int
	 */
	function getIndex( $content )
	{
		return $this->_list->getIndex( $content );	
	}
	
	/**
	 *	Returns the List.
	 *	@access		public
	 *	@return		void
	 */
	function getList()

	{
		return $this->toArray();
	}
	
	/**
	 *	Returns the Size of the List.
	 *	@access		public
	 *	@return		void
	 */
	function getSize()
	{
		return $this->_list->getSize();
	}

	/**
	 *	Returns the List as Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		return $this->_list->toArray();
	}

	/**
	 *	Reads the List.
	 *	@access		public
	 *	@param		string	filename		URI of list
	 *	@return		void
	 */
	function _read( $filename )
	{
		if( file_exists( $filename ) )
		{
			$file	= new File( $filename );
			$lines	= $file->readArray();
			foreach( $lines as $line )
				if( $line = trim( $line ) )
					if( !ereg( $this->_comment_pattern, $line ) )
						$this->_list->add( $line );
		}
		else
			trigger_error( "File '".$filename."' is not existing", E_USER_WARNING );
	}
}
?>