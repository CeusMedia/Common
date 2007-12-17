<?php
import( 'de.ceus-media.file.list.Reader' );
import( 'de.ceus-media.file.File' );
/**
 *	A Class for reading and writing List Files.
 *	@package		file.list
 *	@extends		File_List_Reader
 *	@uses			File_Writer
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	A Class for reading and writing List Files.
 *	@package		file.list
 *	@extends		File_List_Reader
 *	@uses			File_Writer
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class File_List_Writer extends File_List_Reader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		parent::__construct( $fileName );
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Adds a String to the List.
	 *	@access		public
	 *	@param		string		$content		String to add
	 *	@return		void
	 */
	public function add( $content )
	{
		$this->list->add( $content );
		$this->write();
	}
	
	/**
	 *	Changes the content of a String in the List by its old content.
	 *	@access		public
	 *	@param		string		$content		old content of String
	 *	@param		string		$new			new content of String
	 *	@return		void
	 */
	public function change( $content, $new )
	{
		$this->list->change( $content, $new );
		$this->write();
	}
	
	/**
	 *	Removes a String in the List by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of the String
	 *	@return		void
	 */
	public function remove( $index )
	{
		$this->list->remove( $index );
		$this->write();
	}
	
	/**
	 *	Sets a String in the List by its Index..
	 *	@access		public
	 *	@param		int			$index			Index of the String
	 *	@param		string		$content		new content of the String
	 *	@return		void
	 */
	public function set( $index, $content )
	{
		$this->list->set( $index, $content );
		$this->write();
	}
	
	/**
	 *	Writes the current List to File.
	 *	@access		protected
	 *	@return		void
	 */
	protected function write()
	{
		$file	= new File_Writer( $this->fileName, 0777 );
		$file->writeArray( $this->toArray() );
	}
}
?>