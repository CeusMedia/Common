<?php
/**
 *	...
 *	@package	ui
 *	@subpackage	tree
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	...
 *	@package	ui
 *	@subpackage	tree
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documentation
 */
class KTree
{
	var $_image_path	= "";
	var $_use_icons	= false;
	var $_nodes	= array ();
	var $_leafes	= array ();
	var $_icons	= array ();

	public function __construct ($image_path, $extension = "png")
	{
		if ($image_path)
		{
			$this->_use_icons = true;
			$this->_image_path = $image_path;
			$icons = array (
				"line"		=> "line",
				"plus"		=> "plus",
				"minus"		=> "minus",
				"folder"		=> "folder",
				"file"			=> "file",
				"root"		=> "root",
				"line"		=> "line",
				"joinbottom"	=> "joinbottom",
				"join"		=> "join");
			$this->setIcons ($icons, $extension);
		}
		$this->clearTree ();
		$this->_root_name = "root";
	}

	function clearTree ()
	{
		$this->_nodes = $this->_leafes = array ();
	}

	function getIcon ($name, $id = false)
	{
		$code = "";
//		if ($id) $ins_id = " id='".$id."'";
		if ($this->_use_icons && in_array ($name, array_keys ($this->_icons)))
			$code = "<img src='".$this->_icons[$name]."' alt=''>";
		return $code;
	}

	function import ($object, $nodename, $leafname)
	{
		foreach ($object->$nodename as $name => $obj)
		{
			$this->_nodes[$name] = new KTree ($this->_image_path);
			$this->_nodes[$name]->_icons = $this->_icons;
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
		$this->import ($folder, "_folders", "_files");
	}

	function setIcon ($name, $image_name, $extension = "png")
	{
		if ($image_name) $this->_icons [$name] = $this->_image_path.$image_name.".".$extension;
		else unset ($this->_icons [$name]);
	}
	
	function setIcons ($icons, $extension = "png")
	{
		foreach ($icons as $name => $image_name)
			$this->setIcon ($name, $image_name, $extension);
	}

	function setRootIcon ($image_name, $extension = "png")
	{
		$this->setIcon ('root', $image_name, $extension);
	}

	function setRootName ($root_name)
	{
		$this->_root_name = $root_name;
	}

	function toHTML ($url, $target = false, $prefix = "b", $recursion = 0, $path = "")
	{
		$code = $script = "";
		$id = 0;
		if ($target) $ins_target = " target='".$target."'";
		if (!$recursion)
		{
			if ($this->_use_icons) $code .= str_repeat (" ", $recursion)."<div class='tree'><div class='rackname' onclick=\"rt.change('".$prefix.$id."')\"><table class='node' cellpadding='0' cellspacing='0' border='0'><tr><td>".$this->getIcon ('root')."</td><td class='nodename'>".$this->_root_name."</td></tr></table></div>\n";
			$code .= "<div name='rack' class='rackshut' id='".$prefix.$id."'>\n";
			$script = "rt.recall('".$prefix.$id."');\n";
		}
		$recursion++;
		$last_node = array_keys(array_slice (array_merge ($this->_nodes, $this->_leafes), -1));
		foreach ($this->_nodes as $rackname => $rack)
		{
			$new_path = $path.$rackname."/";
			if ($this->_use_icons) $icon_join = (($last_node[0] == $rackname)?$this->getIcon ('joinbottom'):$this->getIcon ('join'));
			if ($this->_use_icons) $icon_line = str_repeat ($this->getIcon ('line'), $depth = $recursion - 1);
			if ($this->_use_icons) $icon_this = $this->getIcon ("folder");
			$id ++;
			$code .= str_repeat (" ", $recursion)."<div class='rackname' onclick=\"rt.change('".$prefix.$id."')\"><table class='node' cellpadding='0' cellspacing='0'><tr><td>".$icon_line.$icon_join.$this->getIcon ('minus').$icon_this."</td><td class='nodename'>".$rackname."</td></tr></table></div>\n";
			$code .= str_repeat (" ", $recursion)."<div name='rack' class='rackshut' id='".$prefix.$id."'>\n";
			$tree = $rack->toHTML ($url, $target, $prefix.$id, $recursion, $new_path);
			$code .= $tree["code"];
			$script .= $tree["script"];
			$script .= "rt.recall('".$prefix.$id."');\n";
			$code .= str_repeat (" ", $recursion)."</div>\n";
		}
		$last_leaf = array_keys(array_slice ($this->_leafes, -1));
		foreach ($this->_leafes as $contentname => $content)
		{
			if ($this->_use_icons) $icon_join = (($last_leaf[0] == $contentname)?$this->getIcon ('joinbottom'):$this->getIcon ('join'));
			if ($this->_use_icons) $icon_line = str_repeat ($this->getIcon ('line'), $recursion - 1);
			if ($this->_use_icons) $icon_this = $icon_join.$this->getIcon ('file');
			$code .= str_repeat (" ", $recursion)."<div class='leaf'><table class='node' cellpadding='0' cellspacing='0'><tr><td>".$icon_line.$icon_this."</td><td class='leafname'><a class='leaf' href='".$url.urlencode ($path.$contentname)."'".$ins_target.">".$contentname."</a></td></tr></table></div>\n";
		}
		if ($recursion==1) $code .= "</div></div>";
		return array ("code" => $code, "script" => $script);
	}
}
?>