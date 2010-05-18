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

	/**
	 *	The pointer to the cvs file.
	 *	@var resource
	 *	@access private
	 */
	private $filePointer = NULL;

	/**
	 *	The current element, which will
	 *	be returned on each iteration.
	 *	@var array
	 *	@access private
	 */
	private $currentElement = NULL;

	/**
	 * The row counter.
	 * @var int
	 * @access private
	 */
	private $rowCounter = NULL;

	/**
	 * The delimiter for the csv file.
	 * @var str
	 * @access private
	 */
	private $delimiter	= ",";

	/**
	 * This is the constructor.It try to open the csv file.The method throws an exception
	 * on failure.
	 *
	 * @access public
	 * @param str $file The csv file.
	 * @param str $delimiter The delimiter.
	 *
	 * @throws Exception
	 */
	public function __construct( $file, $delimiter = NULL )
	{
		if( $delimiter )
			$this->delimiter	= $delimiter;
		$this->filePointer	= @fopen( $file, 'r' );
		if( $this->filePointer === false )
			throw new Exception( 'The file "'.$file.'" cannot be read.' );
	}

	/**
	 * This method resets the file pointer.
	 *
	 * @access public
	 */
	public function rewind()
	{
		$this->rowCounter	= 0;
		rewind( $this->filePointer );
	}

	/**
	 * This method returns the current csv row as a 2 dimensional array
	 *
	 * @access public
	 * @return array The current csv row as a 2 dimensional array
	 */
	public function current()
	{
		return $this->currentElement;
	}

	/**
	 * This method returns the current row number.
	 *
	 * @access public
	 * @return int The current row number
	 */
	public function key()
	{
		return $this->rowCounter;
	}

	/**
	 * This method checks if the end of file is reached.
	 *
	 * @access public
	 * @return boolean Returns true on EOF reached, false otherwise.
	 */
	public function next()
	{
		if( is_resource( $this->filePointer ) )
		{
			if( !feof( $this->filePointer ) )
			{
				$this->rowCounter++;
				$data = fgetcsv( $this->filePointer, self::ROW_SIZE, $this->delimiter );
				if( $data )
				{
					$this->currentElement	= $data;	
					return $this->current();
				}
			}
		}
		return false;
	}

	/**
	 * This method checks if the next row is a valid row.
	 *
	 * @access public
	 * @return boolean If the next row is a valid row.
	 */
	public function valid()
	{
		if( !$this->next() )
		{
			if( is_resource( $this->filePointer ) )
			{
				fclose( $this->filePointer );
			}
			return false;
		}
		return true;
	}
}
?>