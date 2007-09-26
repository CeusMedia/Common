<?php
import ("de.ceus-media.file.File");
/**
 *	@package		file
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo			Code Documentation
 */
/**
 *	@todo		Code Documentation
 */
class MatrixReader
{
//	/**	@var		string	_filename		URI of file with absolute path */
//	var $_filename;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		URI of file
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Create a file and sets Rights, Owner and Group.
	 *	@access		public
	 *	@param		string	mod		UNIX rights for chmod()
	 *	@param		string	user		user name for chown()
	 *	@param		string	user		group name for chgrp()
	 *	@return		void
	 */
	function read ( $filename )
	{
		$file = new File( $filename );
		$lines = $file->readArray();
		$head = array_shift( $lines);
		$parts = explode( "\t", $head );
		foreach( $parts as $part)
			if( trim( $part ) )
				$cols[] = $part;
		foreach( $lines as $line )
		{
			$parts = explode( "\t", $line );
			$row = array_shift( $parts );
			$rows[] = $row;
			reset( $cols );
			foreach( $cols as $col)
			{
				$v[$row][$col] = array_shift( $parts );
			}
		}
		return $v;
	}
	
	function write( $filename,  $array )
	{
		$cols = array();
		$lines = array();
		foreach( $array as $row => $data )
		{
			$line = array();
			foreach( $data as $col => $value )
			{
				if( count( $cols ) < count( $data ) )
					$cols[] = $col;
				$line[] = $value;
			}
			$lines[] = $row."\t".implode( "\t", $line );
		}
		$lines = array_merge( array( "\t".implode( "\t", $cols )), $lines );
		$file = new File( $filename );
		$file->writeArray( $lines);
	}
}
?>