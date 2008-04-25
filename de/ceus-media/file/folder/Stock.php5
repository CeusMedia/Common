<?php
import ("de.ceus-media.file.Reader");
import ("de.ceus-media.file.Writer");
import ("de.ceus-media.file.folder.Folder");
/**
 *	Stock to store objects.
 *	@package	file
 *	@uses		File_Reader
 *	@uses		File_Writer
 *	@uses		Folder
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version	0.4
 */
/**
 *	Stock to store objects.
 *	@package	file
 *	@uses		File_Reader
 *	@uses		File_Writer
 *	@uses		Folder
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version	0.4
 */
class Stock
{
	/**	@var	string		$stock			Stock directory */
 	protected $stock;
	/**	@var	string		$index			Index file of the stock */
	protected $index;
	/**	@var	array		$keys			List of object keys in the stock */
	protected $keys		= array ();
	/**	@var	array		$objects		List of objects in the stock */
	protected $objects	= array ();
	/**	@var	array		$deleted		List of 'to-be-deleted' object */
	protected $deleted	= array ();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$stockName		name of stock an stock directory
	 *	@param		string		$stockPath		path to stock
	 *	@param		string		$index			name of stock index file
	 *	@return		void
	 */
	public function __construct( $stockName, $stockPath = "", $index = "stock.idx" )
	{
		$this->stock = $stockPath.$stockName."/", true;
		$this->index = $stockPath.$stockName."/".$index;
		if( !$this->index->exists() )
			$this->saveIndex();
		$this->keys =& $this->unpickle( $this->index );
	}

	/**
	 *	Adds an object to the object key list and objects list.
	 *	@access		public
	 *	@param		string		$key		object key
	 *	@param		object		$object		object to be stored in the stock
	 *	@param		bool		$overwrite	overwrite existing objects
	 *	@return		bool
	 */
	public function add( $key, $object, $overwrite = false )
	{
		if( !$this->has( $key ) )
		{
			$this->keys[] = $key;
			$this->saveObject( $key, $object );
			$this->saveIndex();
			return true;
		}
		else if( $overwrite )
			return $this->saveObject( $key, $object );
		else
			throw new InvalidArgumentException( 'An Object with Key "'.$key.'" is already in Stock.' );
		return false;
	}
	
	/**
	 *	Deletes whole Stock with object files, stock index and stock directory.
	 *	@access		public
	 *	@return		bool
	 */
	public function destroy()
	{
		$this->index->remove();
		foreach( $this->keys as $key )
		{
			$fileName = $this->stock->getPath()."/".$key;
			@unlink( $fileName );
		}
		return $this->stock->remove();
	}

	/**
	 *	Returns object calles from the stock by object key.
	 *
	 *	@access		public
	 *	@param		string		$key		object key
	 *	@return		object
	 */
	public function & get( $key )
	{
		if( $this->has( $key ) )
		{
			$fileName	= $this->stock->getPath()."/".$key;
			return $this->unpickle( $fileName );
		}
		else
		{
			return false;
		}
	}

	/**
	 *	Returns the object key list as array.
	 *
	 *	@access		public
	 *	@return		array
	 */
	public function getKeys()
	{
		return $this->keys;
	}

	/**
	 *	Indicates wheter a object file is existing in the stock.
	 *
	 *	@access		public
	 *	@return		bool
	 */
	public function has( $key )
	{
		return in_array( $key, $this->keys );
	}

	/**
	 *	Writes object into stock by serializing it.
	 *	@access		protected
	 *	@param		string		$fileName	Name of File in Stock
	 *	@param		object		$object		object to be stored in the stock
	 *	@return		void
	 */
	protected function pickle( $fileName, &$object )
	{
		@unlink( $fileName );
		return File_Writer::save( serialize( $object ) );
	}

	/**
	 *	Stores an object into stock, object list and object key list.
	 *
	 *	@access		public
	 *	@param		File		$file		file in the stock
	 *	@param		object		$object		object to be stored in the stock
	 *	@return		bool
	 */
	public function put( $key, $object )
	{
		if( $this->has( $key ) )
		{
			return $this->saveObject( $key, $object );
		}
		else
		{
			return $this->add( $key, $object );
		}
	}

	/**
	 *	Removes an object from object list and object key list.
	 *	@access		public
	 *	@param		string		$key		object key
	 *	@return		void
	 */
	public function remove( $key )
	{
		unset( $this->keys[array_search( $key, $this->keys )] );
		$fileName	= $this->stock->getPath()."/".$key;
		@unlink( $fileName );
		$this->saveIndex();
	}

	/**
	 *	Writes list of object keys into the stock index file.
	 *	@access		protected
	 *	@return		void
	 */
	protected function saveIndex()
	{
		$this->pickle( $this->index, $this->keys );
	}

	/**
	 *	Writes object into stock after creating a file by object key.
	 *	@access		protected
	 *	@param		string	$key		object key
	 *	@param		object	$object		object to be stored in the stock
	 *	@return		void
	 */
	protected function saveObject( $key, &$object )
	{
		$fileName	= $this->stock->getPath()."/".$key;
		return $this->pickle( $fileName, $object );
	}

	/**
	 *	Recreates object from a serialized file in the stock.
	 *	@access		protected
	 *	@param		string		$fileName		Name of File in Stock
	 *	@return		object
	 */
	protected function & unpickle( $fileName )
	{
		return unserialize( File_Reader::load( $fileName ) );
	}
}
?>