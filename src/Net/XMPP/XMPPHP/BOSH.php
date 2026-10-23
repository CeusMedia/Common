<?php
/**
 * XMPPHP: The PHP XMPP Library
 * Copyright (C) 2008  Nathanael C. Fritz
 * This file is part of SleekXMPP.
 *
 * XMPPHP is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * XMPPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with XMPPHP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP_XMPPHP
 *	@author			Nathanael C. Fritz <JID: fritzy@netflint.net>
 *	@author			Stephan Wentz <JID: stephan@jabber.wentz.it>
 *	@author			Michael Garvin <JID: gar@netflint.net>
 *	@copyright		2008 Nathanael C. Fritz
 */

namespace CeusMedia\Common\Net\XMPP\XMPPHP;

use SimpleXMLElement;

/**
 * XMPPHP Main Class
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP_XMPPHP
 *	@author			Nathanael C. Fritz <JID: fritzy@netflint.net>
 *	@author			Stephan Wentz <JID: stephan@jabber.wentz.it>
 *	@author			Michael Garvin <JID: gar@netflint.net>
 *	@copyright		2008 Nathanael C. Fritz
 */
class BOSH
{
	/** @var int */
	protected $rid;
	/** @var string|null */
	protected $sid;
	/** @var string */
	protected $http_server;
	/** @var array<string> */
	protected $http_buffer = [];
	/** @var bool */
	protected $session = false;
	/** @var bool */
	protected $use_encryption = false;
	/** @var string */
	protected $host;
	/** @var int */
	protected $port;
	/** @var \XMLParser */
	protected $parser;
	/** @var \CeusMedia\Common\Net\XMPP\XMPPHP\Log */
	protected $log;
	/** @var int */
	protected $xml_depth = 0;
	/** @var array<int, \CeusMedia\Common\Net\XMPP\XMPPHP\XMLObj> */
	protected $xmlobj = [];
	/** @var bool */
	protected $authed = false;
	/** @var string */
	protected $jid;
	/** @var string */
	protected $fulljid;
	/** @var bool */
	protected $been_reset = false;
	/** @var string */
	protected $stream_start = '<stream>';

	public function connect(string $server, string $wait='1', bool $session=false): void
	{
		$this->http_server = $server;
		$this->use_encryption = false;
		$this->session = $session;

		$this->rid = 3001;
		$this->sid = null;
		if($session)
		{
			$this->loadSession();
		}
		if(!$this->sid) {
			$body = $this->__buildBody();
			$body->addAttribute('hold','1');
			$body->addAttribute('to', $this->host);
			$body->addAttribute('route', "xmpp:{$this->host}:{$this->port}");
			$body->addAttribute('secure','true');
			$body->addAttribute('xmpp:version','1.6', 'urn:xmpp:xbosh');
			$body->addAttribute('wait', strval($wait));
			$body->addAttribute('ack','1');
			$body->addAttribute('xmlns:xmpp','urn:xmpp:xbosh');
			$buff = "<stream:stream xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>";
			xml_parse($this->parser, $buff, false);
			$response = $this->__sendBody($body);
			$rxml = new SimpleXMLElement($response);
			$this->sid = $rxml['sid'];

		} else {
			$buff = "<stream:stream xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>";
			xml_parse($this->parser, $buff, false);
		}
	}

	public function __sendBody(?\SimpleXMLElement $body=null, bool $recv=true): string
	{
		if(!$body) {
			$body = $this->__buildBody();
		}
		$ch = curl_init($this->http_server);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body->asXML());
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$header = array('Accept-Encoding: gzip, deflate','Content-Type: text/xml; charset=utf-8');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		$output = '';
		if($recv) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			$this->http_buffer[] = $output;
		}
		curl_close($ch);
		return $output;
	}

	public function __buildBody(?\SimpleXMLElement $sub=null): \SimpleXMLElement
	{
		$xml = new SimpleXMLElement("<body xmlns='http://jabber.org/protocol/httpbind' xmlns:xmpp='urn:xmpp:xbosh' />");
		$xml->addAttribute('content', 'text/xml; charset=utf-8');
		$xml->addAttribute('rid', strval($this->rid));
		$this->rid += 1;
		if($this->sid) $xml->addAttribute('sid', $this->sid);
		#if($this->sid) $xml->addAttribute('xmlns', 'http://jabber.org/protocol/httpbind');
		$xml->addAttribute('xml:lang', 'en');
		// ok, so simplexml is lame
		if($sub) {
			$p = dom_import_simplexml($xml);
			$c = dom_import_simplexml($sub);
			$cn = $p->ownerDocument->importNode($c, true);
			$p->appendChild($cn);
			$xml = simplexml_import_dom($p);
		}
		return $xml;
	}

	public function __process(): void
	{
		if($this->http_buffer) {
			$this->__parseBuffer();
		} else {
			$this->__sendBody();
			$this->__parseBuffer();
		}
	}

	public function __parseBuffer(): void
	{
		while ($this->http_buffer) {
			$idx = key($this->http_buffer);
			$buffer = $this->http_buffer[$idx];
			unset($this->http_buffer[$idx]);
			if($buffer) {
				$xml = new SimpleXMLElement($buffer);
				$children = $xml->xpath('child::node()');
				foreach ($children as $child) {
					$buff = $child->asXML();
					$this->log->log("RECV: $buff",  Log::LEVEL_VERBOSE);
					xml_parse($this->parser, $buff, false);
				}
			}
		}
	}

	public function send(string $msg): void
	{
		$this->log->log("SEND: $msg",  Log::LEVEL_VERBOSE);
		$msg = new SimpleXMLElement($msg);
		#$msg->addAttribute('xmlns', 'jabber:client');
		$this->__sendBody($this->__buildBody($msg), true);
		#$this->__parseBuffer();
	}

	public function reset(): void
	{
		$this->xml_depth = 0;
		unset($this->xmlobj);
		$this->xmlobj = [];
		$this->setupParser();
		#$this->send($this->stream_start);
		$body = $this->__buildBody();
		$body->addAttribute('to', $this->host);
		$body->addAttribute('xmpp:restart', 'true', 'urn:xmpp:xbosh');
		$buff = "<stream:stream xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>";
		$response = $this->__sendBody($body);
		$this->been_reset = true;
		xml_parse($this->parser, $buff, false);
	}

	public function loadSession(): void
	{
		if(isset($_SESSION['XMPPHP_BOSH_RID'])) $this->rid = $_SESSION['XMPPHP_BOSH_RID'];
		if(isset($_SESSION['XMPPHP_BOSH_SID'])) $this->sid = $_SESSION['XMPPHP_BOSH_SID'];
		if(isset($_SESSION['XMPPHP_BOSH_authed'])) $this->authed = $_SESSION['XMPPHP_BOSH_authed'];
		if(isset($_SESSION['XMPPHP_BOSH_jid'])) $this->jid = $_SESSION['XMPPHP_BOSH_jid'];
		if(isset($_SESSION['XMPPHP_BOSH_fulljid'])) $this->fulljid = $_SESSION['XMPPHP_BOSH_fulljid'];
	}

	public function saveSession(): void
	{
		$_SESSION['XMPPHP_BOSH_RID'] = (string) $this->rid;
		$_SESSION['XMPPHP_BOSH_SID'] = (string) $this->sid;
		$_SESSION['XMPPHP_BOSH_authed'] = (boolean) $this->authed;
		$_SESSION['XMPPHP_BOSH_jid'] = (string) $this->jid;
		$_SESSION['XMPPHP_BOSH_fulljid'] = (string) $this->fulljid;
	}

	public function setupParser(): void
	{
		$this->parser = xml_parser_create('UTF-8');
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'startXML', 'endXML');
		xml_set_character_data_handler($this->parser, 'charXML');
	}
}
