<?php
/**
 *	@package	ui
 *	@subpackage	tree
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	ui
 *	@subpackage	tree
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documentation
 */
class MenuTree
{
	var $_nodes	= array ();
	var $_leafes	= array ();

	function clearTree ()
	{
		$this->_nodes = $this->_leafes = array ();
	}

	function import ($object, $nodename, $leafname)
	{
		foreach ($object->$nodename as $name => $obj)
		{
			$this->_nodes[$name] = new MenuTree ($this->_image_path);
			$this->_nodes[$name]->import ($obj, $nodename, $leafname);
		}
		foreach ($object->$leafname as $name => $content)
			$this->_leafes[$name] = $content;
		ksort ($this->_nodes, SORT_STRING);
		ksort ($this->_leafes, SORT_STRING);
	}

	function importRack ($racks)
	{
		$this->import ($racks, "_racks", "_contents");
	}

	function importTreeFolder ($folder)
	{
		$this->import ($folder, "folders", "files");
	}

	function toHTML ($url, $target = false, $recursion = 0, $path = "")
	{
		$code = "";
		if ($target) $ins_target = " target='".$target."'";
		if (!$recursion)
		{
			$code .= "<ul id='menu'>\n";
		}
		$recursion++;
		foreach ($this->_nodes as $rackname => $rack)
		{
			$new_path = $path.$rackname."/";
			$code .= str_repeat (" ", $recursion)."<li class='folder'>";
			$code .= str_repeat (" ", $recursion)."<a href='#' class='submenu'>".$rackname."</a>\n";
			$code .= str_repeat (" ", $recursion)."<ul class='folder'>\n";
			$code .= $rack->toHTML ($url, $target, $recursion, $new_path);
			$code .= str_repeat (" ", $recursion)."</ul></li>\n";
		}
		$last_leaf = array_keys(array_slice ($this->_leafes, -1));
		foreach ($this->_leafes as $contentname => $content)
		{
			$code .= str_repeat (" ", $recursion)."<li><a class='menulink' href='".$url.$path.$contentname."'".$ins_target.">".$content."</a></li>\n";
		}
		if ($recursion==1) $code .= "</ul>";
		return $code;
	}
}
?>