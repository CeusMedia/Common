<?php
import ("de.ceus-media.adt.AtomTime");
import ("de.ceus-media.file.ini.Reader");
import ("de.ceus-media.file.ini.Writer");
/**
 *	Calculates real Time by Server time and synchronised Atom time.
 *	@package	adt
 *	@extends	AtomTime
 *	@uses		File_INI_Reader
 *	@uses		File_INI_Writer
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
/**
 *	Calculates real Time by Server time and synchronised Atom time.
 *	@package	adt
 *	@extends	AtomTime
 *	@uses		File_INI_Reader
 *	@uses		File_INI_Writer
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
class AtomServerTime extends AtomTime
{
	/**	@var	string		_sync_file		URI of File with synchronized atom time */
	var $_sync_file	= "";
	/**	@var	string		_sync_time		Timestamp of last synchronisation */
	var $_sync_time	= "";
	/**	@var	int			_sync_diff		Time difference between server time and atom time */
	var $_sync_diff	= 0;
	/**	@var	int			_refresh_time		Time distance in seconds for synchronisation update */
	var $_refresh_time	= 86400;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	sync_filename		URI of File with synchronized atom time
	 *	@param		int		refresh_time		Time distance in seconds for synchronisation update
	 *	@return		void
	 */
	public function __construct ($sync_filename = "AtomServerTime.sync", $refresh_time = 0)
	{
		$this->_sync_file = $sync_filename;
		$this->_readSyncFile ();
		if ($refresh_time)
			$this->_refresh_time = $refresh_time;
		if (time () - $this->_sync_time >=  $this->_refresh_time)
			$this->synchronize ();
	}

	/**
	 *	Reads File with synchronized atom time difference.
	 *	@access		private
	 *	@return		void
	 */
	function _readSyncFile()
	{
		if (!file_exists ($this->_sync_file))
			$this->synchronize ();
		$ir = new File_INI_Reader ($this->_sync_file, false);
		$data = $ir->getProperties (true);
		$this->_sync_time	= $data['time'];
		$this->_sync_diff	= $data['diff'];
	}

	/**
	 *	Synchronizes server time with atom time by saving time difference.
	 *	@access		public
	 *	@return		void
	 */
	function synchronize ()
	{
		$at		= new AtomTime ();
		$atom	= $at->getTimestamp ();
		$server	= time ();
		$diff		= $server - $atom;
		$f		= new File ($this->_sync_file, 0777);
		$iw		= new File_INI_Writer ($this->_sync_file);
		$iw->setProperty ("time", time ());
		$iw->setProperty ("diff", $diff);
		$this->_readSyncFile ();
	}
	
	/**
	 *	Returns timestamp of last synchronisation.
	 *	@access		public
	 *	@return		int
	 */
	function getSyncTime ()
	{
		return $this->_sync_time;
	}
	
	/**
	 *	Returns date of last synchronisation as formatted string.
	 *	@access		public
	 *	@param		string	format			Date format
	 *	@return		string
	 */
	function getSyncDate ($format = "d.m.Y - H:i:s")
	{
		return date($format, $this->_sync_time);
	}
	
	/**
	 *	Returns time difference between server time and atom time.
	 *	@access		public
	 *	@return		int
	 */
	function getSyncDiff()
	{
		return $this->_sync_diff;
	}
	
	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@return		int
	 *	@link		http://www.php.net/time
	 */
	function getTimestamp ()
	{
		$time = time() + $this->_diff;
		return $time;
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
		$time = time() + $this->_diff;
		return date($format, $time);
	}
}
?>