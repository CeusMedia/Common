<?php
/**
 *	Reader for Log Files containing JSON Serials.
 *	@package		file.log.js
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2008
 *	@version		0.1
 */
/**
 *	Reader for Log Files containing JSON Serials.
 *	@package		file.log.js
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2008
 *	@version		0.1
 */
class File_Log_JS_Reader
{
	/**	@var		string		$fileName		File Name of Log File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;	
	}

	/**
	 *	Returns List of parsed Lines.
	 *	@access		public
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 */
	public function getList( $reverse = FALSE, $limit = 0 )
	{
		return $this->read( $this->fileName, $reverse, $limit );
	}
	
	/**
	 *	Reads and returns List of parsed Lines statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		bool		$reverse		Flag: revert List
	 *	@param		int			$limit			Optional: limit List
	 *	@return		array
	 */
	public static function read( $fileName, $reverse = FALSE, $limit = 0 )
	{
		$data	= array();
		if( !file_exists( $fileName ) )
			throw new Exception( 'Log File "'.$fileName.'" is not existing.' );
		$lines		= file( $fileName );
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( !$line )
				continue;
			$data[]	= json_decode( $line, TRUE );
		}
		if( $reverse )
			$data	= array_reverse( $data );
		if( $limit )
			$data	= array_slice( $data, 0, $limit );
		return $data;
	}
}
?>