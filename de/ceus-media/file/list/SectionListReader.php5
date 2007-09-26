<?php
import ("de.ceus-media.adt.list.SectionList");
import ("de.ceus-media.file.File");
/**
 *	A Class for reading Section List Files.
 *	@package		file
 *	@extends		SectionList
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	A Class for reading Section List Files.
 *	@package		file
 *	@extends		SectionList
 *	@uses			File
 *	@author			Chistian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class SectionListReader extends SectionList
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		URI of sectioned list
	 *	@return		void
	 */
	function SectionListReader ($filename)
	{
		parent::__construct();
		$this->_comment_pattern	= "^[#|-|*|:|;]{1}";
		$this->_section_pattern		= "^([){1}([a-z0-9_=.,:;# ])+(]){1}$";
		$this->_read ($filename);
	}

	/**
	 *	Reads the List.
	 *	@access		public
	 *	@param		string	filename		URI of sectioned list
	 *	@return		void
	 */
	function _read ($filename)
	{
		if( file_exists( $filename ) )
		{
			$file	= new File( $filename );
			$lines	= $file->readArray();
			foreach ($lines as $line)
			{
				if (($line = trim($line)) != "")
				{
					if (!ereg ($this->_comment_pattern, $line))
					{
						if (ereg ($this->_section_pattern, $line))
						{
							$section = substr($line, 1, -1);
							$this->addSection ($section);
						}
						else if ($section)
						{
							$this->addEntry ($line, $section);						
						}
					}
				}
			}
		}
		else
			trigger_error( "File '".$filename."' is not existing", E_USER_WARNING );
	}
}
?>