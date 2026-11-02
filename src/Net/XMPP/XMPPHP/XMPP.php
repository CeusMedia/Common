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
class XMPP extends XMLStream
{
	/** @var string */
	public string $server;

	/** @var string */
	public string $user;

	/** @var string */
	protected string $password;

	/** @var string */
	protected string $resource;

	/** @var string */
	protected string $fulljid;

	/** @var string */
	protected string $jid;

	/** @var string */
	protected string $basejid;

	/** @var bool */
	protected bool $authed = false;

	/** @var bool */
	protected bool $session_started = false;

	/** @var bool */
	protected bool $auto_subscribe = false;

	/** @var bool */
	protected bool $use_encryption = true;

	/** @var bool */
	public bool $track_presence = true;

	/** @var Roster */
	public Roster $roster;

	/**
	 * Constructor
	 *
	 * @param string  $host
	 * @param integer $port
	 * @param string  $user
	 * @param string  $password
	 * @param string  $resource
	 * @param string|NULL  $server
	 * @param boolean $printlog
	 * @param integer|NULL $loglevel
	 */
	public function __construct(string $host, int $port, string $user, string $password, string $resource, ?string $server = null, bool $printlog = false, ?int $loglevel = null)
	{
		parent::__construct($host, $port, $printlog, $loglevel);

		$this->user	 = $user;
		$this->password = $password;
		$this->resource = $resource;
		$server ??= $host;
		$this->basejid = $this->user . '@' . $this->host;

		$this->roster = new Roster();
		$this->track_presence = true;

		$this->stream_start = '<stream:stream to="' . $server . '" xmlns:stream="http://etherx.jabber.org/streams" xmlns="jabber:client" version="1.0">';
		$this->stream_end   = '</stream:stream>';
		$this->default_ns   = 'jabber:client';

		$this->addXPathHandler('{http://etherx.jabber.org/streams}features', 'features_handler');
		$this->addXPathHandler('{urn:ietf:params:xml:ns:xmpp-sasl}success', 'sasl_success_handler');
		$this->addXPathHandler('{urn:ietf:params:xml:ns:xmpp-sasl}failure', 'sasl_failure_handler');
		$this->addXPathHandler('{urn:ietf:params:xml:ns:xmpp-tls}proceed', 'tls_proceed_handler');
		$this->addXPathHandler('{jabber:client}message', 'message_handler');
		$this->addXPathHandler('{jabber:client}presence', 'presence_handler');
		$this->addXPathHandler('iq/{jabber:iq:roster}query', 'roster_iq_handler');
	}

	/**
	 * Turn encryption on/ff
	 *
	 * @param boolean $useEncryption
	 */
	public function useEncryption(bool $useEncryption = true): void
	{
		$this->use_encryption = $useEncryption;
	}

	/**
	 * Turn on auto-authorization of subscription requests.
	 *
	 * @param boolean $autoSubscribe
	 */
	public function autoSubscribe(bool $autoSubscribe = true): void
	{
		$this->auto_subscribe = $autoSubscribe;
	}

	/**
	 * @param string $to
	 * @param string $body
	 * @param string $type
	 * @param string|null $subject
	 * @param string|null $payload
	 */
	public function message(string $to, string $body, string $type = 'chat', ?string $subject = null, ?string $payload = null): void
	{

		$to	  = htmlspecialchars($to);
		$body	= htmlspecialchars($body);
		$subject = htmlspecialchars($subject);

		$out = "<message from=\"{$this->fulljid}\" to=\"$to\" type='$type'>";
		if($subject) $out .= "<subject>$subject</subject>";
		$out .= "<body>$body</body>";
		if($payload) $out .= $payload;
		$out .= "</message>";

		$this->send($out);
	}

	/**
	 * Set Presence
	 *
	 * @param string $status
	 * @param string $show
	 * @param string $to
	 */
	public function presence(?string $status = null, string $show = 'available', ?string $to = null, string $type='available', int $priority=0): void
	{
		if($type == 'available') $type = '';
		$to	 = htmlspecialchars($to);
		$status = htmlspecialchars($status);
		if($show == 'unavailable') $type = 'unavailable';

		$out = "<presence";
		if($to) $out .= " to=\"$to\"";
		if($type) $out .= " type='$type'";
		if($show == 'available' and !$status) {
			$out .= "/>";
		} else {
			$out .= ">";
			if($show != 'available') $out .= "<show>$show</show>";
			if($status) $out .= "<status>$status</status>";
			if($priority) $out .= "<priority>$priority</priority>";
			$out .= "</presence>";
		}

		$this->send($out);
	}
	/**
	 * Send Auth request
	 *
	 * @param string $jid
	 */
	public function subscribe(string $jid): void
	{
		$this->send("<presence type='subscribe' to='{$jid}' from='{$this->fulljid}' />");
		#$this->send("<presence type='subscribed' to='{$jid}' from='{$this->fulljid}' />");
	}

	/**
	 * @param XMLObj $xml
	 */
	public function message_handler(XMLObj $xml): void
	{
		if(isset($xml->attrs['type'])) {
			$payload['type'] = $xml->attrs['type'];
		} else {
			$payload['type'] = 'chat';
		}
		$payload['from'] = $xml->attrs['from'];
		$payload['body'] = $xml->sub('body') ? $xml->sub('body')->data : NULL;
		$payload['xml'] = $xml;
		$this->log->log("Message: {$payload['body']}", Log::LEVEL_DEBUG);
		$this->event('message', $payload);
	}

	/**
	 * @param XMLObj $xml
	 */
	public function presence_handler(XMLObj $xml): void
	{
		$payload['type'] = (isset($xml->attrs['type'])) ? $xml->attrs['type'] : 'available';
		$payload['show'] = (isset($xml->sub('show')->data)) ? $xml->sub('show')->data : $payload['type'];
		$payload['from'] = $xml->attrs['from'];
		$payload['status'] = (isset($xml->sub('status')->data)) ? $xml->sub('status')->data : '';
		$payload['priority'] = (isset($xml->sub('priority')->data)) ? intval($xml->sub('priority')->data) : 0;
		$payload['xml'] = $xml;
		if($this->track_presence) {
			$this->roster->setPresence($payload['from'], $payload['priority'], $payload['show'], $payload['status']);
		}
		$this->log->log("Presence: {$payload['from']} [{$payload['show']}] {$payload['status']}", Log::LEVEL_DEBUG);
		if(array_key_exists('type', $xml->attrs) and $xml->attrs['type'] == 'subscribe') {
			if($this->auto_subscribe) {
				$this->send("<presence type='subscribed' to='{$xml->attrs['from']}' from='{$this->fulljid}' />");
				$this->send("<presence type='subscribe' to='{$xml->attrs['from']}' from='{$this->fulljid}' />");
			}
			$this->event('subscription_requested', $payload);
		} elseif(array_key_exists('type', $xml->attrs) and $xml->attrs['type'] == 'subscribed') {
			$this->event('subscription_accepted', $payload);
		} else {
			$this->event('presence', $payload);
		}
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function features_handler(XMLObj $xml): void {
		if($xml->hasSub('starttls') and $this->use_encryption) {
			$this->send("<starttls xmlns='urn:ietf:params:xml:ns:xmpp-tls'><required /></starttls>");
		} elseif($xml->hasSub('bind') and $this->authed) {
			$id = $this->getId();
			$this->addIdHandler($id, 'resource_bind_handler');
			$this->send("<iq xmlns=\"jabber:client\" type=\"set\" id=\"$id\"><bind xmlns=\"urn:ietf:params:xml:ns:xmpp-bind\"><resource>{$this->resource}</resource></bind></iq>");
		} else {
			$this->log->log("Attempting Auth...");
			if ($this->password) {
				$this->send("<auth xmlns='urn:ietf:params:xml:ns:xmpp-sasl' mechanism='PLAIN'>" . base64_encode("\x00" . $this->user . "\x00" . $this->password) . "</auth>");
			} else {
				$this->send("<auth xmlns='urn:ietf:params:xml:ns:xmpp-sasl' mechanism='ANONYMOUS'/>");
			}
		}
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function sasl_success_handler(XMLObj $xml): void
	{
		$this->log->log("Auth success!");
		$this->authed = true;
		$this->reset();
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function sasl_failure_handler(XMLObj $xml): void
	{
		$this->log->log("Auth failed!", Log::LEVEL_ERROR);
		$this->disconnect();

		throw new Exception('Auth failed!');
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function resource_bind_handler(XMLObj $xml): void
	{
		if($xml->attrs['type'] == 'result') {
			$this->log->log("Bound to " . $xml->sub('bind')->sub('jid')->data);
			$this->fulljid = $xml->sub('bind')->sub('jid')->data;
			$jidarray = explode('/',$this->fulljid);
			$this->jid = $jidarray[0];
		}
		$id = $this->getId();
		$this->addIdHandler($id, 'session_start_handler');
		$this->send("<iq xmlns='jabber:client' type='set' id='$id'><session xmlns='urn:ietf:params:xml:ns:xmpp-session' /></iq>");
	}

	/**
	* Retrieves the roster
	*
	*/
	public function getRoster(): void
	{
		$id = $this->getID();
		$this->addIdHandler($id, 'roster_iq_handler');
		$this->send("<iq xmlns='jabber:client' type='get' id='$id'><query xmlns='jabber:iq:roster' /></iq>");
	}

	/**
	* @param XMLObj $xml
	*/
	protected function roster_iq_handler(XMLObj $xml): void
	{
		$status = "result";
		$contacts = [];
		$xmlroster = $xml->sub('query');
		foreach($xmlroster->subs as $item) {
			$groups = [];
			if ($item->name == 'item') {
				$jid = $item->attrs['jid']; //REQUIRED
				$name = @$item->attrs['name']; //MAY
				$subscription = $item->attrs['subscription'];
				foreach($item->subs as $subitem) {
					if ($subitem->name == 'group') {
						$groups[] = $subitem->data;
					}
				}
				$contacts[] = array($jid, $subscription, $name, $groups); //Store for action if no errors happen
			} else {
				$status = "error";
			}
		}
		if ($status == "result") { //No errors, add contacts
			foreach($contacts as $contact) {
				$this->roster->addContact($contact[0], $contact[1], $contact[2], $contact[3]);
			}
		}
		if ($xml->attrs['type'] == 'set') {
			$this->send("<iq type=\"reply\" id=\"{$xml->attrs['id']}\" to=\"{$xml->attrs['from']}\" />");
		}
		$this->event( 'roster_received' );
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function session_start_handler(XMLObj $xml): void
	{
		$this->log->log("Session started");
		$this->session_started = true;
		$this->event('session_start');
	}

	/**
	 * @param XMLObj $xml
	 */
	protected function tls_proceed_handler(XMLObj $xml): void
	{
		$this->log->log("Starting TLS encryption");
		stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
		$this->reset();
	}

	/**
	* Retrieves the vcard
	*
	*/
	public function getVCard(?string $jid = Null): void
	{
		$id = $this->getID();
		$this->addIdHandler($id, 'vcard_get_handler');
		if($jid) {
			$this->send("<iq type='get' id='$id' to='$jid'><vCard xmlns='vcard-temp' /></iq>");
		} else {
			$this->send("<iq type='get' id='$id'><vCard xmlns='vcard-temp' /></iq>");
		}
	}

	/**
	* @param XMLObj $xml
	*/
	protected function vcard_get_handler(XMLObj $xml): void
	{
		$vcard_array = [];
		$vcard = $xml->sub('vcard');
		// go through all of the sub elements and add them to the vcard array
		foreach ($vcard->subs as $sub) {
			if ($sub->subs) {
				$vcard_array[$sub->name] = [];
				foreach ($sub->subs as $sub_child) {
					$vcard_array[$sub->name][$sub_child->name] = $sub_child->data;
				}
			} else {
				$vcard_array[$sub->name] = $sub->data;
			}
		}
		$vcard_array['from'] = $xml->attrs['from'];
		$this->event('vcard', $vcard_array);
	}
}
