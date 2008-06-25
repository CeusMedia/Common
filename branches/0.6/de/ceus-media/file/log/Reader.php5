<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.11.2007
 *	@version		0.6
 */
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.11.2007
 *	@version		0.6
 */
class File_Log_Reader
{
	/**	@var		string		$fileName		URI of file with absolute path */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Reads a Log File and returns Lines.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public static function load( $fileName, $offset = NULL, $limit = NULL )
	{
		$file	= new File_Reader( $fileName );
		$lines	= $file->readArray();
		if( $offset !== NULL && $limit !== NULL && (int) $limit !==  0 )
			$lines	= array_slice( $lines, abs( (int) $offset ), (int) $limit );
		else if( $offset !== NULL && (int) $offset !== 0 )
			$lines	= array_slice( $lines, (int) $offset );
		return $lines;
	}

	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public function read( $offset = NULL, $limit = NULL )
	{
		return $this->load( $this->fileName, $offset, $limit );
	}
}
?>