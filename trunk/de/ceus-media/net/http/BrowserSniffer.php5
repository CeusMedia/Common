<?php
/**
 *	Sniffer for browsing HTTP Clients via User Agents.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
/**
 *	Sniffer for browsing HTTP Clients via User Agents.
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.08.2005
 *	@version		$Id$
 */
class Net_HTTP_BrowserSniffer
{
	/**	@var		string		$browser			Browser */
	protected $browser;
	/**	@var		string		$browserVersion		Browser Version */
	protected $browserVersion;
	/**	@var		string		$browserType		Browser Type */
	protected $browserType;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$userAgent			User Agent
	 *	@return		void
	 */
	public function __construct( $userAgent = false )
	{
		$this->identifyBrowser( $userAgent );
	}
	
	/**
	 *	Returns Browser, Version and Type.
	 *	@access		public
	 *	@return		array
	 */
	public function getBrowser()
	{
		return array( "browser" => $this->browser, "version" => $this->browserVersion, "type" => $this->browserType );
	}
	
	/**
	 *	Indicates whether Client is a Robot.
	 *	@access		public
	 *	@return		bool
	 */
	public function isRobot()
	{
		return $this->browserType == "robot";
	}

	/**
	 *	Indicates whether Client is a Browser.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBrowser()
	{
		return $this->browserType == "browser";
	}
	
	/**
	 *	Identifies Browser, Version and Type.
	 *	@access		private
	 *	@param		string		$userAgent			User Agent
	 *	@return		void
	 */
	public function identifyBrowser( $ua = false )
	{
		if( !$ua )
			$ua = getEnv( 'HTTP_userAgent' );
		$this->browserType = "robot";
		if (eregi("msnbot", $ua))
		{
			$this->browser = "MSN Bot";
			if (eregi("msnbot/0.11", $ua))	$this->browserVersion = "0.11";
			elseif (eregi("msnbot/0.30", $ua))	$this->browserVersion = "0.3";
			elseif (eregi("msnbot/1.0", $ua))	$this->browserVersion = "1.0";
		}
		elseif (eregi("almaden", $ua))
			$this->browser = "IBM Almaden Crawler";
		elseif (eregi("BecomeBot", $ua))
		{
			$this->browser = "BecomeBot";
			if (eregi("becomebot/1.23", $ua))	$this->browserVersion = "1.23";
		}
		elseif (eregi("Link-Checker-Pro", $ua))
			$this->browser = "Link Checker Pro";
		elseif (eregi("ia_archiver", $ua))
			$this->browser = "Alexa";
		elseif ((eregi("googlebot", $ua)) || (eregi("google", $ua)))
		{
			$this->browser = "Google Bot";
			if ((eregi("googlebot/2.1", $ua)) || (eregi("google/2.1", $ua)))	$this->browserVersion = "2.1";
		}
		elseif (eregi("surveybot", $ua))
		{
			$this->browser = "Survey Bot";
			if (eregi("surveybot/2.3", $ua))	$this->browserVersion = "2.3";
		}
		elseif (eregi("zyborg", $ua))
		{
			$this->browser = "ZyBorg";
			if (eregi("zyborg/1.0", $ua))	$this->browserVersion = "1.0";
		}
		elseif (eregi("w3c-checklink", $ua))
		{
			$this->browser = "W3C Checklink";
			if (eregi("checklink/3.6", $ua))	$this->browserVersion = "3.6";
		}
		elseif (eregi("linkwalker", $ua))
		{
			$this->browser = "LinkWalker";
		}
		elseif (eregi("fast-webcrawler", $ua))
		{
			$this->browser = "Fast WebCrawler";
			if (eregi("webcrawler/3.8", $ua))	$this->browserVersion = "3.8";
		}
		elseif ((eregi("yahoo", $ua)) && (eregi("slurp", $ua)))
		{
			$this->browser = "Yahoo! Slurp";
		}
		elseif (eregi("naverbot", $ua))
		{
			$this->browser = "NaverBot";
			if (eregi("dloader/1.5", $ua))	$this->browserVersion = "1.5";
		}
		elseif (eregi("converacrawler", $ua))
		{
			$this->browser = "ConveraCrawler";
			if (eregi("converacrawler/0.5", $ua))	$this->browserVersion = "0.5";
		}
		elseif (eregi("w3c_validator", $ua))
		{
			$this->browser = "W3C Validator";
			if (eregi("w3c_validator/1.305", $ua))	$this->browserVersion = "1.305";
		}
		elseif (eregi("innerprisebot", $ua))
		{
			$this->browser = "Innerprise";
			if (eregi("innerprise/1.0", $ua))	$this->browserVersion = "1.0";
		}
		elseif (eregi("topicspy", $ua))
		{
			$this->browser = "Topicspy Checkbot";
		}
		elseif (eregi("poodle predictor", $ua))
		{
			$this->browser = "Poodle Predictor";
			if (eregi("poodle predictor 1.0", $ua))	$this->browserVersion = "1.0";
		}
		elseif (eregi("ichiro", $ua))
		{
			$this->browser = "Ichiro";
			if (eregi("ichiro/1.0", $ua))	$this->browserVersion = "1.0";
		}
		elseif (eregi("link checker pro", $ua))
		{
			$this->browser = "Link Checker Pro";
			if (eregi("link checker pro 3.2.16", $ua))	$this->browserVersion = "3.2.16";
		}
		elseif (eregi("grub-client", $ua))
		{
			$this->browser = "Grub client";
			if (eregi("grub-client-2.3", $ua))	$this->browserVersion = "2.3";
		}
		elseif (eregi("gigabot", $ua))
		{
			$this->browser = "Gigabot";
			if (eregi("gigabot/2.0", $ua))	$this->browserVersion = "2.0";
		}
		elseif (eregi("psbot", $ua))
		{
			$this->browser = "PSBot";
			if (eregi("psbot/0.1", $ua))	$this->browserVersion = "0.1";
		}
		elseif (eregi("mj12bot", $ua))
		{
			$this->browser = "MJ12Bot";
			if (eregi("mj12bot/v0.5", $ua))	$this->browserVersion = "0.5";
		}
		elseif (eregi("nextgensearchbot", $ua))
		{
			$this->browser = "NextGenSearchBot";
			if (eregi("nextgensearchbot 1", $ua))	$this->browserVersion = "1";
		}
		elseif (eregi("tutorgigbot", $ua))
		{
			$this->browser = "TutorGigBot";
			if (eregi("bot/1.5", $ua))	$this->browserVersion = "1.5";
		}
		elseif (ereg("NG", $ua))
		{
			$this->browser = "Exabot NG";
			if (eregi("ng/2.0", $ua))	$this->browserVersion = "2.0";
		}
		elseif (eregi("gaisbot", $ua))
		{
			$this->browser = "Gaisbot";
			if (eregi("gaisbot/3.0", $ua))	$this->browserVersion = "3.0";
		}
		elseif (eregi("xenu link sleuth", $ua))
		{
			$this->browser = "Xenu Link Sleuth";
			if (eregi("xenu link sleuth 1.2", $ua))	$this->browserVersion = "1.2";
		}
		elseif (eregi("turnitinbot", $ua))
		{
			$this->browser = "TurnitinBot";
			if (eregi("turnitinbot/2.0", $ua))	$this->browserVersion = "2.0";
		}
		elseif (eregi("iconsurf", $ua))
		{
			$this->browser = "IconSurf";
			if (eregi("iconsurf/2.0", $ua))	$this->browserVersion = "2.0";
		}
		elseif (eregi("zoe indexer", $ua))
		{
			$this->browser = "Zoe Indexer";
			if (eregi("v1.x", $ua))	$this->browserVersion = "1";
		}
		else
		{
			$this->browserType = "browser";
			if (eregi("amaya", $ua))
			{
				$this->browser = "amaya";
				if (eregi("amaya/5.0", $ua))		$this->browserVersion = "5.0";
				elseif (eregi("amaya/5.1", $ua))	$this->browserVersion = "5.1";
				elseif (eregi("amaya/5.2", $ua))	$this->browserVersion = "5.2";
				elseif (eregi("amaya/5.3", $ua))	$this->browserVersion = "5.3";
				elseif (eregi("amaya/6.0", $ua))	$this->browserVersion = "6.0";
				elseif (eregi("amaya/6.1", $ua))	$this->browserVersion = "6.1";
				elseif (eregi("amaya/6.2", $ua))	$this->browserVersion = "6.2";
				elseif (eregi("amaya/6.3", $ua))	$this->browserVersion = "6.3";
				elseif (eregi("amaya/6.4", $ua))	$this->browserVersion = "6.4";
				elseif (eregi("amaya/7.0", $ua))	$this->browserVersion = "7.0";
				elseif (eregi("amaya/7.1", $ua))	$this->browserVersion = "7.1";
				elseif (eregi("amaya/7.2", $ua))	$this->browserVersion = "7.2";
				elseif (eregi("amaya/8.0", $ua))	$this->browserVersion = "8.0";
			}
			elseif ((eregi("aol", $ua)) && !(eregi("msie", $ua)))
			{
				$this->browser = "AOL";
				$this->browserType = "browser";
				if ((eregi("aol 7.0", $ua)) || (eregi("aol/7.0", $ua)))	$this->browserVersion = "7.0";
			}
			elseif ((eregi("aweb", $ua)) || (eregi("amigavoyager", $ua)))
			{
				$this->browser = "AWeb";
				$this->browserType = "browser";
				if (eregi("voyager/1.0", $ua))	$this->browserVersion = "1.0";
				elseif (eregi("voyager/2.95", $ua))	$this->browserVersion = "2.95";
				elseif ((eregi("voyager/3", $ua)) || (eregi("aweb/3.0", $ua)))	$this->browserVersion = "3.0";
				elseif (eregi("aweb/3.1", $ua))	$this->browserVersion = "3.1";
				elseif (eregi("aweb/3.2", $ua))	$this->browserVersion = "3.2";
				elseif (eregi("aweb/3.3", $ua))	$this->browserVersion = "3.3";
				elseif (eregi("aweb/3.4", $ua))	$this->browserVersion = "3.4";
				elseif (eregi("aweb/3.9", $ua))	$this->browserVersion = "3.9";
			}
			elseif (eregi("beonex", $ua))
			{
				$this->browser = "Beonex";
				$this->browserType = "browser";
				if (eregi("beonex/0.8.2", $ua))		$this->browserVersion = "0.8.2";
				elseif (eregi("beonex/0.8.1", $ua))	$this->browserVersion = "0.8.1";
				elseif (eregi("beonex/0.8", $ua))		$this->browserVersion = "0.8";
			}
			elseif (eregi("camino", $ua))
			{
				$this->browser = "Camino";
				$this->browserType = "browser";
				if (eregi("camino/0.7", $ua))	$this->browserVersion = "0.7";
			}
			elseif (eregi("cyberdog", $ua))
			{
				$this->browser = "Cyberdog";
				$this->browserType = "browser";
				if (eregi("cybergog/1.2", $ua))		$this->browserVersion = "1.2";
				elseif (eregi("cyberdog/2.0", $ua))		$this->browserVersion = "2.0";
				elseif (eregi("cyberdog/2.0b1", $ua))	$this->browserVersion = "2.0b1";
			}
			elseif (eregi("dillo", $ua))
			{
				$this->browser = "Dillo";
				$this->browserType = "browser";
				if (eregi("dillo/0.6.6", $ua))		$this->browserVersion = "0.6.6";
				elseif (eregi("dillo/0.7.2", $ua))	$this->browserVersion = "0.7.2";
				elseif (eregi("dillo/0.7.3", $ua))	$this->browserVersion = "0.7.3";
				elseif (eregi("dillo/0.8", $ua))		$this->browserVersion = "0.8";
			}
			elseif (eregi("doris", $ua))
			{
				$this->browser = "Doris";
				$this->browserType = "browser";
				if (eregi("doris/1.10", $ua))	$this->browserVersion = "1.10";
			}
			elseif (eregi("emacs", $ua))
			{
				$this->browser = "Emacs";
				$this->browserType = "browser";
				if (eregi("emacs/w3/2", $ua))	$this->browserVersion = "2";
				elseif (eregi("emacs/w3/3", $ua))	$this->browserVersion = "3";
				elseif (eregi("emacs/w3/4", $ua))	$this->browserVersion = "4";
			}
			elseif (eregi("firebird", $ua))
			{
				$this->browser = "Firebird";
				$this->browserType = "browser";
				if ((eregi("firebird/0.6", $ua)) || (eregi("browser/0.6", $ua)))	$this->browserVersion = "0.6";
				elseif (eregi("firebird/0.7", $ua))	$this->browserVersion = "0.7";
			}
			elseif (eregi("firefox", $ua))
			{
				$this->browser = "Firefox";
				$this->browserType = "browser";
				if (eregi("firefox/0.9.1", $ua))	$this->browserVersion = "0.9.1";
				elseif (eregi("firefox/0.10", $ua))	$this->browserVersion = "0.10";
				elseif (eregi("firefox/0.9", $ua))	$this->browserVersion = "0.9";
				elseif (eregi("firefox/0.8", $ua))	$this->browserVersion = "0.8";
				elseif (eregi("firefox/1.0", $ua))	$this->browserVersion = "1.0";
				elseif (eregi("firefox/1.5", $ua))	$this->browserVersion = "1.5";
				elseif (eregi("firefox/2.0", $ua))	$this->browserVersion = "2.0";
			}
			elseif (eregi("frontpage", $ua))
			{
				$this->browser = "FrontPage";
				$this->browserType = "browser";
				if ((eregi("express 2", $ua)) || (eregi("frontpage 2", $ua)))	$this->browserVersion = "2";
				elseif (eregi("frontpage 3", $ua))	$this->browserVersion = "3";
				elseif (eregi("frontpage 4", $ua))	$this->browserVersion = "4";
				elseif (eregi("frontpage 5", $ua))	$this->browserVersion = "5";
				elseif (eregi("frontpage 6", $ua))	$this->browserVersion = "6";
			}
			elseif (eregi("galeon", $ua))
			{
				$this->browser = "Galeon";
				$this->browserType = "browser";
				if (eregi("galeon 0.1", $ua))			$this->browserVersion = "0.1";
				elseif (eregi("galeon/0.11.1", $ua))	$this->browserVersion = "0.11.1";
				elseif (eregi("galeon/0.11.2", $ua))	$this->browserVersion = "0.11.2";
				elseif (eregi("galeon/0.11.3", $ua))	$this->browserVersion = "0.11.3";
				elseif (eregi("galeon/0.11.5", $ua))	$this->browserVersion = "0.11.5";
				elseif (eregi("galeon/0.12.8", $ua))	$this->browserVersion = "0.12.8";
				elseif (eregi("galeon/0.12.7", $ua))	$this->browserVersion = "0.12.7";
				elseif (eregi("galeon/0.12.6", $ua))	$this->browserVersion = "0.12.6";
				elseif (eregi("galeon/0.12.5", $ua))	$this->browserVersion = "0.12.5";
				elseif (eregi("galeon/0.12.4", $ua))	$this->browserVersion = "0.12.4";
				elseif (eregi("galeon/0.12.3", $ua))	$this->browserVersion = "0.12.3";
				elseif (eregi("galeon/0.12.2", $ua))	$this->browserVersion = "0.12.2";
				elseif (eregi("galeon/0.12.1", $ua))	$this->browserVersion = "0.12.1";
				elseif (eregi("galeon/0.12", $ua))		$this->browserVersion = "0.12";
				elseif ((eregi("galeon/1", $ua)) || (eregi("galeon 1.0", $ua)))	$this->browserVersion = "1.0";
			}
			elseif (eregi("ibm web browser", $ua))
			{
				$this->browser = "IBM Web Browser";
				$this->browserType = "browser";
				if (eregi("rv:1.0.1", $ua))	$this->browserVersion = "1.0.1";
			}
			elseif (eregi("chimera", $ua))
			{
				$this->browser = "Chimera";
				$this->browserType = "browser";
				if (eregi("chimera/0.7", $ua))		$this->browserVersion = "0.7";
				elseif (eregi("chimera/0.6", $ua))	$this->browserVersion = "0.6";
				elseif (eregi("chimera/0.5", $ua))	$this->browserVersion = "0.5";
				elseif (eregi("chimera/0.4", $ua))	$this->browserVersion = "0.4";
			}
			elseif (eregi("icab", $ua))
			{
				$this->browser = "iCab";
			$this->browserType = "browser";
				if (eregi("icab/2.7.1", $ua))		$this->browserVersion = "2.7.1";
				elseif (eregi("icab/2.8.1", $ua))	$this->browserVersion = "2.8.1";
				elseif (eregi("icab/2.8.2", $ua))	$this->browserVersion = "2.8.2";
				elseif (eregi("icab 2.9", $ua))		$this->browserVersion = "2.9";
				elseif (eregi("icab 2.0", $ua))		$this->browserVersion = "2.0";
			}
			elseif (eregi("konqueror", $ua))
			{
				$this->browser = "Konqueror";
				$this->browserType = "browser";
				if (eregi("konqueror/3.5", $ua))		$this->browserVersion = "3.5";
				elseif (eregi("konqueror/3.4", $ua))	$this->browserVersion = "3.4";
				elseif (eregi("konqueror/3.3", $ua))	$this->browserVersion = "3.3";
				elseif (eregi("konqueror/3.2", $ua))	$this->browserVersion = "3.2";
				elseif (eregi("konqueror/3.1", $ua))	$this->browserVersion = "3.1";
				elseif (eregi("konqueror/3", $ua))		$this->browserVersion = "3.0";
				elseif (eregi("konqueror/2.2", $ua))	$this->browserVersion = "2.2";
				elseif (eregi("konqueror/2.1", $ua))	$this->browserVersion = "2.1";
				elseif (eregi("konqueror/1.1", $ua))	$this->browserVersion = "1.1";
			}
			elseif (eregi("liberate", $ua))
			{
				$this->browser = "Liberate";
				$this->browserType = "browser";
				if (eregi("dtv 1.2", $ua))		$this->browserVersion = "1.2";
				elseif (eregi("dtv 1.1", $ua))	$this->browserVersion = "1.1";
			}
			elseif (eregi("desktop/lx", $ua))
			{
				$this->browser = "Lycoris Desktop/LX";
				$this->browserType = "browser";
			}
			elseif (eregi("netbox", $ua))
			{
				$this->browser = "NetBox";
				$this->browserType = "browser";
				if (eregi("netbox/3.5", $ua))	$this->browserVersion = "3.5";
			}
			elseif (eregi("netcaptor", $ua))
			{
				$this->browser = "Netcaptor";
				$this->browserType = "browser";
				if (eregi("netcaptor 7.0", $ua))		$this->browserVersion = "7.0";
				elseif (eregi("netcaptor 7.1", $ua))	$this->browserVersion = "7.1";
				elseif (eregi("netcaptor 7.2", $ua))	$this->browserVersion = "7.2";
				elseif (eregi("netcaptor 7.5", $ua))	$this->browserVersion = "7.5";
				elseif (eregi("netcaptor 6.1", $ua))	$this->browserVersion = "6.1";
			}
			elseif (eregi("netpliance", $ua))
			{
				$this->browser = "Netpliance";
				$this->browserType = "browser";
			}
			elseif (eregi("netscape", $ua))
			{
				$this->browser = "Netscape";
				$this->browserType = "browser";
				if (eregi("netscape/7.1", $ua))		$this->browserVersion = "7.1";
				elseif (eregi("netscape/7.2", $ua))		$this->browserVersion = "7.2";
				elseif (eregi("netscape/7.0", $ua))		$this->browserVersion = "7.0";
				elseif (eregi("netscape6/6.2", $ua))	$this->browserVersion = "6.2";
				elseif (eregi("netscape6/6.1", $ua))	$this->browserVersion = "6.1";
				elseif (eregi("netscape6/6.0", $ua))	$this->browserVersion = "6.0";
			}
			elseif ((eregi("mozilla/5.0", $ua)) && (eregi("rv:", $ua)) && (eregi("gecko/", $ua)))
			{
				$this->browser = "Mozilla";
				$this->browserType = "browser";
				if (eregi("rv:1.0", $ua))		$this->browserVersion = "1.0";
				elseif (eregi("rv:1.1", $ua))	$this->browserVersion = "1.1";
				elseif (eregi("rv:1.2", $ua))	$this->browserVersion = "1.2";
				elseif (eregi("rv:1.3", $ua))	$this->browserVersion = "1.3";
				elseif (eregi("rv:1.4", $ua))	$this->browserVersion = "1.4";
				elseif (eregi("rv:1.5", $ua))	$this->browserVersion = "1.5";
				elseif (eregi("rv:1.6", $ua))	$this->browserVersion = "1.6";
				elseif (eregi("rv:1.7", $ua))	$this->browserVersion = "1.7";
				elseif (eregi("rv:1.8", $ua))	$this->browserVersion = "1.8";
			}
			elseif (eregi("offbyone", $ua))
			{
				$this->browser = "OffByOne";
				$this->browserType = "browser";
				if (eregi("mozilla/4.7", $ua))	$this->browserVersion = "3.4";
			}
			elseif (eregi("omniweb", $ua))
			{
				$this->browser = "OmniWeb";
				$this->browserType = "browser";
				if (eregi("omniweb/4.5", $ua))	$this->browserVersion = "4.5";
				elseif (eregi("omniweb/4.4", $ua))	$this->browserVersion = "4.4";
				elseif (eregi("omniweb/4.3", $ua))	$this->browserVersion = "4.3";
				elseif (eregi("omniweb/4.2", $ua))	$this->browserVersion = "4.2";
				elseif (eregi("omniweb/4.1", $ua))	$this->browserVersion = "4.1";
			}
			elseif (eregi("opera", $ua))
			{
				$this->browser = "Opera";
				$this->browserType = "browser";
				if ((eregi("opera/9.1", $ua)) || (eregi("opera 9.1", $ua)))		$this->browserVersion = "9.1";
				elseif ((eregi("opera/9.0", $ua)) || (eregi("opera 9.0", $ua)))	$this->browserVersion = "9.0";
				elseif ((eregi("opera/8.0", $ua)) || (eregi("opera 8.0", $ua)))	$this->browserVersion = "8.0";
				elseif ((eregi("opera/7.60", $ua)) || (eregi("opera 7.60", $ua)))	$this->browserVersion = "7.60";
				elseif ((eregi("opera/7.54", $ua)) || (eregi("opera 7.54", $ua)))	$this->browserVersion = "7.54";
				elseif ((eregi("opera/7.53", $ua)) || (eregi("opera 7.53", $ua)))	$this->browserVersion = "7.53";
				elseif ((eregi("opera/7.52", $ua)) || (eregi("opera 7.52", $ua)))	$this->browserVersion = "7.52";
				elseif ((eregi("opera/7.51", $ua)) || (eregi("opera 7.51", $ua)))	$this->browserVersion = "7.51";
				elseif ((eregi("opera/7.50", $ua)) || (eregi("opera 7.50", $ua)))	$this->browserVersion = "7.50";
				elseif ((eregi("opera/7.23", $ua)) || (eregi("opera 7.23", $ua)))	$this->browserVersion = "7.23";
				elseif ((eregi("opera/7.22", $ua)) || (eregi("opera 7.22", $ua)))	$this->browserVersion = "7.22";
				elseif ((eregi("opera/7.21", $ua)) || (eregi("opera 7.21", $ua)))		$this->browserVersion = "7.21";
				elseif ((eregi("opera/7.20", $ua)) || (eregi("opera 7.20", $ua)))	$this->browserVersion = "7.20";
				elseif ((eregi("opera/7.11", $ua)) || (eregi("opera 7.11", $ua)))	$this->browserVersion = "7.11";
				elseif ((eregi("opera/7.10", $ua)) || (eregi("opera 7.10", $ua)))	$this->browserVersion = "7.10";
				elseif ((eregi("opera/7.03", $ua)) || (eregi("opera 7.03", $ua)))	$this->browserVersion = "7.03";
				elseif ((eregi("opera/7.02", $ua)) || (eregi("opera 7.02", $ua)))	$this->browserVersion = "7.02";
				elseif ((eregi("opera/7.01", $ua)) || (eregi("opera 7.01", $ua)))	$this->browserVersion = "7.01";
				elseif ((eregi("opera/7.0", $ua)) || (eregi("opera 7.0", $ua)))	$this->browserVersion = "7.0";
				elseif ((eregi("opera/6.12", $ua)) || (eregi("opera 6.12", $ua)))	$this->browserVersion = "6.12";
				elseif ((eregi("opera/6.11", $ua)) || (eregi("opera 6.11", $ua)))	$this->browserVersion = "6.11";
				elseif ((eregi("opera/6.1", $ua)) || (eregi("opera 6.1", $ua)))	$this->browserVersion = "6.1";
				elseif ((eregi("opera/6.	0", $ua)) || (eregi("opera 6.0", $ua)))	$this->browserVersion = "6.0";
				elseif ((eregi("opera/5.12", $ua)) || (eregi("opera 5.12", $ua)))	$this->browserVersion = "5.12";
				elseif ((eregi("opera/5.0", $ua)) || (eregi("opera 5.0", $ua)))	$this->browserVersion = "5.0";
				elseif ((eregi("opera/4", $ua)) || (eregi("opera 4", $ua)))		$this->browserVersion = "4";
			}
			elseif (eregi("oracle", $ua))
			{
				$this->browser = "Oracle PowerBrowser";
				$this->browserType = "browser";
				if (eregi("(tm)/1.0a", $ua))		$this->browserVersion = "1.0a";
				elseif (eregi("oracle 1.5", $ua))	$this->browserVersion = "1.5";
			}
			elseif (eregi("phoenix", $ua))
			{
				$this->browser = "Phoenix";
				$this->browserType = "browser";
				if (eregi("phoenix/0.4", $ua))		$this->browserVersion = "0.4";
				elseif (eregi("phoenix/0.5", $ua))	$this->browserVersion = "0.5";
			}
			elseif (eregi("planetweb", $ua))
			{
				$this->browser = "PlanetWeb";
				$this->browserType = "browser";
				if (eregi("planetweb/2.606", $ua))		$this->browserVersion = "2.6";
				elseif (eregi("planetweb/1.125", $ua))	$this->browserVersion = "3";
			}
			elseif (eregi("powertv", $ua))
			{
				$this->browser = "PowerTV";
				$this->browserType = "browser";
				if (eregi("powertv/1.5", $ua))	$this->browserVersion = "1.5";
			}
			elseif (eregi("prodigy", $ua))
			{
				$this->browser = "Prodigy";
				if (eregi("wb/3.2e", $ua))	$this->browserVersion = "3.2e";
				elseif (eregi("rv: 1.", $ua))	$this->browserVersion = "1.0";
			}
			elseif ((eregi("voyager", $ua)) || ((eregi("qnx", $ua))) && (eregi("rv: 1.", $ua)))
			{
				$this->browser = "Voyager";
				if (eregi("2.03b", $ua))	$this->browserVersion = "2.03b";
				elseif (eregi("wb/win32/3.4g", $ua))	$this->browserVersion = "3.4g";
			}
			elseif (eregi("quicktime", $ua))
			{
				$this->browser = "QuickTime";
				if (eregi("qtver=5", $ua))		$this->browserVersion = "5.0";
				elseif (eregi("qtver=6.0", $ua))	$this->browserVersion = "6.0";
				elseif (eregi("qtver=6.1", $ua))	$this->browserVersion = "6.1";
				elseif (eregi("qtver=6.2", $ua))	$this->browserVersion = "6.2";
				elseif (eregi("qtver=6.3", $ua))	$this->browserVersion = "6.3";
				elseif (eregi("qtver=6.4", $ua))	$this->browserVersion = "6.4";
				elseif (eregi("qtver=6.5", $ua))	$this->browserVersion = "6.5";
			}
			elseif (eregi("safari", $ua))
			{
				$this->browser = "Safari";
				if (eregi("safari/48", $ua))		$this->browserVersion = "0.48";
				elseif (eregi("safari/49", $ua))	$this->browserVersion = "0.49";
				elseif (eregi("safari/51", $ua))	$this->browserVersion = "0.51";
				elseif (eregi("safari/60", $ua))	$this->browserVersion = "0.60";
				elseif (eregi("safari/61", $ua))	$this->browserVersion = "0.61";
				elseif (eregi("safari/62", $ua))	$this->browserVersion = "0.62";
				elseif (eregi("safari/63", $ua))	$this->browserVersion = "0.63";
				elseif (eregi("safari/64", $ua))	$this->browserVersion = "0.64";
				elseif (eregi("safari/65", $ua))	$this->browserVersion = "0.65";
				elseif (eregi("safari/66", $ua))	$this->browserVersion = "0.66";
				elseif (eregi("safari/67", $ua))	$this->browserVersion = "0.67";
				elseif (eregi("safari/68", $ua))	$this->browserVersion = "0.68";
				elseif (eregi("safari/69", $ua))	$this->browserVersion = "0.69";
				elseif (eregi("safari/70", $ua))	$this->browserVersion = "0.70";
				elseif (eregi("safari/71", $ua))	$this->browserVersion = "0.71";
				elseif (eregi("safari/72", $ua))	$this->browserVersion = "0.72";
				elseif (eregi("safari/73", $ua))	$this->browserVersion = "0.73";
				elseif (eregi("safari/74", $ua))	$this->browserVersion = "0.74";
				elseif (eregi("safari/80", $ua))	$this->browserVersion = "0.80";
				elseif (eregi("safari/83", $ua))	$this->browserVersion = "0.83";
				elseif (eregi("safari/84", $ua))	$this->browserVersion = "0.84";
				elseif (eregi("safari/85", $ua))	$this->browserVersion = "0.85";
				elseif (eregi("safari/90", $ua))	$this->browserVersion = "0.90";
				elseif (eregi("safari/92", $ua))	$this->browserVersion = "0.92";
				elseif (eregi("safari/93", $ua))	$this->browserVersion = "0.93";
				elseif (eregi("safari/94", $ua))	$this->browserVersion = "0.94";
				elseif (eregi("safari/95", $ua))	$this->browserVersion = "0.95";
				elseif (eregi("safari/96", $ua))	$this->browserVersion = "0.96";
				elseif (eregi("safari/97", $ua))	$this->browserVersion = "0.97";
				elseif (eregi("safari/125", $ua))	$this->browserVersion = "1.25";
			}
			elseif (eregi("sextatnt", $ua))
			{
				$this->browser = "Tango";
				if (eregi("sextant v3.0", $ua))	$this->browserVersion = "3.0";
			}
			elseif (eregi("sharpreader", $ua))
			{
				$this->browser = "SharpReader";
				if (eregi("sharpreader/0.9.5", $ua))	$this->browserVersion = "0.9.5";
			}
			elseif (eregi("elinks", $ua))
			{
				$this->browser = "ELinks";
				if (eregi("0.3", $ua))	$this->browserVersion = "0.3";
				elseif (eregi("0.4", $ua))	$this->browserVersion = "0.4";
				elseif (eregi("0.9", $ua))	$this->browserVersion = "0.9";
			}
			elseif (eregi("links", $ua))
			{
				$this->browser = "Links";
				if (eregi("0.9", $ua))	$this->browserVersion = "0.9";
				elseif (eregi("2.0", $ua))	$this->browserVersion = "2.0";
				elseif (eregi("2.1", $ua))	$this->browserVersion = "2.1";
			}
			elseif (eregi("lynx", $ua))
			{
				$this->browser = "Lynx";
				if (eregi("lynx/2.3", $ua))	$this->browserVersion = "2.3";
				elseif (eregi("lynx/2.4", $ua))	$this->browserVersion = "2.4";
				elseif ((eregi("lynx/2.5", $ua)) || (eregi("lynx 2.5", $ua)))	$this->browserVersion = "2.5";
				elseif (eregi("lynx/2.6", $ua))	$this->browserVersion = "2.6";
				elseif (eregi("lynx/2.7", $ua))	$this->browserVersion = "2.7";
				elseif (eregi("lynx/2.8", $ua))	$this->browserVersion = "2.8";
			}
			elseif (eregi("webexplorer", $ua))
			{
				$this->browser = "WebExplorer";
				if (eregi("dll/v1.1", $ua))	$this->browserVersion = "1.1";
			}
			elseif (eregi("wget", $ua))
			{
				$this->browser = "WGet";
				if (eregi("Wget/1.9", $ua))	$this->browserVersion = "1.9";
				if (eregi("Wget/1.8", $ua))	$this->browserVersion = "1.8";
			}
			elseif (eregi("webtv", $ua))
			{
				$this->browser = "WebTV";
				if (eregi("webtv/1.0", $ua))		$this->browserVersion = "1.0";
				elseif (eregi("webtv/1.1", $ua))	$this->browserVersion = "1.1";
				elseif (eregi("webtv/1.2", $ua))	$this->browserVersion = "1.2";
				elseif (eregi("webtv/2.2", $ua))	$this->browserVersion = "2.2";
				elseif (eregi("webtv/2.5", $ua))	$this->browserVersion = "2.5";
				elseif (eregi("webtv/2.6", $ua))	$this->browserVersion = "2.6";
				elseif (eregi("webtv/2.7", $ua))	$this->browserVersion = "2.7";
			}
			elseif (eregi("yandex", $ua))
			{
				$this->browser = "Yandex";
				if (eregi("/1.01", $ua))	$this->browserVersion = "1.01";
				elseif (eregi("/1.03", $ua))	$this->browserVersion = "1.03";
			}
			elseif ((eregi("mspie", $ua)) || ((eregi("msie", $ua))) && (eregi("windows ce", $ua)))
			{
				$this->browser = "Pocket Internet Explorer";
				if (eregi("mspie 1.1", $ua))		$this->browserVersion = "1.1";
				elseif (eregi("mspie 2.0", $ua))	$this->browserVersion = "2.0";
				elseif (eregi("msie 3.02", $ua))	$this->browserVersion = "3.02";
			}
			elseif (eregi("UP.Browser/", $ua))
			{
				$this->browser = "UP Browser";
				if (eregi("Browser/7.0", $ua))		$this->browserVersion = "7.0";
			}
			elseif (eregi("msie", $ua))
			{
				$this->browser = "Internet Explorer";
				if (eregi("msie 7.0", $ua))			$this->browserVersion = "7.0";
				elseif (eregi("msie 6.0", $ua))		$this->browserVersion = "6.0";
				elseif (eregi("msie 5.5", $ua))		$this->browserVersion = "5.5";
				elseif (eregi("msie 5.01", $ua))	$this->browserVersion = "5.01";
				elseif (eregi("msie 5.23", $ua))	$this->browserVersion = "5.23";
				elseif (eregi("msie 5.22", $ua))	$this->browserVersion = "5.22";
				elseif (eregi("msie 5.2.2", $ua))	$this->browserVersion = "5.2.2";
				elseif (eregi("msie 5.1b1", $ua))	$this->browserVersion = "5.1b1";
				elseif (eregi("msie 5.17", $ua))	$this->browserVersion = "5.17";
				elseif (eregi("msie 5.16", $ua))	$this->browserVersion = "5.16";
				elseif (eregi("msie 5.12", $ua))	$this->browserVersion = "5.12";
				elseif (eregi("msie 5.0b1", $ua))	$this->browserVersion = "5.0b1";
				elseif (eregi("msie 5.0", $ua))		$this->browserVersion = "5.0";
				elseif (eregi("msie 5.21", $ua))	$this->browserVersion = "5.21";
				elseif (eregi("msie 5.2", $ua))		$this->browserVersion = "5.2";
				elseif (eregi("msie 5.15", $ua))	$this->browserVersion = "5.15";
				elseif (eregi("msie 5.14", $ua))	$this->browserVersion = "5.14";
				elseif (eregi("msie 5.13", $ua))	$this->browserVersion = "5.13";
				elseif (eregi("msie 4.5", $ua))		$this->browserVersion = "4.5";
				elseif (eregi("msie 4.01", $ua))	$this->browserVersion = "4.01";
				elseif (eregi("msie 4.0b2", $ua))	$this->browserVersion = "4.0b2";
				elseif (eregi("msie 4.0b1", $ua))	$this->browserVersion = "4.0b1";
				elseif (eregi("msie 4", $ua))		$this->browserVersion = "4.0";
				elseif (eregi("msie 3", $ua))		$this->browserVersion = "3.0";
				elseif (eregi("msie 2", $ua))		$this->browserVersion = "2.0";
				elseif (eregi("msie 1.5", $ua))		$this->browserVersion = "1.5";
			}
			elseif (eregi("iexplore", $ua))
			{
				$this->browser = "Internet Explorer";
			}
			elseif (eregi("mozilla", $ua))
			{
				$this->browser = "Netscape";
				if (eregi("mozilla/5.0", $ua))	$this->browserVersion = "5.0";
				else if (eregi("mozilla/4.9", $ua))	$this->browserVersion = "4.9";
				else if (eregi("mozilla/4.8", $ua))	$this->browserVersion = "4.8";
				elseif (eregi("mozilla/4.7", $ua))	$this->browserVersion = "4.7";
				elseif (eregi("mozilla/4.6", $ua))	$this->browserVersion = "4.6";
				elseif (eregi("mozilla/4.5", $ua))	$this->browserVersion = "4.5";
				elseif (eregi("mozilla/4.0", $ua))	$this->browserVersion = "4.0";
				elseif (eregi("mozilla/3.0", $ua))	$this->browserVersion = "3.0";
				elseif (eregi("mozilla/2.0", $ua))	$this->browserVersion = "2.0";
			}
		}
	}
}
?>