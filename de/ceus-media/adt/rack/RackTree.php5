<?php
import ("de.ceus-media.adt.tree.Tree");
/**
 *	...
 *	@package		adt
 *	@subpackage		rack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.05.2006
 *	@version			0.1
 */
/**
 *	...
 *	@package		adt
 *	@subpackage		rack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.05.2006
 *	@version			0.1
 *	@todo			Code Documentation
 */
class RackTree extends Tree
{
	var $_contents = array ();
	var $_separator;

	public function __construct ($separator = "/")
	{
		$this->_separator = $separator;
	}


	function addRack ($rackname)
	{
		$parts = explode ($this->_separator, $rackname);
		if (count($parts) > 1)
		{
			$rackname = implode ($this->_separator, array_slice($parts, 1));
			if ($this->isRack ($parts[0]))
			{
				return $this->_children[$parts[0]]->addRack ($rackname);
			}
			else
			{
				$this->addChild ($parts[0], new RackTree ($this->_separator));
				return $this->_children[$parts[0]]->addRack ($rackname);
			}
		}
		else
		{
			$this->addChild ($parts[0], new RackTree ($this->_separator));
			return true;
		}
	}

	function isRack ($rackname)
	{
		$parts = explode ($this->_separator, $rackname);
		if (count($parts) > 1)
		{
			$rackname = implode ($this->_separator, array_slice($parts, 1));
			if ($this->isRack ($parts[0]))
			{
				return $this->$parts[0]->isRack ($rackname);
			}
		}
		else
		{
			return (in_array ($rackname, array_keys ($this->_children)) && is_a ($this->_children[$rackname], "racktree"));
		}
		return false;
	}

	function addContent ($contentname, $content)
	{
		$parts = explode ($this->_separator, $contentname);
		if (count($parts) > 1)
		{
//			echo "<br>addContent: ".$contentname;
			$contentname = implode ($this->_separator, array_slice($parts, 1));
//			if (in_array ($parts[0], $this->_racks) && is_a ($this->_racks[$parts[0]], "rack"))
			if (isset ($this->_children[$parts[0]]))
			{
				return $this->_children[$parts[0]]->addContent ($contentname, $content);
			}
			else
			{
				$this->addChild ($parts[0], new RackTree ($this->_separator));
				return $this->_children[$parts[0]]->addContent ($contentname, $content);
			}
		}
		else
		{
			$this->_contents[$parts[0]] = $content;
			return true;
		}
	}
	
	function getContent ()
	{
	}
	
	function importArray ($array)
	{
		foreach ($array as $key => $value)
		{
			if (is_array ($value))
			{
				if (count ($value))
				{
		//			echo "<br>import_key:".$key;
					$this->addRack ($key);
					$parts = explode ($this->_separator, $key);
					$this->importArray ($value);
				}
			}
			else
			{
		//		echo "<br>content:".$key;
				$this->addContent ($key, $value);
			}
		}
	}
			
	function toArray ()
	{
		$a = array ();
		$vars = array_keys (get_object_vars($this));
		foreach ($vars as $var)
		{
			if (is_a ($this->$var, "rack"))
			{
				$a[$var] = $this->$var->toArray ();
			}
			else 	$a[$var] = $this->$var;
		}
		return $a;
	}
}
?>