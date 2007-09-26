<?php
/**
 *	??? Recursive Rack Structure for Tree-like Structures.
 *	@package		adt
 *	@subpackage		rack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.05.2006
 *	@version		0.1
 */
/**
 *	??? Recursive Rack Structure for Tree-like Structures.
 *	@package		adt
 *	@subpackage		rack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.05.2006
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Rack
{
	var $_racks = array ();
	var $_contents = array ();
	var $_separator;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		separator		Separator within Rack Paths
	 *	@return		void
	 */
	public function __construct( $separator = "/" )
	{
		$this->_separator = $separator;
	}

	/**
	 *	Adds a new Rack to Rack.
	 *	@access		public
	 *	@param		string		separator		Separator within Rack Paths
	 *	@return		void
	 */
	function addRack( $rackname )
	{
		$parts = explode( $this->_separator, $rackname );
		if( count( $parts ) > 1 )
		{
			$rackname = implode( $this->_separator, array_slice( $parts, 1) );
			if( $this->isRack( $parts[0] ) )
				return $this->_racks[$parts[0]]->addRack( $rackname );
			else
			{
				$this->_racks[$parts[0]] = new Rack( $this->_separator );
				return $this->_racks[$parts[0]]->addRack( $rackname );
			}
		}
		else
		{
			$this->_racks[$parts[0]] = new Rack( $this->_separator );
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
			return (in_array ($rackname, $this->_racks) && is_a ($this->_racks[$rackname], "rack"));
		}
		return false;
	}

	function addContent ($contentname, $content)
	{
		$parts = explode ($this->_separator, $contentname);
		if (count($parts) > 1)
		{
			$contentname = implode ($this->_separator, array_slice($parts, 1));
//			if (in_array ($parts[0], $this->_racks) && is_a ($this->_racks[$parts[0]], "rack"))
			if ($this->isRack ($parts[0]))
			{
				return $this->_racks[$parts[0]]->addContent ($contentname, $content);
			}
			else
			{
				$this->addRack ($parts[0]);
//				$this->_racks[$parts[0]] = new Rack ($parts[0]);
				return $this->_racks[$parts[0]]->addContent ($contentname, $content);
			}
		}
		else
		{
			$this->_contents[$contentname] = $content;
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
					$this->addRack ($key);
					$parts = explode ($this->_separator, $key);
					$this->_racks[$parts[0]]->importArray ($value);
//					$this->importArray ($value);
				}
			}
			else $this->addContent ($key, $value);
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