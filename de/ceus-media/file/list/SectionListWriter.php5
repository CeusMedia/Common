<?php
import ("de.ceus-media.file.File");
/**
 *	Writer for Section List.
 *	@package		file
 *	@subpackage		list
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Writer for Section List.
 *	@package		file
 *	@subpackage		list
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class SectionListWriter
{
	/**	@var	string		filename		URI of Section List */
	var $_filename;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of Section List
	 *	@return		void
	 */
	public function __construct( $filename )
	{
		$this->_filename = $filename;
	}

	/**
	 *	Writes Section List.
	 *	@access		public
	 *	@param		SectionList	section_list	Section List to write
	 *	@return		void
	 */
	function write ($section_list)
	{
		$lines = array();
		foreach ($section_list->toArray() as $section => $data)
		{
			if (count($lines))
				$lines[] = "";
			$lines[] = "[".$section."]";
			foreach ($data as $entry)
				$lines[] = $entry;
		}
		$f = new File ($this->_filename, 0755);
		$f->writeArray ($lines);
	}
}
?>