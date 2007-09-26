<?php
import( 'de.ceus-media.file.list.ListReader' );
import( 'de.ceus-media.file.File' );
/**
 *	A Class for reading and writing List Files.
 *	@package		file
 *	@extends		ListReader
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	A Class for reading and writing List Files.
 *	@package		file
 *	@extends		ListReader
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class ListWriter extends ListReader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $filename )
	{
		parent::__construct( $filename );
		$this->_filename	= $filename;
	}
	
	/**
	 *	Adds a String to the List.
	 *	@access		public
	 *	@param		string		$content		String to add
	 *	@return		void
	 */
	function add( $content )
	{
		$this->_list->add( $content );
		$this->_write();
	}
	
	/**
	 *	Changes the content of a String in the List by its old content.
	 *	@access		public
	 *	@param		string		$content		old content of String
	 *	@param		string		$new			new content of String
	 *	@return		void
	 */
	function change( $content, $new )
	{
		$this->_list->change( $content, $new );
		$this->_write();
	}
	
	/**
	 *	Removes a String in the List by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of the String
	 *	@return		void
	 */
	function remove( $index )
	{
		$this->_list->remove( $index );
		$this->_write();
	}
	
	/**
	 *	Sets a String in the List by its Index..
	 *	@access		public
	 *	@param		int			$index			Index of the String
	 *	@param		string		$content		new content of the String
	 *	@return		void
	 */
	function set( $index, $content )
	{
		$this->_list->set( $index, $content );
		$this->_write();
	}
	
	/**
	 *	Writes the current List to File.
	 *	@access		public
	 *	@return		void
	 */
	function _write()
	{
		$file	= new File( $this->_filename, 0777 );
		$file->writeArray( $this->toArray() );
	}
}
?>