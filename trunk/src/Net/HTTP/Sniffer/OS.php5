<?php
/**
 *	Sniffer for Client's Operating System.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Net.HTTP.Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
/**
 *	Sniffer for Client's Operating System.
 *	@category		cmClasses
 *	@package		Net.HTTP.Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 *	@todo			may be out of date
 */
class Net_HTTP_Sniffer_OS
{
	/**	@var	string		$system			Operating System */
	protected $system		= "";
	/**	@var	string		$version		Version of Operating System */
	protected $version		= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->identifySystem();
	}
	
	/**
	 *	Returns Operating System and Version.
	 *	@access		public
	 *	@return		array
	 */
	public function getOS()
	{
		return array( "system" => $this->system, "version" => $this->version );
	}
	
	/**
	 *	Returns Operating System and Version.
	 *	@access		public
	 *	@return		array
	 */
	public function getSystem()
	{
		return $this->system;
	}
	
	/**
	 *	Returns Operating System Version.
	 *	@access		public
	 *	@return		array
	 */
	public function getVersion()
	{
		return $this->version;
	}
	
	/**
	 *	Identifies Operating System and Version.
	 *	@access		protected
	 *	@return		void
	 */
	protected function identifySystem()
	{
		$ua = getEnv( 'HTTP_USER_AGENT' );
		if( eregi( "win", $ua ) )
		{
			$this->system = "Windows";
			if( (eregi( "Windows 95", $ua ) ) || ( eregi( "Win95", $ua ) )) $this->version = "95";
			elseif( eregi( "Windows ME", $ua) || ( eregi( "Win 9x 4.90", $ua ) )) $this->version = "ME";
			elseif( ( eregi( "Windows 98", $ua ) ) || ( eregi( "Win98", $ua ) )) $this->version = "98";
			elseif( ( eregi( "Windows NT 5.0", $ua ) ) || ( eregi( "WinNT5.0", $ua ) ) || ( eregi( "Windows 2000", $ua ) ) || ( eregi( "Win2000", $ua ) ) ) $this->version = "2000";
			elseif( ( eregi( "Windows NT 5.1", $ua ) ) || ( eregi( "WinNT5.1", $ua ) ) || ( eregi( "Windows XP", $ua ) ) ) $this->version = "XP";
			elseif( ( eregi( "Windows NT 5.2", $ua ) ) || ( eregi( "WinNT5.2", $ua ) ) ) $this->version = ".NET 2003";
			elseif( ( eregi( "Windows NT 6.0", $ua ) ) || ( eregi( "WinNT6.0", $ua ) ) ) $this->version = "Codename: Longhorn";
			elseif( eregi( "Windows CE", $ua ) ) $this->version = "CE";
			elseif( eregi( "Win3.11", $ua ) ) $this->version = "3.11";
			elseif( eregi( "Win3.1", $ua ) ) $this->version = "3.1";
			elseif( ( eregi( "Windows NT", $ua ) ) || ( eregi( "WinNT", $ua ) )) $this->version = "NT";
		}
		elseif( eregi( "lindows", $ua ) )
			$this->system = "LindowsOS";
		elseif( eregi( "mac", $ua ) )
		{
			$this->system = "MacIntosh";
			if( (eregi( "Mac OS X", $ua ) ) || ( eregi( "Mac 10", $ua ) ) ) $this->version = "OS X";
			elseif( (eregi( "PowerPC", $ua ) ) || ( eregi( "PPC", $ua ) ) ) $this->version = "PPC";
			elseif( (eregi( "68000", $ua ) ) || ( eregi( "68k", $ua ) ) ) $this->version = "68K";
		}
		elseif( eregi( "linux", $ua ) )
		{
			$this->system = "Linux";
			if( eregi( "i686", $ua ) )			$this->version = "i686";
			elseif( eregi( "i586", $ua ) )		$this->version = "i586";
			elseif( eregi( "i486", $ua ) )		$this->version = "i486";
			elseif( eregi( "i386", $ua ) )		$this->version = "i386";
			elseif( eregi( "ppc", $ua ) )		$this->version = "ppc";
		}
		elseif( eregi( "freebsd", $ua ) )
		{
			$this->system = "FreeBSD";
			if( eregi( "i686", $ua ) )			$this->version = "i686";
			elseif( eregi( "i586", $ua ) )		$this->version = "i586";
			elseif( eregi( "i486", $ua ) )		$this->version = "i486";
			elseif( eregi( "i386", $ua ) )		$this->version = "i386";
		}
		elseif( eregi( "netbsd", $ua ) )
		{
			$this->system = "NetBSD";
			if( eregi( "i686", $ua ) )			$this->version = "i686";
			elseif( eregi( "i586", $ua ) )		$this->version = "i586";
			elseif( eregi( "i486", $ua ) )		$this->version = "i486";
			elseif( eregi( "i386", $ua ) )		$this->version = "i386";
		}
		elseif( eregi( "os/2", $ua ) )
		{
			$this->system = "OS/2";
			if( eregi( "Warp 4.5", $ua ) )		$this->version = "Warp 4.5";
			elseif( eregi( "Warp 4", $ua ) )	$this->version = "Warp 4";
		}
		elseif( eregi( "qnx", $ua ) )
		{
			$this->system = "QNX";
			if( eregi( "photon", $ua ) ) $this->version = "Photon";
		}
		elseif( eregi( "symbian", $ua ) )		$this->system = "Symbian";
		elseif( eregi( "sunos", $ua ) )			$this->system = "SunOS";
		elseif( eregi( "hp-ux", $ua ) )			$this->system = "HP-UX";
		elseif( eregi( "osf1", $ua ) )			$this->system = "OSF1";
		elseif( eregi( "irix", $ua ) )			$this->system = "IRIX";
		elseif( eregi( "amiga", $ua ) )			$this->system = "Amiga";
		elseif( eregi( "liberate", $ua ) )		$this->system = "Liberate";
		elseif( eregi( "dreamcast", $ua ) )		$this->system = "Sega Dreamcast";
		elseif( eregi( "palm", $ua ) )			$this->system = "Palm";
		elseif( eregi( "powertv", $ua ) )		$this->system = "PowerTV";
		elseif( eregi( "prodigy", $ua ) )		$this->system = "Prodigy";
		elseif( eregi( "unix", $ua ) )			$this->system = "Unix";
		elseif( eregi( "webtv", $ua ) )			$this->system = "WebTV";
		elseif( eregi( "sie-cx35", $ua ) )		$this->system = "Siemens CX35";
	}
}
?>