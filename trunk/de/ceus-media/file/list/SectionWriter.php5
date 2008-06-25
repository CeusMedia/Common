<?php
import ("de.ceus-media.file.Writer");
/**
 *	Writer for Section List.
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Writer for Section List.
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class File_List_SectionWriter
{
	/**	@var		string		$fileName		File Name of Section List */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Section List
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Writes Section List.
	 *	@access		public
	 *	@param		array		$list			Section List to write
	 *	@return		void
	 */
	public function write( $list )
	{
		return self::save( $this->fileName, $list );
	}
	
	/**
	 *	Saves a Section List to a File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Section List
	 *	@param		array		$list			Section List to write
	 *	@return		void
	 */
	public static function save( $fileName, $list )
	{
		$lines = array();
		foreach( $list as $section => $data )
		{
			if( count( $lines ) )
				$lines[] = "";
			$lines[] = "[".$section."]";
			foreach( $data as $entry )
				$lines[] = $entry;
		}
		$writer	= new File_Writer( $fileName, 0755 );
		return $writer->writeArray( $lines );
	}
}
?>