<?php
/**
 *	Sniffer for Client's Operating System.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.1
 */
/**
 *	Sniffer for Client's Operating System.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.4
 */
class HTTP_OperatingSystemSniffer
{
	/**	@var	string		$_os			Operating System */
	var $_os;
	/**	@var	string		$_os_version		Version of Operating System */
	var $_os_version;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_getOS();
	}
	
	/**
	 *	Returns Operating System and Version.
	 *	@access		public
	 *	@return		array
	 */
	function getOS()
	{
		return array( "os" => $this->_os, "version" => $this->_os_version );
	}
	
	/**
	 *	Identifies Operating System and Version.
	 *	@access		public
	 *	@return		void
	 */
	function _getOS()
	{
		$ua = getEnv( 'HTTP_USER_AGENT' );
		if (eregi("win", $ua))
		{
			$this->_os = "Windows";
			if ((eregi("Windows 95", $ua)) || (eregi("Win95", $ua))) $this->_os_version = "95";
			elseif (eregi("Windows ME", $ua) || (eregi("Win 9x 4.90", $ua))) $this->_os_version = "ME";
			elseif ((eregi("Windows 98", $ua)) || (eregi("Win98", $ua))) $this->_os_version = "98";
			elseif ((eregi("Windows NT 5.0", $ua)) || (eregi("WinNT5.0", $ua)) || (eregi("Windows 2000", $ua)) || (eregi("Win2000", $ua))) $this->_os_version = "2000";
			elseif ((eregi("Windows NT 5.1", $ua)) || (eregi("WinNT5.1", $ua)) || (eregi("Windows XP", $ua))) $this->_os_version = "XP";
			elseif ((eregi("Windows NT 5.2", $ua)) || (eregi("WinNT5.2", $ua))) $this->_os_version = ".NET 2003";
			elseif ((eregi("Windows NT 6.0", $ua)) || (eregi("WinNT6.0", $ua))) $this->_os_version = "Codename: Longhorn";
			elseif (eregi("Windows CE", $ua)) $this->_os_version = "CE";
			elseif (eregi("Win3.11", $ua)) $this->_os_version = "3.11";
			elseif (eregi("Win3.1", $ua)) $this->_os_version = "3.1";
			elseif ((eregi("Windows NT", $ua)) || (eregi("WinNT", $ua))) $this->_os_version = "NT";
		}
		elseif (eregi("lindows", $ua))
			$this->_os = "LindowsOS";
		elseif (eregi("mac", $ua))
		{
			$this->_os = "MacIntosh";
			if ((eregi("Mac OS X", $ua)) || (eregi("Mac 10", $ua))) $this->_os_version = "OS X";
			elseif ((eregi("PowerPC", $ua)) || (eregi("PPC", $ua))) $this->_os_version = "PPC";
			elseif ((eregi("68000", $ua)) || (eregi("68k", $ua))) $this->_os_version = "68K";
		}
		elseif (eregi("linux", $ua))
		{
			$this->_os = "Linux";
			if (eregi("i686", $ua)) $this->_os_version = "i686";
			elseif (eregi("i586", $ua)) $this->_os_version = "i586";
			elseif (eregi("i486", $ua)) $this->_os_version = "i486";
			elseif (eregi("i386", $ua)) $this->_os_version = "i386";
			elseif (eregi("ppc", $ua)) $this->_os_version = "ppc";
		}
		elseif (eregi("freebsd", $ua))
		{
			$this->_os = "FreeBSD";
			if (eregi("i686", $ua)) $this->_os_version = "i686";
			elseif (eregi("i586", $ua)) $this->_os_version = "i586";
			elseif (eregi("i486", $ua)) $this->_os_version = "i486";
			elseif (eregi("i386", $ua)) $this->_os_version = "i386";
		}
		elseif (eregi("netbsd", $ua))
		{
			$this->_os = "NetBSD";
			if (eregi("i686", $ua)) $this->_os_version = "i686";
			elseif (eregi("i586", $ua)) $this->_os_version = "i586";
			elseif (eregi("i486", $ua)) $this->_os_version = "i486";
			elseif (eregi("i386", $ua)) $this->_os_version = "i386";
		}
		elseif (eregi("os/2", $ua))
		{
			$this->_os = "OS/2";
			if (eregi("Warp 4.5", $ua)) $this->_os_version = "Warp 4.5";
			elseif (eregi("Warp 4", $ua)) $this->_os_version = "Warp 4";
		}
		elseif (eregi("qnx", $ua))
		{
			$this->_os = "QNX";
			if (eregi("photon", $ua)) $this->_os_version = "Photon";
		}
		elseif (eregi("symbian", $ua))			$this->_os = "Symbian";
		elseif (eregi("sunos", $ua))			$this->_os = "SunOS";
		elseif (eregi("hp-ux", $ua))			$this->_os = "HP-UX";
		elseif (eregi("osf1", $ua))			$this->_os = "OSF1";
		elseif (eregi("irix", $ua))				$this->_os = "IRIX";
		elseif (eregi("amiga", $ua))			$this->_os = "Amiga";
		elseif (eregi("liberate", $ua))			$this->_os = "Liberate";
		elseif (eregi("dreamcast", $ua))		$this->_os = "Sega Dreamcast";
		elseif (eregi("palm", $ua))			$this->_os = "Palm";
		elseif (eregi("powertv", $ua))			$this->_os = "PowerTV";
		elseif (eregi("prodigy", $ua))			$this->_os = "Prodigy";
		elseif (eregi("unix", $ua))			$this->_os = "Unix";
		elseif (eregi("webtv", $ua))			$this->_os = "WebTV";
		elseif (eregi("sie-cx35", $ua))			$this->_os = "Siemens CX35";
	}
}
?>