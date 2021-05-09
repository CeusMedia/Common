<?php
/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FS_File_CSV_Reader implements Countable
{
	public static $maxRowSize	= 4096;

	protected $iterator;

	/**
	 *	Constructor.
	 *	It tries to open the csv file and throws an exception on failure.
	 *	@access		public
	 *	@param		string		$filePath		CSV file
	 *	@param		boolean		$useHeaders		Flag: use first line to read row headers
	 *	@param		string		$delimiter		Delimiter sign
	 *	@param		string		$enclosure		Enclosure sign
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	public function __construct( $filePath, $useHeaders = FALSE, $delimiter = NULL, $enclosure = NULL )
	{
		FS_File_CSV_Iterator::$maxRowSize = self::$maxRowSize;
		$this->iterator	= new FS_File_CSV_Iterator( $filePath, $useHeaders, $delimiter, $enclosure );
	}

    /**
	 *  Returns the count of data rows.
	 *  @access		public
	 *  @return		int
	 */
	public function count(): int
	{
		$counter	= 0;
		$this->iterator->rewind();
		while( $this->iterator->valid() ){
			$counter++;
			$this->iterator->next();
		}
		return $counter;
	}

	/**
	 *	Returns headers, if available. Empty array otherwise.
	 *	@access		public
	 *	@return		array
	 *	@deprecated	Use FS_File_CSV_Reader::getHeaders()
	 *	@todo		Remove in version 0.9.0
	 */
	public function getColumnHeaders(): array
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.8.0' )
			->setExceptionVersion( '0.8.8.4' )
			->message( 'Use FS_File_CSV_Reader::getHeaders() instead' );
		return $this->iterator->getHeaders();
	}

	/**
	 *	Returns headers, if available. Empty array otherwise.
	 *	@access		public
	 *	@return		array
	 */
	public function getHeaders(): array
	{
		return $this->iterator->getHeaders();
	}

    /**
	 *  Returns the count of data rows.
	 *  @access		public
	 *  @return		int
	 *	@deprecated	Use FS_File_CSV_Reader::count()
	 *	@todo		Remove in version 0.9.0
	 */
	public function getRowCount()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.8.0' )
			->setExceptionVersion( '0.8.8.4' )
			->message( 'Use FS_File_CSV_Reader::count() instead' );
		return $this->count();
	}

	/**
	 *	Set verbosity.
	 *	@access		public
	 *	@param		boolean		$verbose		Flag: be verbose or not, default: no
	 *	@return		self
	 */
	public function setVerbose( bool $verbose ): self
	{
		$this->iterator->setVerbose( $verbose );
		return $this;
	}

	/**
	 *	Returns parse data as array.
	 *	Array key will be available header (if available) or incrementing integers starting with 0.
	 *	@return		array
	 */
	public function toArray(): array
	{
		$list	= [];
		$this->iterator->rewind();
		while( $this->iterator->valid() ){
			$list[]	= $this->iterator->current();
			$this->iterator->next();
		}
		return $list;
	}

	/**
	 *	Returns parse data as array.
	 *	Array key will be available header (if available) or incrementing integers starting with 0.
	 *	@return		array
	 *	@deprecated	Use FS_File_CSV_Reader::toArray()
	 *	@todo		Remove in version 0.9.0
	 */
	public function toAssocArray(): array
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.8.0' )
			->setExceptionVersion( '0.8.8.4' )
			->message( 'Use FS_File_CSV_Reader::toArray() instead' );
		return $this->toArray();
	}
}
