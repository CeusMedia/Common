<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\FS\File\CSV;

use Countable;
use RuntimeException;

/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Reader implements Countable
{
	public static $maxRowSize	= 4096;

	protected $iterator;

	/**
	 *	Constructor.
	 *	It tries to open the csv file and throws an exception on failure.
	 *	@access		public
	 *	@param		string			$filePath		CSV file
	 *	@param		boolean			$useHeaders		Flag: use first line to read row headers
	 *	@param		string|NULL		$delimiter		Delimiter sign
	 *	@param		string|NULL		$enclosure		Enclosure sign
	 *	@return		void
	 *	@throws		RuntimeException
	 */
	public function __construct( string $filePath, bool $useHeaders = FALSE, ?string $delimiter = NULL, ?string $enclosure = NULL )
	{
		Iterator::$maxRowSize = self::$maxRowSize;
		$this->iterator	= new Iterator( $filePath, $useHeaders, $delimiter, $enclosure );
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
	 */
	public function getHeaders(): array
	{
		return $this->iterator->getHeaders();
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
}
