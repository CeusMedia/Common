<?php
/**
 *	Sniffer for Client's Operating System.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			12.08.2005
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

/**
 *	Sniffer for Client's Operating System.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			12.08.2005
 *	@todo			may be out of date
 */
class OS
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
		return [
			'system'	=> $this->system,
			'version'	=> $this->version,
		];
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
		if( preg_match( "~win~i", $ua ) ){
			$this->system = "Windows";
			if( (preg_match( "~Windows 95~i", $ua ) ) || ( preg_match( "~Win95~i", $ua ) )) $this->version = "95";
			elseif( preg_match( "~Windows ME~i", $ua) || ( preg_match( "~Win 9x 4.90~i", $ua ) )) $this->version = "ME";
			elseif( ( preg_match( "~Windows 98~i", $ua ) ) || ( preg_match( "~Win98~i", $ua ) )) $this->version = "98";
			elseif( ( preg_match( "~Windows NT 5.0~i", $ua ) ) || ( preg_match( "~WinNT5.0~i", $ua ) ) || ( preg_match( "~Windows 2000~i", $ua ) ) || ( preg_match( "~Win2000~i", $ua ) ) ) $this->version = "2000";
			elseif( ( preg_match( "~Windows NT 5.1~i", $ua ) ) || ( preg_match( "~WinNT5.1~i", $ua ) ) || ( preg_match( "~Windows XP~i", $ua ) ) ) $this->version = "XP";
			elseif( ( preg_match( "~Windows NT 5.2~i", $ua ) ) || ( preg_match( "~WinNT5.2~i", $ua ) ) ) $this->version = ".NET 2003";
			elseif( ( preg_match( "~Windows NT 6.0~i", $ua ) ) || ( preg_match( "~WinNT6.0~i", $ua ) ) ) $this->version = "Codename: Longhorn";
			elseif( preg_match( "~Windows CE~i", $ua ) ) $this->version = "CE";
			elseif( preg_match( "~Win3.11~i", $ua ) ) $this->version = "3.11";
			elseif( preg_match( "~Win3.1~i", $ua ) ) $this->version = "3.1";
			elseif( ( preg_match( "~Windows NT~i", $ua ) ) || ( preg_match( "~WinNT~i", $ua ) )) $this->version = "NT";
		}
		elseif( preg_match( "~lindows~i", $ua ) )
			$this->system = "LindowsOS";
		elseif( preg_match( "~mac~i", $ua ) ){
			$this->system = "MacIntosh";
			if( (preg_match( "~Mac OS X~i", $ua ) ) || ( preg_match( "~Mac 10~i", $ua ) ) ) $this->version = "OS X";
			elseif( (preg_match( "~PowerPC~i", $ua ) ) || ( preg_match( "~PPC~i", $ua ) ) ) $this->version = "PPC";
			elseif( (preg_match( "~68000~i", $ua ) ) || ( preg_match( "~68k~i", $ua ) ) ) $this->version = "68K";
		}
		elseif( preg_match( "~linux~i", $ua ) ){
			$this->system = "Linux";
			if( preg_match( "~i686~i", $ua ) )			$this->version = "i686";
			elseif( preg_match( "~i586~i", $ua ) )		$this->version = "i586";
			elseif( preg_match( "~i486~i", $ua ) )		$this->version = "i486";
			elseif( preg_match( "~i386~i", $ua ) )		$this->version = "i386";
			elseif( preg_match( "~ppc~i", $ua ) )		$this->version = "ppc";
		}
		elseif( preg_match( "~freebsd~i", $ua ) ){
			$this->system = "FreeBSD";
			if( preg_match( "~i686~i", $ua ) )			$this->version = "i686";
			elseif( preg_match( "~i586~i", $ua ) )		$this->version = "i586";
			elseif( preg_match( "~i486~i", $ua ) )		$this->version = "i486";
			elseif( preg_match( "~i386~i", $ua ) )		$this->version = "i386";
		}
		elseif( preg_match( "~netbsd~i", $ua ) ){
			$this->system = "NetBSD";
			if( preg_match( "~i686~i", $ua ) )			$this->version = "i686";
			elseif( preg_match( "~i586~i", $ua ) )		$this->version = "i586";
			elseif( preg_match( "~i486~i", $ua ) )		$this->version = "i486";
			elseif( preg_match( "~i386~i", $ua ) )		$this->version = "i386";
		}
		elseif( preg_match( "~os/2~i", $ua ) ){
			$this->system = "OS/2";
			if( preg_match( "~Warp 4.5~i", $ua ) )		$this->version = "Warp 4.5";
			elseif( preg_match( "~Warp 4~i", $ua ) )	$this->version = "Warp 4";
		}
		elseif( preg_match( "~qnx~i", $ua ) ){
			$this->system = "QNX";
			if( preg_match( "~photon~i", $ua ) ) $this->version = "Photon";
		}
		elseif( preg_match( "~symbian~i", $ua ) )		$this->system = "Symbian";
		elseif( preg_match( "~sunos~i", $ua ) )			$this->system = "SunOS";
		elseif( preg_match( "~hp-ux~i", $ua ) )			$this->system = "HP-UX";
		elseif( preg_match( "~osf1~i", $ua ) )			$this->system = "OSF1";
		elseif( preg_match( "~irix~i", $ua ) )			$this->system = "IRIX";
		elseif( preg_match( "~amiga~i", $ua ) )			$this->system = "Amiga";
		elseif( preg_match( "~liberate~i", $ua ) )		$this->system = "Liberate";
		elseif( preg_match( "~dreamcast~i", $ua ) )		$this->system = "Sega Dreamcast";
		elseif( preg_match( "~palm~i", $ua ) )			$this->system = "Palm";
		elseif( preg_match( "~powertv~i", $ua ) )		$this->system = "PowerTV";
		elseif( preg_match( "~prodigy~i", $ua ) )		$this->system = "Prodigy";
		elseif( preg_match( "~unix~i", $ua ) )			$this->system = "Unix";
		elseif( preg_match( "~webtv~i", $ua ) )			$this->system = "WebTV";
		elseif( preg_match( "~sie-cx35~i", $ua ) )		$this->system = "Siemens CX35";
	}
}
