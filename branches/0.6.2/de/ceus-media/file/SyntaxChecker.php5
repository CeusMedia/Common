<?php
/**
 *	Checks Syntax of PHP Classes and Scripts.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.1
 */
/**
 *	Checks Syntax of PHP Classes and Scripts.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.05.2008
 *	@version		0.1
 */
class File_SyntaxChecker
{
	/**
	 *	Returns whether a PHP Class or Script has valid Syntax.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to check
	 *	@return		bool
	 */
	public function checkFile( $fileName )
	{
		$output = shell_exec('php -l "'.$fileName.'"');
		if( preg_match( "@^No syntax errors detected@", $output	) )
			return TRUE;
		$this->error	= $output;
		return FALSE;
	}
	
	/**
	 *	Returns Error of last File Syntax Check.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 *	Returns Error of last File Syntax Check in a shorter Format without File Name and Parser Prefix.
	 *	@access		public
	 *	@return		string
	 */
	public function getShortError()
	{
		$error	= array_shift( explode( "\n", trim( $this->error ) ) );
		$error	= preg_replace( "@^Parse error: (.*) in (.*) on (.*)$@i", "\\1 on \\3", $error );
		return $error;
	}
}
?>