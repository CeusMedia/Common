<?php
import ("de.ceus-media.StopWatch");
/**
 *	Connects Server to request atom time.
 *	@package	adt
 *	@uses		StopWatch
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
/**
 *	Connects Server to request atom time.
 *	@package	adt
 *	@uses		StopWatch
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
class AtomTime
{
	/**	@var	array		_months			List of months in english language */
	var $_months	= array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	/**	@var	string		_url				URL for server request */
	var $_url		= "http://www.uni-leipzig.de/cgi-bin/date/index.htm";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	url				URL for server request
	 *	@return		void
	 */
	public function __construct ($url = false)
	{
		if ($url)
			$this->setURL ($url);
	}
	
	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@return		int
	 *	@link		http://www.php.net/time
	 */
	function getTimestamp ()
	{
		if ($time = $this->_read())
			return $time;
		return false;
	}
	
	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@param		string	format			Date format
	 *	@return		string
	 *	@link		http://www.php.net/date
	 */
	function getDate ($format = "d.m.Y - H:i:s")
	{
		if ($time = $this->_read())
			return date($format, $time);
		return false;
	}

	/**
	 *	Sets URL for server  request.
	 *	@access		private
	 *	@return		int
	 */
	function setURL ($url)
	{
		$this->_url = $url;
	}

	/**
	 *	Reads and parses server response and returns timestamp.
	 *	@access		private
	 *	@return		int
	 */
	function _read ()
	{
		$st = new StopWatch;
		if($file = @file ($this->_url))
		{
			$delay	= floor ($st->stop()/1000);
			$parts	= explode (" ", $file[0]);
			$month	= $parts[1];
			$month	= (array_search($month,$this->_months))+1;
			$day	= $parts[2];
			$year	= $parts[5];
			$time	= $parts[3];
			$parts	= explode(":",$time);
			$hour	= $parts[0];
			$min		= $parts[1];
			$sec		= $parts[2];
			if ($delay)
			{
				if ($s >= $delay)
					$s -= $delay;
				else
				{
					$m--;
					$s = $s + 60 - $delay;
				}
			}
			$time	= mktime($hour,$min,$sec,$month,$day,$year);
			return $time;
		}
		else
			return 0;
	}
}
?>