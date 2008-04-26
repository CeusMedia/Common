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
class MTree
{
	var $_image_path	= "";
	var $_use_icons	= false;
	var $_nodes	= array ();
	var $_leafes	= array ();
	var $_icons	= array ();

	public function __construct ($image_path = false)
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
			$this->setIcons ($icons);
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

	function importRackTree ($object)
	{
		$this->import ($object, "_children", "_contents");
	}

	function import ($object, $nodename, $leafname)
	{
		foreach ($object->$nodename as $name => $obj)
		{
			$this->_nodes[$name] = new MTree ($this->_image_path);
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
		$this->import ($folder, "folders", "files");
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

	function toHTML ($url = false, $target = false, $prefix = "b", $recursion = 0, $path = "")
	{
		$code = $script = "";
		$id = 0;
		$ins_target = $target ? " target='".$target."'" : "";
		if (!$recursion)
		{
			$code .= str_repeat (" ", $recursion)."<ul class='tree'>";
			$code .= "<li class='tree' onclick=\"rt.change('".$prefix.$id."')\">".$this->_root_name."</li>\n";
			$code .= str_repeat (" ", $recursion)."<li name='rack' class='rackshut' id='".$prefix.$id."'><ul class='tree'>";
			$script = "rt.recall('".$prefix.$id."');\n";
		}
		$recursion++;
		$last_node = array_keys(array_slice (array_merge ($this->_nodes, $this->_leafes), -1));
		foreach ($this->_nodes as $rackname => $rack)
		{
			$new_path = $path.$rackname."/";
			$id ++;
			$code .= str_repeat (" ", $recursion)."<li class='rack' onclick=\"rt.change('".$prefix.$id."')\">".$rackname."</li>\n";
			$code .= str_repeat (" ", $recursion)."<li class='rackshut' name='rack' id='".$prefix.$id."'><ul class='tree'>\n";
			$tree = $rack->toHTML ($url, $target, $prefix."-".$id, $recursion, $new_path);
			$code .= $tree["code"];
			$script .= $tree["script"];
			$script .= "rt.recall('".$prefix.$id."');\n";
			$code .= str_repeat (" ", $recursion)."</ul></li>\n";
		}
		$last_leaf = array_keys(array_slice ($this->_leafes, -1));
		foreach ($this->_leafes as $contentname => $content)
		{
			if ($url)
				$link = "<a class='leaf' href='".$url.urlencode($path.$contentname)."'".$ins_target.">".$contentname."</a>";
			else
				$link = $contentname;
			$code .= str_repeat (" ", $recursion)."<li class='cont'>".$link."</li>\n";
		}
		if ($recursion==1) $code .= "</ul></li></ul>";
		return array ("code" => $code, "script" => $script);
	}
}
?>