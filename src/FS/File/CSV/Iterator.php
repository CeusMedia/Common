<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\FS\File\CSV;

use InvalidArgumentException;
use Iterator as BaseIterator;
use RuntimeException;

/**
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Iterator implements BaseIterator
{
	public static int $maxRowSize		= 4096;

	protected const STATUS_NEW		= 0;
	protected const STATUS_OPENING	= 1;
	protected const STATUS_OPEN		= 2;

	protected $currentLine;
	protected int $currentNr			= 0;
	protected string $delimiter			= ',';
	protected string $enclosure			= '"';
	protected $filePath;
	protected $filePointer;
	protected $headers;
	protected int $status				= self::STATUS_NEW;
	protected bool $useHeaders			= FALSE;
	protected bool $verbose				= FALSE;

	/**
	 *	Constructor.
	 *	Sets all parameters but does not open the file. This will be done on the first interaction.
	 *	@access		public
	 *	@param		string			$filePath		CSV file
	 *	@param		boolean			$useHeaders		Flag: use first line to read row headers
	 *	@param		string|NULL		$delimiter		Delimiter sign
	 *	@param		string|NULL		$enclosure		Enclosure sign
	 *	@return		void
	 */
	public function __construct( string $filePath, bool $useHeaders = FALSE, ?string $delimiter = NULL, ?string $enclosure = NULL )
	{
		$this->filePath			= $filePath;
		$this->useHeaders		= $useHeaders;
		if( !is_null( $delimiter ) )
			$this->setDelimiter( $delimiter );
		if( !is_null( $enclosure ) )
			$this->setEnclosure( $enclosure );
	}

	/**
	 *	Returns the first or latest read CSV line as array.
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		array|NULL		First or latest read CSV line as array
	 */
	public function current(): ?array
	{
		if( $this->status !== self::STATUS_OPEN )
			$this->open();
		return $this->currentLine;
	}

	/**
	 *	Returns set delimiter sign.
	 *	@access		public
	 *	@return		string
	 */
	public function getDelimiter(): string
	{
		return $this->delimiter;
	}

	/**
	 *	Returns set enclosure sign.
	 *	@access		public
	 *	@return		string
	 */
	public function getEnclosure(): string
	{
		return $this->enclosure;
	}

	/**
	 *	Returns headers, if available. Empty array otherwise.
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		array
	 */
	public function getHeaders(): array
	{
		if( $this->status !== self::STATUS_OPEN )
			$this->open();
		if( !$this->useHeaders )
			return [];
		return $this->headers;
	}

	/**
	 *	Returns current line number as an index starting with 1.
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		integer		Current line number (starting with 1)
	 */
	public function key(): int
	{
		if( $this->status !== self::STATUS_OPEN )
			$this->open();
		return $this->currentNr;
	}

	/**
	 *	Goes to next line in CSV file.
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		void
	 */
	public function next(): void
	{
		$this->verbose && remark( ' - #'.$this->key().': next' );
		if( $this->status !== self::STATUS_OPEN )
			$this->open();
		$this->currentNr++;
		$this->currentLine	= $this->readCurrentLine();
	}

	/**
	 *	Start on top of the file (maybe again).
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind(): void
	{
		$this->verbose && remark( ' - #'.$this->key().': rewind' );
		if( $this->status === self::STATUS_NEW ){							//  file not opened yet
			$this->open();													//  open file
			return;															//  no need to rewind
		}
		rewind( $this->filePointer );
		$this->currentNr	= 0;
		$this->headers		= NULL;
		if( $this->useHeaders ){
			$this->headers	= $this->readCurrentLine();
			$this->verbose && remark( ' - #'.$this->key().': headers detected');
			$this->verbose && print_m( $this->headers );
			if( !is_array( $this->headers ) )
				$this->useHeaders	= FALSE;
		}
		$this->next();
	}

	/**
	 *	Set delimiter sign.
	 *	@access		public
	 *	@param		string		$delimiter		Sign between columns, default: , (Comma)
	 *	@return		self
	 */
	public function setDelimiter( string $delimiter ): self
	{
		if( strlen( $delimiter ) === 0 )
			throw new InvalidArgumentException( 'Delimiter cannot be empty' );
		$this->delimiter	= $delimiter;
		return $this;
	}

	/**
	 *	Set enclosure sign to wrap around values containing special signs, like the delimiter itself.
	 *	@access		public
	 *	@param		string		$enclosure		Sign to wrap around values containing special signs
	 *	@return		self
	 */
	public function setEnclosure( string $enclosure ): self
	{
		if( strlen( $enclosure ) === 0 )
			throw new InvalidArgumentException( 'Enclosure cannot be empty' );
		$this->enclosure	= $enclosure;
		return $this;
	}

	/**
	 *	Set verbosity.
	 *	@access		public
	 *	@param		boolean		$verbose		Flag: be verbose or not, default: no
	 *	@return		self
	 */
	public function setVerbose( bool $verbose ): self
	{
		$this->verbose	= $verbose;
		return $this;
	}

	/**
	 *	Indicates whether latest read CSV line is valid.
	 *	Opens file, if not done yet.
	 *	@access		public
	 *	@return		boolean
	 */
	public function valid(): bool
	{
		$this->verbose && remark( ' - #'.$this->key().': valid' );
		if( $this->status !== self::STATUS_OPEN )
			$this->open();
		if( !is_resource( $this->filePointer ) )
			return FALSE;
		return $this->currentLine !== FALSE && $this->currentLine !== NULL;
	}

	//  --  PROTECTED  --  //

	/**
	 *	Opens the file and tries to read the headers if enabled.
	 *	@access		protected
	 *	@throws		RuntimeException	If file is not existing or not readable
	 */
	protected function open(): void
	{
		$this->verbose && remark( ' - #'.$this->key().': open' );
		if( $this->status === self::STATUS_OPENING || $this->status === self::STATUS_OPEN )
			return;

		$this->verbose && remark( 'open: '.$this->filePath );
		$this->status = self::STATUS_OPENING;
		$this->filePointer	= @fopen( $this->filePath, 'r' );
		if( $this->filePointer === FALSE )
			throw new RuntimeException( 'File "'.$this->filePath.'" not existing and readable' );

		$this->rewind();
		$this->status = self::STATUS_OPEN;
	}

	protected function readCurrentLine(): ?array
	{
		$this->verbose && remark( ' - #'.$this->key().': readCurrentLine' );
		if( !is_resource( $this->filePointer ) )
			throw new RuntimeException( 'Lost connection to file pointer' );

		if( feof( $this->filePointer ) )
			return NULL;

 		$line	= fgets( $this->filePointer, self::$maxRowSize );
		while( strlen( trim( $line ) ) === 0 ){
			if( feof( $this->filePointer ) )
				return NULL;
		}
		if( $this->currentNr === 0 )
			$line	= $this->removeBOM( $line );
		$data	= str_getcsv( $line, $this->delimiter, $this->enclosure );

		$map	= [];
		foreach( $data as $key => $value ){
			$value	= trim( $value );
			$mapKey	= $key;
			if( $this->useHeaders && $this->headers && array_key_exists( $key, $this->headers ) )
				$mapKey	= $this->headers[$key];
			$map[$mapKey]	= $value;
		}
		return $map;
	}

	protected function removeBOM( string $line ): string
	{
		$bomSequences	= [
			'BOM_UTF8'		=> "\xEF\xBB\xBF",					//  UTF-8 BOM sequence
			'BOM_UTF16_BE'	=> "\xFE\xFF",						//  UTF-16 BE BOM sequence
			'BOM_UTF16_LE'	=> "\xFF\xFE",						//  UTF-16 LE BOM sequence
			'BOM_UTF32_BE'	=> "\x00\x00\xFE\xFF",				//  UTF-32 BE BOM sequence
			'BOM_UTF32_LE'	=> "\xFF\xFE\x00\x00",				//  UTF-32 LE BOM sequence
		];
		foreach( $bomSequences as $bomSequence ){
			$length	= strlen( $bomSequence );
			if( strncmp( $line, $bomSequence, $length ) === 0 )
				$line	= substr( $line, $length );
		}
		return $line;
	}
}
