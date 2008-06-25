<?php
import( 'de.ceus-media.file.list.Reader' );
import( 'de.ceus-media.file.list.Writer' );
/**
 *	Editor for List Files.
 *	@package		file.list
 *	@extends		File_List_Reader
 *	@uses			File_List_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.04.2008
 *	@version		0.6
 */
/**
 *	Editor for List Files.
 *	@package		file.list
 *	@extends		File_List_Reader
 *	@uses			File_List_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.04.2008
 *	@version		0.6
 */
class File_List_Editor extends File_List_Reader
{
	/**	@var		string		$fileName		File Name of List, absolute or relative URI **/
	protected $fileName;
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		parent::__construct( $fileName );
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Adds an Item to the current List.
	 *	@access		public
	 *	@param		string		$item			Item to add
	 *	@param		bool		$force			Flag: force overwriting
	 *	@return		void
	 */
	public function add( $item, $force = FALSE )
	{
		if( in_array( $item, $this->list ) && !$force )
			throw new DomainException( 'List Item "'.$item.'" is already existing. See Option "force".' );
		$this->list[]	= $item;
		return $this->write();
	}

	/**
	 *	Edits an existing Item of current List.
	 *	@access		public
	 *	@param		int			$oldItem		Item to replace
	 *	@param		int			$newItem		Item to set instead
	 *	@return		bool
	 */
	public function edit( $oldItem, $newItem )
	{
		$index	= $this->getIndex( $oldItem );
		$this->list[$index]	= $newItem;
		return $this->write();
	}

	/**
	 *	Removes an Item from current List.
	 *	@access		public
	 *	@param		int			$item			Item to remove
	 *	@return		bool
	 */
	public function remove( $item )
	{
		$index	= $this->getIndex( $item );
		unset( $this->list[$index] );
		return $this->write();
	}
	
	/**
	 *	Removes an Item from current List by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of Item
	 *	@return		bool
	 */
	public function removeIndex( $index )
	{
		if( !isset( $this->list[$index] ) )
			throw new DomainException( 'List Item with Index '.$index.' is not existing.' );
		unset( $this->list[$index] );
		return $this->write();
	}
	
	/**
	 *	Saves current List to File.
	 *	@access		protected
	 *	@param		string		$mode			UNIX rights for chmod()
	 *	@param		string		$user			User Name for chown()
	 *	@param		string		$group			Group Name for chgrp()
	 *	@return		bool
	 */
	protected function write( $mode = 0755, $user = NULL, $group = NULL )
	{
		return File_List_Writer::save( $this->fileName, $this->list, $mode, $user, $group );
	}
}
?>