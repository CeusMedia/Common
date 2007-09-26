<?php
import ("de.ceus-media.adt.rack.Rack");
/**
 *	...
 *	@package		adt
 *	@subpackage		rack
 */
/**
 *	...
 *	@package		adt
 *	@subpackage		rack
 *	@todo			Code Documentation
 */
class DirectoryRack extends Rack
{
	public function __construct ($dirname)
	{
		parent::__construct("/");
		$this->_dirname = $dirname;
		$this->_read ();
	}

	function _read ()
	{
		$folder = new Folder ($this->_dirname, false);
 		while (false !== ($entry = $folder->_dir->read ()))
 		{
 			
 			if (!ereg ("^[.]*$", $entry))
 			{
// 				echo $this->_dirname."/".$entry."<br>";
 				if (is_dir ($this->_dirname."/".$entry))
 					$this->_racks[$entry] = new DirectoryRack ($this->_dirname."/".$entry);
 				else if (is_file ($this->_dirname."/".$entry))
 					$this->addContent ($entry, "123");
 			}
 		}
	}
}
?>