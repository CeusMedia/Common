<?php
/**
 *	@category		cmClasses
 *	@package		File.CSV
 *	@author			mortanon@gmail.com
 *	@link			http://uk.php.net/manual/en/function.fgetcsv.php
 */
/**
 *	@category		cmClasses
 *	@package		File.CSV
 *	@author			mortanon@gmail.com
 *	@link			http://uk.php.net/manual/en/function.fgetcsv.php
 */
class File_CSV_Iterator implements Iterator
{
	const ROW_SIZE = 4096;

	/**	@var	resource	$filePointer		The pointer to the cvs file. */
	protected $filePointer	= NULL;

	/**	@var	integer		$rowCounter			The row counter. */
	protected $rowCounter		= 0;

	/**	@var	string		$delimiter			The delimiter for the csv file. */
	protected $delimiter		= ",";

	/**	@var	string		$enclosure			The delimiter for the csv file. */
	protected $enclosure		= '"';

	/**
	 *	Constructor.
	 *	It tries to open the csv file and throws an exception on failure.
	 *	@access		public
	 *	@param		string		$file			CSV file
	 *	@param		string		$delimiter		Delimiter sign
	 *	@param		string		$enclosure		Enclosure sign
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( $file, $delimiter = NULL, $enclosure = NULL )
	{
		if( !is_null( $delimiter ) )
			$this->delimiter	= $delimiter;
		if( !is_null( $enclosure ) )
			$this->enclosure	= $enclosure;
		$this->filePointer	= @fopen( $file, 'r' );
		if( $this->filePointer === FALSE )
			throw new RuntimeException( 'File "'.$file.'" not existing and readable' );
	}

	/**
	 *	Resets the file pointer.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->rowCounter	= 0;
		rewind( $this->filePointer );
	}

	/**
	 *	Returns the current csv row as a 2 dimensional array.
	 *	@access		public
	 *	@return		array		The current csv row as a 2 dimensional array
	 */
	public function current()
	{
		return $this->currentElement;
	}

	/**
	 *	Returns the current row number.
	 *	@access		public
	 *	@return		integer		The current row number
	 */
	public function key()
	{
		return $this->rowCounter;
	}

	/**
	 *	Indicates whether the end of file is not reached.
	 *	@access		public
	 *	@return		boolean		FALSE on EOF reached, TRUE otherwise
	 */
	public function next()
	{
		if( is_resource( $this->filePointer ) )
		{
			if( !feof( $this->filePointer ) )
			{
				$data = fgetcsv(
					$this->filePointer,
					self::ROW_SIZE,
					$this->delimiter,
					$this->enclosure
				);
				if( $data )
				{
					$this->currentElement	= $data;	
					$this->rowCounter++;
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	/**
	 *	Indicates whether the next row is a valid row.
	 *	@access		public
	 *	@return		boolean
	 */
	public function valid()
	{
		if( $this->next() )
			return TRUE;
		if( is_resource( $this->filePointer ) )
			fclose( $this->filePointer );
		return FALSE;
	}
}
?>