<?php
import( 'de.ceus-media.file.File' );
/**
 *	Parser for reading Log-Files written by LogFile Class.
 *	@package		file
 *	@subpackage		log
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2005
 *	@version		0.4
 */
/**
 *	Parser for reading Log-Files written by LogFile Class.
 *	@package		file
 *	@subpackage		log
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.08.2005
 *	@version		0.4
 *	@todo			finish Implementation
 *	@todo			Code Documentation
 */
class LogParser
{
	/**
	 *	Parses an array of lines and returns an associative array with information.
	 *	@access		public
	 *	@param		array		$lines		Array of lines of a LogFile
	 *	@return		array
	 */
	public function parseArray ( $lines )
	{	
		$log		= array( 'lines'	=> count( $lines ) );
		foreach( $lines as $line )
		{
			$row	= array();
			$data	= $this->readLine( $line );
			foreach( $data as $key => $value )
				$row[$key]	= $value;
			$log['entries'][]	= $row;
		}
		return $log;
	}

	/**
	 *	Parses an array of lines and returns an associative array with information.
	 *	@access		public
	 *	@param		array		$lines		Array of lines of a LogFile
	 *	@return		array
	 */
	public function parseFile( $filename )
	{
		$file		= new File( $filename );
		$lines	= $file->readArray();
		return $this->parseArray( $lines );
	}

	/**
	 *	Parses a lines and returns an associative array with information.
	 *	@access		protected
	 *	@param		string		$string		Lines of a LogFile
	 *	@return		array
	 */
	protected function readLine( $string )
	{
		// !!! seems to be unfinished. !!! //
		return array( 'line' => $string );
	}
}
?>