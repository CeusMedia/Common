<?php
/**
 *	Sniffer for browsing HTTP Clients via User Agents.
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
 *	Sniffer for browsing HTTP Clients via User Agents.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			12.08.2005
 */
class Browser
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
		if (preg_match("~msnbot~i", $ua)){
			$this->browser = "MSN Bot";
			if (preg_match("~msnbot/0.11~i", $ua))	$this->browserVersion = "0.11";
			elseif (preg_match("~msnbot/0.30~i", $ua))	$this->browserVersion = "0.3";
			elseif (preg_match("~msnbot/1.0~i", $ua))	$this->browserVersion = "1.0";
		}
		elseif (preg_match("~almaden~i", $ua))
			$this->browser = "IBM Almaden Crawler";
		elseif (preg_match("~BecomeBot~i", $ua)){
			$this->browser = "BecomeBot";
			if (preg_match("~becomebot/1.23~i", $ua))	$this->browserVersion = "1.23";
		}
		elseif (preg_match("~Link-Checker-Pro~i", $ua))
			$this->browser = "Link Checker Pro";
		elseif (preg_match("~ia_archiver~i", $ua))
			$this->browser = "Alexa";
		elseif ((preg_match("~googlebot~i", $ua)) || (preg_match("~google~i", $ua))){
			$this->browser = "Google Bot";
			if ((preg_match("~googlebot/2.1~i", $ua)) || (preg_match("~google/2.1~i", $ua)))	$this->browserVersion = "2.1";
		}
		elseif (preg_match("~surveybot~i", $ua)){
			$this->browser = "Survey Bot";
			if (preg_match("~surveybot/2.3~i", $ua))	$this->browserVersion = "2.3";
		}
		elseif (preg_match("~zyborg~i", $ua)){
			$this->browser = "ZyBorg";
			if (preg_match("~zyborg/1.0~i", $ua))	$this->browserVersion = "1.0";
		}
		elseif (preg_match("~w3c-checklink~i", $ua)){
			$this->browser = "W3C Checklink";
			if (preg_match("~checklink/3.6~i", $ua))	$this->browserVersion = "3.6";
		}
		elseif (preg_match("~linkwalker~i", $ua)){
			$this->browser = "LinkWalker";
		}
		elseif (preg_match("~fast-webcrawler~i", $ua)){
			$this->browser = "Fast WebCrawler";
			if (preg_match("~webcrawler/3.8~i", $ua))	$this->browserVersion = "3.8";
		}
		elseif ((preg_match("~yahoo~i", $ua)) && (preg_match("~slurp~i", $ua))){
			$this->browser = "Yahoo! Slurp";
		}
		elseif (preg_match("~naverbot~i", $ua)){
			$this->browser = "NaverBot";
			if (preg_match("~dloader/1.5~i", $ua))	$this->browserVersion = "1.5";
		}
		elseif (preg_match("~converacrawler~i", $ua)){
			$this->browser = "ConveraCrawler";
			if (preg_match("~converacrawler/0.5~i", $ua))	$this->browserVersion = "0.5";
		}
		elseif (preg_match("~w3c_validator~i", $ua)){
			$this->browser = "W3C Validator";
			if (preg_match("~w3c_validator/1.305~i", $ua))	$this->browserVersion = "1.305";
		}
		elseif (preg_match("~innerprisebot~i", $ua)){
			$this->browser = "Innerprise";
			if (preg_match("~innerprise/1.0~i", $ua))	$this->browserVersion = "1.0";
		}
		elseif (preg_match("~topicspy~i", $ua)){
			$this->browser = "Topicspy Checkbot";
		}
		elseif (preg_match("~poodle predictor~i", $ua)){
			$this->browser = "Poodle Predictor";
			if (preg_match("~poodle predictor 1.0~i", $ua))	$this->browserVersion = "1.0";
		}
		elseif (preg_match("~ichiro~i", $ua)){
			$this->browser = "Ichiro";
			if (preg_match("~ichiro/1.0~i", $ua))	$this->browserVersion = "1.0";
		}
		elseif (preg_match("~link checker pro~i", $ua)){
			$this->browser = "Link Checker Pro";
			if (preg_match("~link checker pro 3.2.16~i", $ua))	$this->browserVersion = "3.2.16";
		}
		elseif (preg_match("~grub-client~i", $ua)){
			$this->browser = "Grub client";
			if (preg_match("~grub-client-2.3~i", $ua))	$this->browserVersion = "2.3";
		}
		elseif (preg_match("~gigabot~i", $ua)){
			$this->browser = "Gigabot";
			if (preg_match("~gigabot/2.0~i", $ua))	$this->browserVersion = "2.0";
		}
		elseif (preg_match("~psbot~i", $ua)){
			$this->browser = "PSBot";
			if (preg_match("~psbot/0.1~i", $ua))	$this->browserVersion = "0.1";
		}
		elseif (preg_match("~mj12bot~i", $ua)){
			$this->browser = "MJ12Bot";
			if (preg_match("~mj12bot/v0.5~i", $ua))	$this->browserVersion = "0.5";
		}
		elseif (preg_match("~nextgensearchbot~i", $ua)){
			$this->browser = "NextGenSearchBot";
			if (preg_match("~nextgensearchbot 1~i", $ua))	$this->browserVersion = "1";
		}
		elseif (preg_match("~tutorgigbot~i", $ua)){
			$this->browser = "TutorGigBot";
			if (preg_match("~bot/1.5~i", $ua))	$this->browserVersion = "1.5";
		}
		elseif (preg_match("~NG~", $ua)){
			$this->browser = "Exabot NG";
			if (preg_match("~ng/2.0~i", $ua))	$this->browserVersion = "2.0";
		}
		elseif (preg_match("~gaisbot~i", $ua)){
			$this->browser = "Gaisbot";
			if (preg_match("~gaisbot/3.0~i", $ua))	$this->browserVersion = "3.0";
		}
		elseif (preg_match("~xenu link sleuth~i", $ua)){
			$this->browser = "Xenu Link Sleuth";
			if (preg_match("~xenu link sleuth 1.2~i", $ua))	$this->browserVersion = "1.2";
		}
		elseif (preg_match("~turnitinbot~i", $ua)){
			$this->browser = "TurnitinBot";
			if (preg_match("~turnitinbot/2.0~i", $ua))	$this->browserVersion = "2.0";
		}
		elseif (preg_match("~iconsurf~i", $ua)){
			$this->browser = "IconSurf";
			if (preg_match("~iconsurf/2.0~i", $ua))	$this->browserVersion = "2.0";
		}
		elseif (preg_match("~zoe indexer~i", $ua)){
			$this->browser = "Zoe Indexer";
			if (preg_match("~v1.x~i", $ua))	$this->browserVersion = "1";
		}
		else{
			$this->browserType = "browser";
			if (preg_match("~amaya~i", $ua)){
				$this->browser = "amaya";
				if (preg_match("~amaya/5.0~i", $ua))		$this->browserVersion = "5.0";
				elseif (preg_match("~amaya/5.1~i", $ua))	$this->browserVersion = "5.1";
				elseif (preg_match("~amaya/5.2~i", $ua))	$this->browserVersion = "5.2";
				elseif (preg_match("~amaya/5.3~i", $ua))	$this->browserVersion = "5.3";
				elseif (preg_match("~amaya/6.0~i", $ua))	$this->browserVersion = "6.0";
				elseif (preg_match("~amaya/6.1~i", $ua))	$this->browserVersion = "6.1";
				elseif (preg_match("~amaya/6.2~i", $ua))	$this->browserVersion = "6.2";
				elseif (preg_match("~amaya/6.3~i", $ua))	$this->browserVersion = "6.3";
				elseif (preg_match("~amaya/6.4~i", $ua))	$this->browserVersion = "6.4";
				elseif (preg_match("~amaya/7.0~i", $ua))	$this->browserVersion = "7.0";
				elseif (preg_match("~amaya/7.1~i", $ua))	$this->browserVersion = "7.1";
				elseif (preg_match("~amaya/7.2~i", $ua))	$this->browserVersion = "7.2";
				elseif (preg_match("~amaya/8.0~i", $ua))	$this->browserVersion = "8.0";
			}
			elseif ((preg_match("~aol~i", $ua)) && !(preg_match("~msie~i", $ua))){
				$this->browser = "AOL";
				$this->browserType = "browser";
				if ((preg_match("~aol 7.0~i", $ua)) || (preg_match("~aol/7.0~i", $ua)))	$this->browserVersion = "7.0";
			}
			elseif ((preg_match("~aweb~i", $ua)) || (preg_match("~amigavoyager~i", $ua))){
				$this->browser = "AWeb";
				$this->browserType = "browser";
				if (preg_match("~voyager/1.0~i", $ua))	$this->browserVersion = "1.0";
				elseif (preg_match("~voyager/2.95~i", $ua))	$this->browserVersion = "2.95";
				elseif ((preg_match("~voyager/3~i", $ua)) || (preg_match("~aweb/3.0~i", $ua)))	$this->browserVersion = "3.0";
				elseif (preg_match("~aweb/3.1~i", $ua))	$this->browserVersion = "3.1";
				elseif (preg_match("~aweb/3.2~i", $ua))	$this->browserVersion = "3.2";
				elseif (preg_match("~aweb/3.3~i", $ua))	$this->browserVersion = "3.3";
				elseif (preg_match("~aweb/3.4~i", $ua))	$this->browserVersion = "3.4";
				elseif (preg_match("~aweb/3.9~i", $ua))	$this->browserVersion = "3.9";
			}
			elseif (preg_match("~beonex~i", $ua)){
				$this->browser = "Beonex";
				$this->browserType = "browser";
				if (preg_match("~beonex/0.8.2~i", $ua))		$this->browserVersion = "0.8.2";
				elseif (preg_match("~beonex/0.8.1~i", $ua))	$this->browserVersion = "0.8.1";
				elseif (preg_match("~beonex/0.8~i", $ua))		$this->browserVersion = "0.8";
			}
			elseif (preg_match("~camino~i", $ua)){
				$this->browser = "Camino";
				$this->browserType = "browser";
				if (preg_match("~camino/0.7~i", $ua))	$this->browserVersion = "0.7";
			}
			elseif (preg_match("~cyberdog~i", $ua)){
				$this->browser = "Cyberdog";
				$this->browserType = "browser";
				if (preg_match("~cybergog/1.2~i", $ua))		$this->browserVersion = "1.2";
				elseif (preg_match("~cyberdog/2.0~i", $ua))		$this->browserVersion = "2.0";
				elseif (preg_match("~cyberdog/2.0b1~i", $ua))	$this->browserVersion = "2.0b1";
			}
			elseif (preg_match("~dillo~i", $ua)){
				$this->browser = "Dillo";
				$this->browserType = "browser";
				if (preg_match("~dillo/0.6.6~i", $ua))		$this->browserVersion = "0.6.6";
				elseif (preg_match("~dillo/0.7.2~i", $ua))	$this->browserVersion = "0.7.2";
				elseif (preg_match("~dillo/0.7.3~i", $ua))	$this->browserVersion = "0.7.3";
				elseif (preg_match("~dillo/0.8~i", $ua))		$this->browserVersion = "0.8";
			}
			elseif (preg_match("~doris~i", $ua)){
				$this->browser = "Doris";
				$this->browserType = "browser";
				if (preg_match("~doris/1.10~i", $ua))	$this->browserVersion = "1.10";
			}
			elseif (preg_match("~emacs~i", $ua)){
				$this->browser = "Emacs";
				$this->browserType = "browser";
				if (preg_match("~emacs/w3/2~i", $ua))	$this->browserVersion = "2";
				elseif (preg_match("~emacs/w3/3~i", $ua))	$this->browserVersion = "3";
				elseif (preg_match("~emacs/w3/4~i", $ua))	$this->browserVersion = "4";
			}
			elseif (preg_match("~firebird~i", $ua)){
				$this->browser = "Firebird";
				$this->browserType = "browser";
				if ((preg_match("~firebird/0.6~i", $ua)) || (preg_match("~browser/0.6~i", $ua)))	$this->browserVersion = "0.6";
				elseif (preg_match("~firebird/0.7~i", $ua))	$this->browserVersion = "0.7";
			}
			elseif (preg_match("~firefox~i", $ua)){
				$this->browser = "Firefox";
				$this->browserType = "browser";
				if (preg_match("~firefox/0.9.1~i", $ua))	$this->browserVersion = "0.9.1";
				elseif (preg_match("~firefox/0.10~i", $ua))	$this->browserVersion = "0.10";
				elseif (preg_match("~firefox/0.9~i", $ua))	$this->browserVersion = "0.9";
				elseif (preg_match("~firefox/0.8~i", $ua))	$this->browserVersion = "0.8";
				elseif (preg_match("~firefox/1.0~i", $ua))	$this->browserVersion = "1.0";
				elseif (preg_match("~firefox/1.5~i", $ua))	$this->browserVersion = "1.5";
				elseif (preg_match("~firefox/2.0~i", $ua))	$this->browserVersion = "2.0";
			}
			elseif (preg_match("~frontpage~i", $ua)){
				$this->browser = "FrontPage";
				$this->browserType = "browser";
				if ((preg_match("~express 2~i", $ua)) || (preg_match("~frontpage 2~i", $ua)))	$this->browserVersion = "2";
				elseif (preg_match("~frontpage 3~i", $ua))	$this->browserVersion = "3";
				elseif (preg_match("~frontpage 4~i", $ua))	$this->browserVersion = "4";
				elseif (preg_match("~frontpage 5~i", $ua))	$this->browserVersion = "5";
				elseif (preg_match("~frontpage 6~i", $ua))	$this->browserVersion = "6";
			}
			elseif (preg_match("~galeon~i", $ua)){
				$this->browser = "Galeon";
				$this->browserType = "browser";
				if (preg_match("~galeon 0.1~i", $ua))			$this->browserVersion = "0.1";
				elseif (preg_match("~galeon/0.11.1~i", $ua))	$this->browserVersion = "0.11.1";
				elseif (preg_match("~galeon/0.11.2~i", $ua))	$this->browserVersion = "0.11.2";
				elseif (preg_match("~galeon/0.11.3~i", $ua))	$this->browserVersion = "0.11.3";
				elseif (preg_match("~galeon/0.11.5~i", $ua))	$this->browserVersion = "0.11.5";
				elseif (preg_match("~galeon/0.12.8~i", $ua))	$this->browserVersion = "0.12.8";
				elseif (preg_match("~galeon/0.12.7~i", $ua))	$this->browserVersion = "0.12.7";
				elseif (preg_match("~galeon/0.12.6~i", $ua))	$this->browserVersion = "0.12.6";
				elseif (preg_match("~galeon/0.12.5~i", $ua))	$this->browserVersion = "0.12.5";
				elseif (preg_match("~galeon/0.12.4~i", $ua))	$this->browserVersion = "0.12.4";
				elseif (preg_match("~galeon/0.12.3~i", $ua))	$this->browserVersion = "0.12.3";
				elseif (preg_match("~galeon/0.12.2~i", $ua))	$this->browserVersion = "0.12.2";
				elseif (preg_match("~galeon/0.12.1~i", $ua))	$this->browserVersion = "0.12.1";
				elseif (preg_match("~galeon/0.12~i", $ua))		$this->browserVersion = "0.12";
				elseif ((preg_match("~galeon/1~i", $ua)) || (preg_match("~galeon 1.0~i", $ua)))	$this->browserVersion = "1.0";
			}
			elseif (preg_match("~ibm web browser~i", $ua)){
				$this->browser = "IBM Web Browser";
				$this->browserType = "browser";
				if (preg_match("~rv:1.0.1~i", $ua))	$this->browserVersion = "1.0.1";
			}
			elseif (preg_match("~chimera~i", $ua)){
				$this->browser = "Chimera";
				$this->browserType = "browser";
				if (preg_match("~chimera/0.7~i", $ua))		$this->browserVersion = "0.7";
				elseif (preg_match("~chimera/0.6~i", $ua))	$this->browserVersion = "0.6";
				elseif (preg_match("~chimera/0.5~i", $ua))	$this->browserVersion = "0.5";
				elseif (preg_match("~chimera/0.4~i", $ua))	$this->browserVersion = "0.4";
			}
			elseif (preg_match("~icab~i", $ua)){
				$this->browser = "iCab";
			$this->browserType = "browser";
				if (preg_match("~icab/2.7.1~i", $ua))		$this->browserVersion = "2.7.1";
				elseif (preg_match("~icab/2.8.1~i", $ua))	$this->browserVersion = "2.8.1";
				elseif (preg_match("~icab/2.8.2~i", $ua))	$this->browserVersion = "2.8.2";
				elseif (preg_match("~icab 2.9~i", $ua))		$this->browserVersion = "2.9";
				elseif (preg_match("~icab 2.0~i", $ua))		$this->browserVersion = "2.0";
			}
			elseif (preg_match("~konqueror~i", $ua)){
				$this->browser = "Konqueror";
				$this->browserType = "browser";
				if (preg_match("~konqueror/3.5~i", $ua))		$this->browserVersion = "3.5";
				elseif (preg_match("~konqueror/3.4~i", $ua))	$this->browserVersion = "3.4";
				elseif (preg_match("~konqueror/3.3~i", $ua))	$this->browserVersion = "3.3";
				elseif (preg_match("~konqueror/3.2~i", $ua))	$this->browserVersion = "3.2";
				elseif (preg_match("~konqueror/3.1~i", $ua))	$this->browserVersion = "3.1";
				elseif (preg_match("~konqueror/3~i", $ua))		$this->browserVersion = "3.0";
				elseif (preg_match("~konqueror/2.2~i", $ua))	$this->browserVersion = "2.2";
				elseif (preg_match("~konqueror/2.1~i", $ua))	$this->browserVersion = "2.1";
				elseif (preg_match("~konqueror/1.1~i", $ua))	$this->browserVersion = "1.1";
			}
			elseif (preg_match("~liberate~i", $ua)){
				$this->browser = "Liberate";
				$this->browserType = "browser";
				if (preg_match("~dtv 1.2~i", $ua))		$this->browserVersion = "1.2";
				elseif (preg_match("~dtv 1.1~i", $ua))	$this->browserVersion = "1.1";
			}
			elseif (preg_match("~desktop/lx~i", $ua)){
				$this->browser = "Lycoris Desktop/LX";
				$this->browserType = "browser";
			}
			elseif (preg_match("~netbox~i", $ua)){
				$this->browser = "NetBox";
				$this->browserType = "browser";
				if (preg_match("~netbox/3.5~i", $ua))	$this->browserVersion = "3.5";
			}
			elseif (preg_match("~netcaptor~i", $ua)){
				$this->browser = "Netcaptor";
				$this->browserType = "browser";
				if (preg_match("~netcaptor 7.0~i", $ua))		$this->browserVersion = "7.0";
				elseif (preg_match("~netcaptor 7.1~i", $ua))	$this->browserVersion = "7.1";
				elseif (preg_match("~netcaptor 7.2~i", $ua))	$this->browserVersion = "7.2";
				elseif (preg_match("~netcaptor 7.5~i", $ua))	$this->browserVersion = "7.5";
				elseif (preg_match("~netcaptor 6.1~i", $ua))	$this->browserVersion = "6.1";
			}
			elseif (preg_match("~netpliance~i", $ua)){
				$this->browser = "Netpliance";
				$this->browserType = "browser";
			}
			elseif (preg_match("~netscape~i", $ua)){
				$this->browser = "Netscape";
				$this->browserType = "browser";
				if (preg_match("~netscape/7.1~i", $ua))		$this->browserVersion = "7.1";
				elseif (preg_match("~netscape/7.2~i", $ua))		$this->browserVersion = "7.2";
				elseif (preg_match("~netscape/7.0~i", $ua))		$this->browserVersion = "7.0";
				elseif (preg_match("~netscape6/6.2~i", $ua))	$this->browserVersion = "6.2";
				elseif (preg_match("~netscape6/6.1~i", $ua))	$this->browserVersion = "6.1";
				elseif (preg_match("~netscape6/6.0~i", $ua))	$this->browserVersion = "6.0";
			}
			elseif ((preg_match("~mozilla/5.0~i", $ua)) && (preg_match("~rv:~i", $ua)) && (preg_match("~gecko/~i", $ua))){
				$this->browser = "Mozilla";
				$this->browserType = "browser";
				if (preg_match("~rv:1.0~i", $ua))		$this->browserVersion = "1.0";
				elseif (preg_match("~rv:1.1~i", $ua))	$this->browserVersion = "1.1";
				elseif (preg_match("~rv:1.2~i", $ua))	$this->browserVersion = "1.2";
				elseif (preg_match("~rv:1.3~i", $ua))	$this->browserVersion = "1.3";
				elseif (preg_match("~rv:1.4~i", $ua))	$this->browserVersion = "1.4";
				elseif (preg_match("~rv:1.5~i", $ua))	$this->browserVersion = "1.5";
				elseif (preg_match("~rv:1.6~i", $ua))	$this->browserVersion = "1.6";
				elseif (preg_match("~rv:1.7~i", $ua))	$this->browserVersion = "1.7";
				elseif (preg_match("~rv:1.8~i", $ua))	$this->browserVersion = "1.8";
			}
			elseif (preg_match("~offbyone~i", $ua)){
				$this->browser = "OffByOne";
				$this->browserType = "browser";
				if (preg_match("~mozilla/4.7~i", $ua))	$this->browserVersion = "3.4";
			}
			elseif (preg_match("~omniweb~i", $ua)){
				$this->browser = "OmniWeb";
				$this->browserType = "browser";
				if (preg_match("~omniweb/4.5~i", $ua))	$this->browserVersion = "4.5";
				elseif (preg_match("~omniweb/4.4~i", $ua))	$this->browserVersion = "4.4";
				elseif (preg_match("~omniweb/4.3~i", $ua))	$this->browserVersion = "4.3";
				elseif (preg_match("~omniweb/4.2~i", $ua))	$this->browserVersion = "4.2";
				elseif (preg_match("~omniweb/4.1~i", $ua))	$this->browserVersion = "4.1";
			}
			elseif (preg_match("~opera~i", $ua)){
				$this->browser = "Opera";
				$this->browserType = "browser";
				if ((preg_match("~opera/9.1~i", $ua)) || (preg_match("~opera 9.1~i", $ua)))		$this->browserVersion = "9.1";
				elseif ((preg_match("~opera/9.0~i", $ua)) || (preg_match("~opera 9.0~i", $ua)))	$this->browserVersion = "9.0";
				elseif ((preg_match("~opera/8.0~i", $ua)) || (preg_match("~opera 8.0~i", $ua)))	$this->browserVersion = "8.0";
				elseif ((preg_match("~opera/7.60~i", $ua)) || (preg_match("~opera 7.60~i", $ua)))	$this->browserVersion = "7.60";
				elseif ((preg_match("~opera/7.54~i", $ua)) || (preg_match("~opera 7.54~i", $ua)))	$this->browserVersion = "7.54";
				elseif ((preg_match("~opera/7.53~i", $ua)) || (preg_match("~opera 7.53~i", $ua)))	$this->browserVersion = "7.53";
				elseif ((preg_match("~opera/7.52~i", $ua)) || (preg_match("~opera 7.52~i", $ua)))	$this->browserVersion = "7.52";
				elseif ((preg_match("~opera/7.51~i", $ua)) || (preg_match("~opera 7.51~i", $ua)))	$this->browserVersion = "7.51";
				elseif ((preg_match("~opera/7.50~i", $ua)) || (preg_match("~opera 7.50~i", $ua)))	$this->browserVersion = "7.50";
				elseif ((preg_match("~opera/7.23~i", $ua)) || (preg_match("~opera 7.23~i", $ua)))	$this->browserVersion = "7.23";
				elseif ((preg_match("~opera/7.22~i", $ua)) || (preg_match("~opera 7.22~i", $ua)))	$this->browserVersion = "7.22";
				elseif ((preg_match("~opera/7.21~i", $ua)) || (preg_match("~opera 7.21~i", $ua)))		$this->browserVersion = "7.21";
				elseif ((preg_match("~opera/7.20~i", $ua)) || (preg_match("~opera 7.20~i", $ua)))	$this->browserVersion = "7.20";
				elseif ((preg_match("~opera/7.11~i", $ua)) || (preg_match("~opera 7.11~i", $ua)))	$this->browserVersion = "7.11";
				elseif ((preg_match("~opera/7.10~i", $ua)) || (preg_match("~opera 7.10~i", $ua)))	$this->browserVersion = "7.10";
				elseif ((preg_match("~opera/7.03~i", $ua)) || (preg_match("~opera 7.03~i", $ua)))	$this->browserVersion = "7.03";
				elseif ((preg_match("~opera/7.02~i", $ua)) || (preg_match("~opera 7.02~i", $ua)))	$this->browserVersion = "7.02";
				elseif ((preg_match("~opera/7.01~i", $ua)) || (preg_match("~opera 7.01~i", $ua)))	$this->browserVersion = "7.01";
				elseif ((preg_match("~opera/7.0~i", $ua)) || (preg_match("~opera 7.0~i", $ua)))	$this->browserVersion = "7.0";
				elseif ((preg_match("~opera/6.12~i", $ua)) || (preg_match("~opera 6.12~i", $ua)))	$this->browserVersion = "6.12";
				elseif ((preg_match("~opera/6.11~i", $ua)) || (preg_match("~opera 6.11~i", $ua)))	$this->browserVersion = "6.11";
				elseif ((preg_match("~opera/6.1~i", $ua)) || (preg_match("~opera 6.1~i", $ua)))	$this->browserVersion = "6.1";
				elseif ((preg_match("~opera/6.	0~i", $ua)) || (preg_match("~opera 6.0~i", $ua)))	$this->browserVersion = "6.0";
				elseif ((preg_match("~opera/5.12~i", $ua)) || (preg_match("~opera 5.12~i", $ua)))	$this->browserVersion = "5.12";
				elseif ((preg_match("~opera/5.0~i", $ua)) || (preg_match("~opera 5.0~i", $ua)))	$this->browserVersion = "5.0";
				elseif ((preg_match("~opera/4~i", $ua)) || (preg_match("~opera 4~i", $ua)))		$this->browserVersion = "4";
			}
			elseif (preg_match("~oracle~i", $ua)){
				$this->browser = "Oracle PowerBrowser";
				$this->browserType = "browser";
				if (preg_match("~(tm)/1.0a~i", $ua))		$this->browserVersion = "1.0a";
				elseif (preg_match("~oracle 1.5~i", $ua))	$this->browserVersion = "1.5";
			}
			elseif (preg_match("~phoenix~i", $ua)){
				$this->browser = "Phoenix";
				$this->browserType = "browser";
				if (preg_match("~phoenix/0.4~i", $ua))		$this->browserVersion = "0.4";
				elseif (preg_match("~phoenix/0.5~i", $ua))	$this->browserVersion = "0.5";
			}
			elseif (preg_match("~planetweb~i", $ua)){
				$this->browser = "PlanetWeb";
				$this->browserType = "browser";
				if (preg_match("~planetweb/2.606~i", $ua))		$this->browserVersion = "2.6";
				elseif (preg_match("~planetweb/1.125~i", $ua))	$this->browserVersion = "3";
			}
			elseif (preg_match("~powertv~i", $ua)){
				$this->browser = "PowerTV";
				$this->browserType = "browser";
				if (preg_match("~powertv/1.5~i", $ua))	$this->browserVersion = "1.5";
			}
			elseif (preg_match("~prodigy~i", $ua)){
				$this->browser = "Prodigy";
				if (preg_match("~wb/3.2e~i", $ua))	$this->browserVersion = "3.2e";
				elseif (preg_match("~rv: 1.~i", $ua))	$this->browserVersion = "1.0";
			}
			elseif ((preg_match("~voyager~i", $ua)) || ((preg_match("~qnx~i", $ua))) && (preg_match("~rv: 1.~i", $ua))){
				$this->browser = "Voyager";
				if (preg_match("~2.03b~i", $ua))	$this->browserVersion = "2.03b";
				elseif (preg_match("~wb/win32/3.4g~i", $ua))	$this->browserVersion = "3.4g";
			}
			elseif (preg_match("~quicktime~i", $ua)){
				$this->browser = "QuickTime";
				if (preg_match("~qtver=5~i", $ua))		$this->browserVersion = "5.0";
				elseif (preg_match("~qtver=6.0~i", $ua))	$this->browserVersion = "6.0";
				elseif (preg_match("~qtver=6.1~i", $ua))	$this->browserVersion = "6.1";
				elseif (preg_match("~qtver=6.2~i", $ua))	$this->browserVersion = "6.2";
				elseif (preg_match("~qtver=6.3~i", $ua))	$this->browserVersion = "6.3";
				elseif (preg_match("~qtver=6.4~i", $ua))	$this->browserVersion = "6.4";
				elseif (preg_match("~qtver=6.5~i", $ua))	$this->browserVersion = "6.5";
			}
			elseif (preg_match("~safari~i", $ua)){
				$this->browser = "Safari";
				if (preg_match("~safari/48~i", $ua))		$this->browserVersion = "0.48";
				elseif (preg_match("~safari/49~i", $ua))	$this->browserVersion = "0.49";
				elseif (preg_match("~safari/51~i", $ua))	$this->browserVersion = "0.51";
				elseif (preg_match("~safari/60~i", $ua))	$this->browserVersion = "0.60";
				elseif (preg_match("~safari/61~i", $ua))	$this->browserVersion = "0.61";
				elseif (preg_match("~safari/62~i", $ua))	$this->browserVersion = "0.62";
				elseif (preg_match("~safari/63~i", $ua))	$this->browserVersion = "0.63";
				elseif (preg_match("~safari/64~i", $ua))	$this->browserVersion = "0.64";
				elseif (preg_match("~safari/65~i", $ua))	$this->browserVersion = "0.65";
				elseif (preg_match("~safari/66~i", $ua))	$this->browserVersion = "0.66";
				elseif (preg_match("~safari/67~i", $ua))	$this->browserVersion = "0.67";
				elseif (preg_match("~safari/68~i", $ua))	$this->browserVersion = "0.68";
				elseif (preg_match("~safari/69~i", $ua))	$this->browserVersion = "0.69";
				elseif (preg_match("~safari/70~i", $ua))	$this->browserVersion = "0.70";
				elseif (preg_match("~safari/71~i", $ua))	$this->browserVersion = "0.71";
				elseif (preg_match("~safari/72~i", $ua))	$this->browserVersion = "0.72";
				elseif (preg_match("~safari/73~i", $ua))	$this->browserVersion = "0.73";
				elseif (preg_match("~safari/74~i", $ua))	$this->browserVersion = "0.74";
				elseif (preg_match("~safari/80~i", $ua))	$this->browserVersion = "0.80";
				elseif (preg_match("~safari/83~i", $ua))	$this->browserVersion = "0.83";
				elseif (preg_match("~safari/84~i", $ua))	$this->browserVersion = "0.84";
				elseif (preg_match("~safari/85~i", $ua))	$this->browserVersion = "0.85";
				elseif (preg_match("~safari/90~i", $ua))	$this->browserVersion = "0.90";
				elseif (preg_match("~safari/92~i", $ua))	$this->browserVersion = "0.92";
				elseif (preg_match("~safari/93~i", $ua))	$this->browserVersion = "0.93";
				elseif (preg_match("~safari/94~i", $ua))	$this->browserVersion = "0.94";
				elseif (preg_match("~safari/95~i", $ua))	$this->browserVersion = "0.95";
				elseif (preg_match("~safari/96~i", $ua))	$this->browserVersion = "0.96";
				elseif (preg_match("~safari/97~i", $ua))	$this->browserVersion = "0.97";
				elseif (preg_match("~safari/125~i", $ua))	$this->browserVersion = "1.25";
			}
			elseif (preg_match("~sextatnt~i", $ua)){
				$this->browser = "Tango";
				if (preg_match("~sextant v3.0~i", $ua))	$this->browserVersion = "3.0";
			}
			elseif (preg_match("~sharpreader~i", $ua)){
				$this->browser = "SharpReader";
				if (preg_match("~sharpreader/0.9.5~i", $ua))	$this->browserVersion = "0.9.5";
			}
			elseif (preg_match("~elinks~i", $ua)){
				$this->browser = "ELinks";
				if (preg_match("~0.3~i", $ua))	$this->browserVersion = "0.3";
				elseif (preg_match("~0.4~i", $ua))	$this->browserVersion = "0.4";
				elseif (preg_match("~0.9~i", $ua))	$this->browserVersion = "0.9";
			}
			elseif (preg_match("~links~i", $ua)){
				$this->browser = "Links";
				if (preg_match("~0.9~i", $ua))	$this->browserVersion = "0.9";
				elseif (preg_match("~2.0~i", $ua))	$this->browserVersion = "2.0";
				elseif (preg_match("~2.1~i", $ua))	$this->browserVersion = "2.1";
			}
			elseif (preg_match("~lynx~i", $ua)){
				$this->browser = "Lynx";
				if (preg_match("~lynx/2.3~i", $ua))	$this->browserVersion = "2.3";
				elseif (preg_match("~lynx/2.4~i", $ua))	$this->browserVersion = "2.4";
				elseif ((preg_match("~lynx/2.5~i", $ua)) || (preg_match("~lynx 2.5~i", $ua)))	$this->browserVersion = "2.5";
				elseif (preg_match("~lynx/2.6~i", $ua))	$this->browserVersion = "2.6";
				elseif (preg_match("~lynx/2.7~i", $ua))	$this->browserVersion = "2.7";
				elseif (preg_match("~lynx/2.8~i", $ua))	$this->browserVersion = "2.8";
			}
			elseif (preg_match("~webexplorer~i", $ua)){
				$this->browser = "WebExplorer";
				if (preg_match("~dll/v1.1~i", $ua))	$this->browserVersion = "1.1";
			}
			elseif (preg_match("~wget~i", $ua)){
				$this->browser = "WGet";
				if (preg_match("~Wget/1.9~i", $ua))	$this->browserVersion = "1.9";
				if (preg_match("~Wget/1.8~i", $ua))	$this->browserVersion = "1.8";
			}
			elseif (preg_match("~webtv~i", $ua)){
				$this->browser = "WebTV";
				if (preg_match("~webtv/1.0~i", $ua))		$this->browserVersion = "1.0";
				elseif (preg_match("~webtv/1.1~i", $ua))	$this->browserVersion = "1.1";
				elseif (preg_match("~webtv/1.2~i", $ua))	$this->browserVersion = "1.2";
				elseif (preg_match("~webtv/2.2~i", $ua))	$this->browserVersion = "2.2";
				elseif (preg_match("~webtv/2.5~i", $ua))	$this->browserVersion = "2.5";
				elseif (preg_match("~webtv/2.6~i", $ua))	$this->browserVersion = "2.6";
				elseif (preg_match("~webtv/2.7~i", $ua))	$this->browserVersion = "2.7";
			}
			elseif (preg_match("~yandex~i", $ua)){
				$this->browser = "Yandex";
				if (preg_match("~/1.01~i", $ua))	$this->browserVersion = "1.01";
				elseif (preg_match("~/1.03~i", $ua))	$this->browserVersion = "1.03";
			}
			elseif ((preg_match("~mspie~i", $ua)) || ((preg_match("~msie~i", $ua))) && (preg_match("~windows ce~i", $ua))){
				$this->browser = "Pocket Internet Explorer";
				if (preg_match("~mspie 1.1~i", $ua))		$this->browserVersion = "1.1";
				elseif (preg_match("~mspie 2.0~i", $ua))	$this->browserVersion = "2.0";
				elseif (preg_match("~msie 3.02~i", $ua))	$this->browserVersion = "3.02";
			}
			elseif (preg_match("~UP.Browser/~i", $ua)){
				$this->browser = "UP Browser";
				if (preg_match("~Browser/7.0~i", $ua))		$this->browserVersion = "7.0";
			}
			elseif (preg_match("~msie~i", $ua)){
				$this->browser = "Internet Explorer";
				if (preg_match("~msie 7.0~i", $ua))			$this->browserVersion = "7.0";
				elseif (preg_match("~msie 6.0~i", $ua))		$this->browserVersion = "6.0";
				elseif (preg_match("~msie 5.5~i", $ua))		$this->browserVersion = "5.5";
				elseif (preg_match("~msie 5.01~i", $ua))	$this->browserVersion = "5.01";
				elseif (preg_match("~msie 5.23~i", $ua))	$this->browserVersion = "5.23";
				elseif (preg_match("~msie 5.22~i", $ua))	$this->browserVersion = "5.22";
				elseif (preg_match("~msie 5.2.2~i", $ua))	$this->browserVersion = "5.2.2";
				elseif (preg_match("~msie 5.1b1~i", $ua))	$this->browserVersion = "5.1b1";
				elseif (preg_match("~msie 5.17~i", $ua))	$this->browserVersion = "5.17";
				elseif (preg_match("~msie 5.16~i", $ua))	$this->browserVersion = "5.16";
				elseif (preg_match("~msie 5.12~i", $ua))	$this->browserVersion = "5.12";
				elseif (preg_match("~msie 5.0b1~i", $ua))	$this->browserVersion = "5.0b1";
				elseif (preg_match("~msie 5.0~i", $ua))		$this->browserVersion = "5.0";
				elseif (preg_match("~msie 5.21~i", $ua))	$this->browserVersion = "5.21";
				elseif (preg_match("~msie 5.2~i", $ua))		$this->browserVersion = "5.2";
				elseif (preg_match("~msie 5.15~i", $ua))	$this->browserVersion = "5.15";
				elseif (preg_match("~msie 5.14~i", $ua))	$this->browserVersion = "5.14";
				elseif (preg_match("~msie 5.13~i", $ua))	$this->browserVersion = "5.13";
				elseif (preg_match("~msie 4.5~i", $ua))		$this->browserVersion = "4.5";
				elseif (preg_match("~msie 4.01~i", $ua))	$this->browserVersion = "4.01";
				elseif (preg_match("~msie 4.0b2~i", $ua))	$this->browserVersion = "4.0b2";
				elseif (preg_match("~msie 4.0b1~i", $ua))	$this->browserVersion = "4.0b1";
				elseif (preg_match("~msie 4~i", $ua))		$this->browserVersion = "4.0";
				elseif (preg_match("~msie 3~i", $ua))		$this->browserVersion = "3.0";
				elseif (preg_match("~msie 2~i", $ua))		$this->browserVersion = "2.0";
				elseif (preg_match("~msie 1.5~i", $ua))		$this->browserVersion = "1.5";
			}
			elseif (preg_match("~iexplore~i", $ua)){
				$this->browser = "Internet Explorer";
			}
			elseif (preg_match("~mozilla~i", $ua)){
				$this->browser = "Netscape";
				if (preg_match("~mozilla/5.0~i", $ua))	$this->browserVersion = "5.0";
				else if (preg_match("~mozilla/4.9~i", $ua))	$this->browserVersion = "4.9";
				else if (preg_match("~mozilla/4.8~i", $ua))	$this->browserVersion = "4.8";
				elseif (preg_match("~mozilla/4.7~i", $ua))	$this->browserVersion = "4.7";
				elseif (preg_match("~mozilla/4.6~i", $ua))	$this->browserVersion = "4.6";
				elseif (preg_match("~mozilla/4.5~i", $ua))	$this->browserVersion = "4.5";
				elseif (preg_match("~mozilla/4.0~i", $ua))	$this->browserVersion = "4.0";
				elseif (preg_match("~mozilla/3.0~i", $ua))	$this->browserVersion = "3.0";
				elseif (preg_match("~mozilla/2.0~i", $ua))	$this->browserVersion = "2.0";
			}
		}
	}
}
