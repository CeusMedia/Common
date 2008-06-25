<?php
import( 'de.ceus-media.file.Writer' );
/**
 *	A Class for reading and writing List Files.
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	A Class for reading and writing List Files.
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_List_Writer
{
	/**	@var		array		$list			List **/
	protected $list				= array();
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
			throw new DomainException( 'Item "'.$item.'" is already existing. See Option "force".' );
		$this->list[]	= $item;
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
		if( !in_array( $item, $this->list ) )
			throw new DomainException( 'Item "'.$item.'" is not existing.' );
		$index	= array_search( $item, $this->list );
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
			throw new DomainException( 'Item with Index '.$index.' is not existing.' );
		unset( $this->list[$index] );
		return $this->write();
	}
	
	/**
	 *	Saves a List to File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@param		array		$list			List to save
	 *	@param		string		$mode			UNIX rights for chmod()
	 *	@param		string		$user			User Name for chown()
	 *	@param		string		$group			Group Name for chgrp()
	 *	@return		bool
	 */
	public static function save( $fileName, $list, $mode = 0755, $user = NULL, $group = NULL )
	{
		$file	= new File_Writer( $fileName, $mode, $user, $group );
		return $file->writeArray( $list ) !== FALSE;
	}
	
	/**
	 *	Writes the current List to File.
	 *	@access		protected
	 *	@param		string		$mode			UNIX rights for chmod()
	 *	@param		string		$user			User Name for chown()
	 *	@param		string		$group			Group Name for chgrp()
	 *	@return		bool
	 */
	protected function write( $mode = 0755, $user = NULL, $group = NULL )
	{
		return $this->save( $this->fileName, $this->list, $mode, $user, $group );
	}
}
?>