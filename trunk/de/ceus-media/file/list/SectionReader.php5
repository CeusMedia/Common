<?php
import ("de.ceus-media.file.Reader");
/**
 *	A Class for reading Section List Files.
 *	@package		file.list
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	A Class for reading Section List Files.
 *	@package		file.list
 *	@uses			File_Reader
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_List_SectionReader
{
	protected $list	= array();
	public static $commentPattern	= '^[#|-|*|:|;]{1}';
	public static $sectionPattern	= '^([){1}([a-z0-9_=.,:;# ])+(]){1}$';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list	= self::load( $fileName );
	}

	public function read()
	{
		return $this->list;
	}
	
	/**
	 *	Reads the List.
	 *	@access		public
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		array
	 */
	public static function load( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );

		$reader	= new File_Reader( $fileName );
		$lines	= $reader->readArray();
		
		$list	= array();
		foreach( $lines as $line )
		{
			$line = trim( $line );
			if( !$line )
				continue;
			if( ereg( self::$commentPattern, $line ) )
				continue;
					
			if( ereg( self::$sectionPattern, $line ) )
			{
				$section = substr( $line, 1, -1 );
				if( !isset( $list[$section] ) )
					$list[$section]	= array();
			}
			else if( $section )
			{
				$list[$section][]	= $line;
			}
		}
		return $list;
	}
}
?>