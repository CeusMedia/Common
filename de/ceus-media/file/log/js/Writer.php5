<?php
/**
 *	Writer for Log Files containing JSON Serials.
 *	@package		file.log.js
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2008
 *	@version		0.1
 */
/**
 *	Writer for Log Files containing JSON Serials.
 *	@package		file.log.js
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2008
 *	@version		0.1
 */
class File_Log_JS_Writer
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
	 *	Adds Data to Log File.
	 *	@access		public
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public function note( $data )
	{
		return self::noteData( $this->fileName, $data );
	}

	/**
	 *	Adds Data to Log File statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@param		array		$data			Data Array to note
	 *	@return		bool
	 */
	public static function noteData( $fileName, $data )
	{
		$data	= array_merge(
			array(
				'timestamp' => time()
			),
			$data
		);
		$serial	= json_encode( $data )."\n";
		if( !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
		return error_log( $serial, 3, $fileName );
	}
}
?>