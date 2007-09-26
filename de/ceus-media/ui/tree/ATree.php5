<?php
/**
 *	...
 *	@package		ui
 *	@subpackage		tree
 *	@todo	Class Documentation
 */
class ATree
{
	var $_array;
	var $_baselink;
	var $_id;
	var $_prefix;
	
	public function __construct ($array, $prefix)
	{
		$this->_array	= $array;
		$this->_prefix	= $prefix;
	}
	
	function toHTML ($baselink, $target = '_self')
	{
		$this->_id		= 0;
		$this->_baselink	= $baselink;
		$this->_target	= $target;
		$code	= "
<!--  start TREE  //-->
<ul class='tree'>";
		$tree	= $this->_buildNode ($this->_array);
		$code	.= $tree['code'];
		$script	.= $tree['script'];
		$code	.= "
</ul>
<!--  end TREE  //-->
";
		return array ("code" => $code, "script" => $script);
	}
	
	function _buildNode ($array, $depth = 0)
	{
		$tabs = str_repeat ("  ", $depth);
		if (is_array ($array) && $array['tag'])
		{
			$id = $this->_getID();
			$class = $depth == 1 ? 'root' : 'child';
			$code .= "
$tabs<li class='".$class."'>
$tabs  <span class='".$class."' onclick=\"rt.change('".$id."')\">".str_repeat ("&nbsp;", 6)."</span>
$tabs  <span><a class='node' href='".$this->_baselink.$id."' target='".$this->_target."'>".strtolower($array['tag'])."</a></span>
$tabs</li>
$tabs<li class='rackshut' id='".$id."'>
$tabs  <ul class='tree'>";
			$script .= "rt.recall ('".$id."');\n";
			if (count ($array['attributes']))
			{
				$tree = $this->_buildAttributes($array['attributes'], $depth + 1);
				$code .= $tree["code"];
				$script .= $tree["script"];
			}
			if ($array['content'])
			{
				$code .= $this->_buildContent($array['content'], $depth + 1);
			}
			if (count ($array['children']))
			{
				foreach ($array['children'] as $child)
				{
					$tree = $this->_buildNode($child, $depth + 1);
					$code .= $tree["code"];
					$script .= $tree["script"];
				}
			}
			$code .= "$tabs  </ul></li>";

		}
		else if (is_array ($array[0]))
		{
			foreach ($array as $data)
			{
				$tree = $this->_buildNode($data, $depth + 1);
				$code .= $tree["code"];
				$script .= $tree["script"];
			}
		}
		return array ("code" => $code, "script" => $script);
	}
	
	function _buildAttributes ($array, $depth = 0)
	{
		$tabs = str_repeat ("  ", $depth);
		foreach ($array as $key => $value)
		{
			$id = $this->_getID();
			$script .= "rt.recall ('".$id."');\n";
			$code .= "
$tabs<li class='attribute' onClick='this.blur();'>
$tabs  <span class='attribute' onclick=\"rt.change('".$id."')\">".str_repeat ("&nbsp;", 6)."</span>
$tabs  <span><a class='node' href='".$this->_baselink.$id."' target='".$this->_target."'>".strtolower($key)."</a></span>
$tabs</li>
$tabs<li class='rackshut' id='".$id."'>
$tabs  <ul class='tree'>
$tabs    <li class='content'><span class='content'>".str_repeat ("&nbsp;", 6)."</span>
$tabs      <span><a class='leaf' href='".$this->_baselink.$this->_getID()."' target='".$this->_target."'>".$value."</a></span>
$tabs    </li>
$tabs  </ul>
$tabs</li>";
		}
		return array ("code" => $code, "script" => $script);		
	}
	
	function _buildContent ($content, $depth = 0)
	{
		$tabs = str_repeat ("  ", $depth);
		$code = "
$tabs<li class='content'>
$tabs  <span class='content'>".str_repeat ("&nbsp;", 6)."</span>
$tabs  <span><a class='leaf' href='".$this->_baselink.$this->_getID()."' target='".$this->_target."'>".$content."</a></span>
$tabs</li>";
		return $code;		
	}
	
	function _getID ()
	{
		$this->_id++;
		$id = md5($this->_getPrefix().$array['tag'].$this->_id);
		return $id;
	}
	
	function _getPrefix ()
	{
		return $this->_prefix;
	}
}
?>