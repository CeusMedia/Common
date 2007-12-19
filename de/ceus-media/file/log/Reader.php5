<?php
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
	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@param		int			$offset		Offset from Start or End
	 *	@param		int			$limit		Amount of Entries to return
	 *	@return		array
	 */
	public static function read( $uri, $offset = 0, $limit = 0)
	{
		$file	= new File_Reader( $uri );
		$lines	= $file->readArray();
		if( $offset && $limit )
			$lines	= array_slice( $lines, $offset, $limit );
		else if( $offset )
			$lines	= array_slice( $lines, $offset );
		return $lines;
	}
}
?>