<?php
import ("de.ceus-media.file.Writer");
/**
 *	Writer for Section List.
 *	@package		file
 *	@subpackage		list
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Writer for Section List.
 *	@package		file
 *	@subpackage		list
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class SectionListWriter
{
	/**	@var		string		$fileName		URI of Section List */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of Section List
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Writes Section List.
	 *	@access		public
	 *	@param		SectionList	section_list	Section List to write
	 *	@return		void
	 */
	public function write( $section_list )
	{
		$lines = array();
		foreach( $section_list->toArray() as $section => $data )
		{
			if( count( $lines ) )
				$lines[] = "";
			$lines[] = "[".$section."]";
			foreach( $data as $entry )
				$lines[] = $entry;
		}
		$f = new File_Writer( $this->fileName, 0755 );
		$f->writeArray( $lines );
	}
}
?>