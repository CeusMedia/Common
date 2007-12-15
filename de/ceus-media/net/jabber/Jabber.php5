<?php
import( 'de.ceus-media.net.jabber.Standardconnector' );
import( 'de.ceus-media.net.jabber.MakeXML' );
/***************************************************************************
	Class.Jabber.PHP v0.4.2
	(c) 2004 Nathan "Fritzy" Fritz
	http://cjphp.netflint.net *** fritzy@netflint.net

	This is a bugfix version, specifically for those who can't get 
	0.4 to work on Jabberd2 servers. 

	last modified: 24.03.2004 13:01:53 

 ***************************************************************************/
/*
	Jabber::Connect()
	Jabber::Disconnect()
	Jabber::SendAuth()

	Jabber::Listen()
	Jabber::SendPacket($xml {string})

	Jabber::Subscribe($jid {string})
	Jabber::Unsubscribe($jid {string})

	Jabber::CallHandler($message {array})
	Jabber::CruiseControl([$seconds {number}])

	Jabber::SubscriptionApproveRequest($to {string})
	Jabber::SubscriptionDenyRequest($to {string})

	Jabber::GetFirstFromQueue()
	Jabber::GetFromQueueById($packet_type {string}, $id {string})

	Jabber::SendMessage($to {string}, $id {number}, $type {string}, $content {array}[, $payload {array}])
 	Jabber::SendIq($to {string}, $type {string}, $id {string}, $xmlns {string}[, $payload {string}])
	Jabber::SendPresence($type {string}[, $to {string}[, $status {string}[, $show {string}[, $priority {number}]]]])

	Jabber::SendError($to {string}, $id {string}, $error_number {number}[, $error_message {string}])
	Jabber::GetvCard($jid {string}[, $id {string}])	-- EXPERIMENTAL --

	Jabber::getInfoFromMessage($packet {array}, $type {string})
	Jabber::getInfoFromIq($packet {array}, $type {string})
	Jabber::getInfoFromPresence($packet {array}, $type {string})

	Jabber::addToLog($string {string})
	Jabber::printLog()
*/
class Jabber
{
	var $server;
	var $port;
	var $username;
	var $password;
	var $resource;
	var $jid;

	var $connection;
	var $delayDisconnect;

	var $streamId;
	var $roster;

	var $enable_logging;
	var $logArray;
	var $logFileName;
	var $logFileHandler;

	var $iqSleepTimer;
	var $lastPingTime;

	var $packetQueue;
	var $subscriptionQueue;

	var $iqVersionName;
	var $iqVersionOs;
	var $iqVersionVersion;

	var $error_codes;

	var $connected;
	var $keepAliveId;
	var $returnedKeepAlive;
	var $txnid;

	var $connector;

	function Jabber()
	{
		$this->server				= "localhost";
		$this->port					= "5222";
		$this->username				= "larry";
		$this->password				= "curly";
		$this->resource				= NULL;

		$this->enable_logging		= FALSE;
		$this->logArray				= array();
		$this->logFileName			= '';
		$this->logFileHandler		= FALSE;
		$this->packetQueue			= array();
		$this->subscriptionQueue	= array();
		$this->iqSleepTimer			= 1;
		$this->delayDisconnect		= 1;
		$this->returnedKeepAlive	= TRUE;
		$this->txnid				= 0;
		$this->iqVersionName		= "Class.Jabber.PHP -- http://cjphp.netflint.net -- by Nathan 'Fritzy' Fritz, fritz@netflint.net";
		$this->iqVersionVersion		= "0.4";
		$this->iqVersionOs			= $_SERVER['SERVER_SOFTWARE'];
		$this->connection_class		= "Standardconnector";

		$this->error_codes			= array(
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Not Allowed",
			406 => "Not Acceptable",
			407 => "Registration Required",
			408 => "Request Timeout",
			409 => "Conflict",
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Remove Server Error",
			503 => "Service Unavailable",
			504 => "Remove Server Timeout",
			510 => "Disconnected"
		);
	}

	function addToLog($string)
	{
		if ($this->enable_logging)
		{
			if ($this->logFileHandler)
			{
				#fwrite($this->logFileHandler, $string . "\n\n");
				print "$string \n\n";
			}
			else
			{
				$this->logArray[] = htmlspecialchars($string);
			}
		}
	}

	function Connect()
	{
		$this->createLogFile();
		$this->connector = new $this->connection_class;
		if ($this->connector->OpenSocket($this->server, $this->port))
		{
			$this->SendPacket("<?xml version='1.0' encoding='UTF-8' ?" . ">\n");
			$this->SendPacket("<stream:stream to='{$this->server}' xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>\n");
			sleep(1);
			if ($this->check_connected())
			{
				$this->connected = TRUE;	// Nathan Fritz
				return TRUE;
			}
			else
			{
				$this->addToLog("ERROR: Connect() #1");
				return FALSE;
			}
		}
		else
		{
			$this->addToLog("ERROR: Connect() #2");
			return FALSE;
		}
	}

	function Disconnect()
	{
		if (is_int($this->delayDisconnect))
		{
			sleep($this->delayDisconnect);
		}

		$this->SendPacket("</stream:stream>");
		$this->connector->CloseSocket();

		$this->closeLogFile();
		$this->PrintLog();
	}

	function SendAuth()
	{
		$this->auth_id	= "auth_" . md5(time() . $_SERVER['REMOTE_ADDR']);

		$this->resource	= ($this->resource != NULL) ? $this->resource : ("Class.Jabber.PHP " . md5($this->auth_id));
		$this->jid		= "{$this->username}@{$this->server}/{$this->resource}";

		// request available authentication methods
		$payload	= "<username>{$this->username}</username>";
		$packet		= $this->SendIq(NULL, 'get', $this->auth_id, "jabber:iq:auth", $payload);

		// was a result returned?
		if ($this->getInfoFromIq( $packet, 'type' ) == 'result' && $this->getInfoFromIq($packet, 'id' ) == $this->auth_id )
		{
			// yes, now check for auth method availability in descending order (best to worst)

			if (!function_exists(mhash))
			{
				$this->addToLog("ATTENTION: SendAuth() - mhash() is not available; screw 0k and digest method, we need to go with plaintext auth");
			}

			// auth_0k
			if (function_exists(mhash) && isset($packet['iq']['#']['query'][0]['#']['sequence'][0]["#"]) && isset($packet['iq']['#']['query'][0]['#']['token'][0]["#"]))
			{
				return $this->sendauth_0k($packet['iq']['#']['query'][0]['#']['token'][0]["#"], $packet['iq']['#']['query'][0]['#']['sequence'][0]["#"]);
			}
			// digest
			elseif (function_exists(mhash) && isset($packet['iq']['#']['query'][0]['#']['digest']))
			{
				return $this->sendauth_digest();
			}
			// plain text
			elseif ($packet['iq']['#']['query'][0]['#']['password'])
			{
				return $this->sendauth_plaintext();
			}
			// dude, you're fucked
			{
				$this->addToLog("ERROR: SendAuth() #2 - No auth method available!");
				return FALSE;
			}
		}
		else
		{
			// no result returned
			$this->addToLog("ERROR: SendAuth() #1");
			return FALSE;
		}
	}

	function SendPacket($xml)
	{
		$xml = trim($xml);

		if ($this->connector->WriteToSocket($xml))
		{
			$this->addToLog("SEND: $xml");
			return TRUE;
		}
		else
		{
			$this->addToLog('ERROR: SendPacket() #1');
			return FALSE;
		}
	}

	function Listen()
	{
		unset($incoming);

		while ($line = $this->connector->ReadFromSocket(4096))
		{
			$incoming .= $line;
		}

		$incoming = trim($incoming);

		if ($incoming != "")
		{
			$this->addToLog("RECV: $incoming");
		}

		if ($incoming != "")
		{
			$temp = $this->split_incoming($incoming);

			for ($a = 0; $a < count($temp); $a++)
			{
				$this->packetQueue[] = $this->xmlize($temp[$a]);
			}
		}

		return TRUE;
	}

	function StripJID($jid = NULL)
	{
		preg_match("/(.*)\/(.*)/Ui", $jid, $temp);
		return ($temp[1] != "") ? $temp[1] : $jid;
	}

	function SendMessage($to, $type = "normal", $id = NULL, $content = NULL, $payload = NULL)
	{
		if ($to && is_array($content))
		{
			if (!$id)
			{
				$id = $type . "_" . time();
			}

			$content = $this->array_htmlspecialchars($content);

			$xml = "<message to='$to' type='$type' id='$id'>\n";

			if ($content['subject'])
			{
				$xml .= "<subject>" . $content['subject'] . "</subject>\n";
			}

			if ($content['thread'])
			{
				$xml .= "<thread>" . $content['thread'] . "</thread>\n";
			}

			$xml .= "<body>" . $content['body'] . "</body>\n";
			$xml .= $payload;
			$xml .= "</message>\n";


			if ($this->SendPacket($xml))
			{
				return TRUE;
			}
			else
			{
				$this->addToLog("ERROR: SendMessage() #1");
				return FALSE;
			}
		}
		else
		{
			$this->addToLog("ERROR: SendMessage() #2");
			return FALSE;
		}
	}

	function SendPresence($type = NULL, $to = NULL, $status = NULL, $show = NULL, $priority = NULL)
	{
		$xml = "<presence";
		$xml .= ($to) ? " to='$to'" : '';
		$xml .= ($type) ? " type='$type'" : '';
		$xml .= ($status || $show || $priority) ? ">\n" : " />\n";

		$xml .= ($status) ? "	<status>$status</status>\n" : '';
		$xml .= ($show) ? "	<show>$show</show>\n" : '';
		$xml .= ($priority) ? "	<priority>$priority</priority>\n" : '';

		$xml .= ($status || $show || $priority) ? "</presence>\n" : '';

		if ($this->SendPacket($xml))
		{
			return TRUE;
		}
		else
		{
			$this->addToLog("ERROR: SendPresence() #1");
			return FALSE;
		}
	}

	function SendError($to, $id = NULL, $error_number, $error_message = NULL)
	{
		$xml = "<iq type='error' to='$to'";
		$xml .= ($id) ? " id='$id'" : '';
		$xml .= ">\n";
		$xml .= "	<error code='$error_number'>";
		$xml .= ($error_message) ? $error_message : $this->error_codes[$error_number];
		$xml .= "</error>\n";
		$xml .= "</iq>";

		$this->SendPacket($xml);
	}


	function GetFirstFromQueue()
	{
		return array_shift($this->packetQueue);
	}

	function GetFromQueueById($packet_type, $id)
	{
		$found_message = FALSE;

		foreach ($this->packetQueue as $key => $value)
		{
			if ($value[$packet_type]['@']['id'] == $id)
			{
				$found_message = $value;
				unset($this->packetQueue[$key]);

				break;
			}
		}

		return (is_array($found_message)) ? $found_message : FALSE;
	}

	function CallHandler($packet = NULL)
	{
		$packet_type	= $this->get_packet_type($packet);

		if ($packet_type == "message")
		{
			$type		= $packet['message']['@']['type'];
			$type		= ($type != "") ? $type : "normal";
			$funcmeth	= "Handler_message";
		}
		elseif ($packet_type == "iq")
		{
			$type		= $packet['iq']['#']['query'][0]['@']['xmlns'];
			$funcmeth	= "Handler_iq";
		}
		elseif ($packet_type == "presence")
		{
			$type		= $packet['presence']['@']['type'];
			$type		= ($type != "") ? $type : "available";
			$funcmeth	= "Handler_presence";
		}

		if ($funcmeth != '')
		{
			if( function_exists( $funcmeth ) )
			{
				call_user_func( $funcmeth, $packet, $type );
			}
			elseif( method_exists( $this, $funcmeth ) )
			{
				call_user_func( array( &$this, $funcmeth ), $packet, $type );
			}
			else
			{
				$this->Handler_NOT_IMPLEMENTED($packet);
				$this->addToLog("ERROR: CallHandler() #1 - neither method nor function $funcmeth() available");
			}
		}
	}

	function CruiseControl($seconds = -1)
	{
		$count = 0;

		while ($count != $seconds)
		{
			$this->Listen();

			do {
				$packet = $this->GetFirstFromQueue();

				if ($packet) {
					$this->CallHandler($packet);
				}

			} while (count($this->packetQueue) > 1);

			$count += 0.25;
			usleep(250000);
			
			if ($this->lastPingTime + 180 < time())
			{
				// Modified by Nathan Fritz
				if ($this->returnedKeepAlive == FALSE)
				{
					$this->connected = FALSE;
					$this->addToLog('EVENT: Disconnected');
				}
				if ($this->returnedKeepAlive == TRUE)
				{
					$this->connected = TRUE;
				}

				$this->returnedKeepAlive = FALSE;
				$this->keepAliveId = 'keep_alive_' . time();
				//$this->SendPacket("<iq id='{$this->keepAliveId}'/>", 'CruiseControl');
				$this->SendPacket("<iq type='get' from='" . $this->username . "@" . $this->server . "/" . $this->resource . "' to='" . $this->server . "' id='" . $this->keepAliveId . "'><query xmlns='jabber:iq:time' /></iq>");
				// **

				$this->lastPingTime = time();
			}
		}

		return TRUE;
	}

	function SubscriptionAcceptRequest($to = NULL)
	{
		return ($to) ? $this->SendPresence("subscribed", $to) : FALSE;
	}

	function SubscriptionDenyRequest($to = NULL)
	{
		return ($to) ? $this->SendPresence("unsubscribed", $to) : FALSE;
	}

	function Subscribe($to = NULL)
	{
		return ($to) ? $this->SendPresence("subscribe", $to) : FALSE;
	}

	function Unsubscribe($to = NULL)
	{
		return ($to) ? $this->SendPresence("unsubscribe", $to) : FALSE;
	}
	
	function SendIq($to = NULL, $type = 'get', $id = NULL, $xmlns = NULL, $payload = NULL, $from = NULL)
	{
		if (!preg_match("/^(get|set|result|error)$/", $type))
		{
			unset($type);

			$this->addToLog("ERROR: SendIq() #2 - type must be 'get', 'set', 'result' or 'error'");
			return FALSE;
		}
		elseif ($id && $xmlns)
		{
			$xml = "<iq type='$type' id='$id'";
			$xml .= ($to) ? " to='" . htmlspecialchars($to) . "'" : '';
			$xml .= ($from) ? " from='$from'" : '';
			$xml .= ">
						<query xmlns='$xmlns'>
							$payload
						</query>
					</iq>";

			$this->SendPacket($xml);
			sleep($this->iqSleepTimer);
			$this->Listen();

			return (preg_match("/^(get|set)$/", $type)) ? $this->GetFromQueueById("iq", $id) : TRUE;
		}
		else
		{
			$this->addToLog("ERROR: SendIq() #1 - to, id and xmlns are mandatory");
			return FALSE;
		}
	}

	function GetvCard($jid = NULL, $id = NULL)
	{
		if (!$id)
		{
			$id = "vCard_" . md5(time() . $_SERVER['REMOTE_ADDR']);
		}

		if ($jid)
		{
			$xml = "<iq type='get' to='$jid' id='$id'>
						<vCard xmlns='vcard-temp'/>
					</iq>";

			$this->SendPacket($xml);
			sleep($this->iqSleepTimer);
			$this->Listen();

			return $this->GetFromQueueById("iq", $id);
		}
		else
		{
			$this->addToLog("ERROR: GetvCard() #1 - to and id are mandatory");
			return FALSE;
		}
	}

	function printLog()
	{
		if ($this->enable_logging)
		{
			if ($this->logFileHandler)
			{
				echo "<h2>Logging enabled, logged events have been written to the file {$this->logFileName}.</h2>\n";
			}
			else
			{
				echo "<h2>Logging enabled, logged events below:</h2>\n";
				echo "<pre>\n";
				echo (count($this->logArray) > 0) ? implode("\n\n\n", $this->logArray) : "No logged events.";
				echo "</pre>\n";
			}
		}
	}

	// ======================================================================
	// private methods
	// ======================================================================
	protected function  sendauth_0k($zerok_token, $zerok_sequence)
	{
		// initial hash of password
		$zerok_hash = mhash(MHASH_SHA1, $this->password);
		$zerok_hash = bin2hex($zerok_hash);

		// sequence 0: hash of hashed-password and token
		$zerok_hash = mhash(MHASH_SHA1, $zerok_hash . $zerok_token);
		$zerok_hash = bin2hex($zerok_hash);

		// repeat as often as needed
		for ($a = 0; $a < $zerok_sequence; $a++)
		{
			$zerok_hash = mhash(MHASH_SHA1, $zerok_hash);
			$zerok_hash = bin2hex($zerok_hash);
		}

		$payload = "<username>{$this->username}</username>
					<hash>$zerok_hash</hash>
					<resource>{$this->resource}</resource>";

		$packet = $this->SendIq(NULL, 'set', $this->auth_id, "jabber:iq:auth", $payload);

		// was a result returned?
		if( $this->getInfoFromIq( $packet, 'type' ) == 'result' && $this->getInfoFromIq( $packet, 'id' ) == $this->auth_id )
		{
			return TRUE;
		}
		else
		{
			$this->addToLog("ERROR: _sendauth_0k() #1");
			return FALSE;
		}
	}

	protected function sendauth_digest()
	{
		$payload = "<username>{$this->username}</username>
					<resource>{$this->resource}</resource>
					<digest>" . bin2hex(mhash(MHASH_SHA1, $this->streamId . $this->password)) . "</digest>";

		$packet = $this->SendIq(NULL, 'set', $this->auth_id, "jabber:iq:auth", $payload);

		// was a result returned?
		if( $this->getInfoFromIq( $packet, 'type' ) == 'result' && $this->getInfoFromIq( $packet, 'id' ) == $this->auth_id)
		{
			return TRUE;
		}
		else
		{
			$this->addToLog("ERROR: _sendauth_digest() #1");
			return FALSE;
		}
	}

	protected function sendauth_plaintext()
	{
		$payload = "<username>{$this->username}</username>
					<password>{$this->password}</password>
					<resource>{$this->resource}</resource>";

		$packet = $this->SendIq(NULL, 'set', $this->auth_id, "jabber:iq:auth", $payload);

		// was a result returned?
		if ($this->getInfoFromIq( $packet, 'type' ) == 'result' && $this->getInfoFromIq( $packet, 'id' ) == $this->auth_id)
		{
			return TRUE;
		}
		else
		{
			$this->addToLog("ERROR: _sendauth_plaintext() #1");
			return FALSE;
		}
	}

	protected function listen_incoming()
	{
		unset($incoming);

		while ($line = $this->connector->ReadFromSocket(4096))
		{
			$incoming .= $line;
		}

		$incoming = trim($incoming);

		if ($incoming != "")
		{
			$this->addToLog("RECV: $incoming");
		}

		return $this->xmlize($incoming);
	}

	protected function check_connected()
	{
		$incoming_array = $this->listen_incoming();

		if (is_array($incoming_array))
		{
			if ($incoming_array["stream:stream"]['@']['from'] == $this->server
				&& $incoming_array["stream:stream"]['@']['xmlns'] == "jabber:client"
				&& $incoming_array["stream:stream"]['@']["xmlns:stream"] == "http://etherx.jabber.org/streams")
			{
				$this->streamId = $incoming_array["stream:stream"]['@']['id'];

				return TRUE;
			}
			else
			{
				$this->addToLog("ERROR: _check_connected() #1");
				return FALSE;
			}
		}
		else
		{
			$this->addToLog("ERROR: _check_connected() #2");
			return FALSE;
		}
	}

	protected function get_packet_type($packet = NULL)
	{
		if (is_array($packet))
		{
			reset($packet);
			$packet_type = key($packet);
		}

		return ($packet_type) ? $packet_type : FALSE;
	}

	protected function split_incoming($incoming)
	{
		$temp = preg_split("/<(message|iq|presence|stream)/", $incoming, -1, PREG_SPLIT_DELIM_CAPTURE);
		$array = array();

		for ($a = 1; $a < count($temp); $a = $a + 2)
		{
			$array[] = "<" . $temp[$a] . $temp[($a + 1)];
		}

		return $array;
	}

	protected function createLogFile()
	{
		if ($this->logFileName != '' && $this->enable_logging)
		{
			$this->logFileHandler = fopen($this->logFileName, 'w');
		}
	}

	protected function closeLogFile()
	{
		if ($this->logFileHandler)
		{
			fclose($this->logFileHandler);
		}
	}

	// _array_htmlspecialchars()
	// applies htmlspecialchars() to all values in an array
	protected function array_htmlspecialchars($array)
	{
		if (is_array($array))
		{
			foreach ($array as $k => $v)
			{
				if (is_array($v))
				{
					$v = $this->array_htmlspecialchars($v);
				}
				else
				{
					$v = htmlspecialchars($v);
				}
			}
		}

		return $array;
	}

	function getInfoFromMessage( $packet, $key = null )
	{
		$array	= array(
			'from'		=> $packet['message']['@']['from'],
			'type'		=> $packet['message']['@']['type'],
			'id'		=> $packet['message']['@']['id'],
			'thread'	=> $packet['message']['@']['thread'][0]['#'],
			'subject'	=> $packet['message']['@']['subject'][0]['#'],
			'body'		=> $packet['message']['@']['body'][0]['#'],
			'xmlns'		=> $packet['message']['@']['x'],
			'error'		=> preg_replace("/^\/$/", "", ($packet['message']['#']['error'][0]['@']['code'] . "/" . $packet['message']['#']['error'][0]['#'])),
			);
		if( $key && in_array( $key, array_keys( $array ) ) )
			return $array[$key];
		return $array;
	}

	function getInfoFromIq( $packet, $key = null )
	{
		$array	= array(
			'from'		=> $packet['iq']['@']['from'],
			'type'		=> $packet['iq']['@']['type'],
			'id'		=> $packet['iq']['@']['id'],
			'key'		=> $packet['iq']['@']['query'][0]['#']['key'][0]['#'],
			'error'		=> preg_replace("/^\/$/", "", ($packet['iq']['#']['error'][0]['@']['code'] . "/" . $packet['iq']['#']['error'][0]['#'])),
			);
		if( $key && in_array( $key, array_keys( $array ) ) )
			return $array[$key];
		return $array;
	}

	function getInfoFromPresence( $packet, $key = null )
	{
		$array	= array(
			'from'		=> $packet['presence']['@']['from'],
			'type'		=> $packet['presence']['@']['type'],
			'status'	=> $packet['presence']['@']['status'][0]['#'],
			'show'		=> $packet['presence']['@']['show'][0]['#'],
			'priority'	=> $packet['presence']['@']['priority'][0]['#'],
			);
		if( $key && in_array( $key, array_keys( $array ) ) )
			return $array[$key];
		return $array;
	}

	function Handler_message( $packet, $type )
	{
		$from = $this->getInfoFromMessage( $packet, 'from' );
		$this->addToLog("EVENT: Message (type $type) from $from");
	}

	function Handler_iq($packet, $namespace )
	{
		if( $namespace == "jabber:iq:time" )
		{
			if ($this->keepAliveId == $this->getInfoFromIq( $packet, 'id' ))
			{
				$this->returnedKeepAlive = TRUE;
				$this->connected = TRUE;
				$this->addToLog('EVENT: Keep-Alive returned, connection alive.');
			}
			$type	= $this->getInfoFromIq( $packet, 'type' );
			$from	= $this->getInfoFromIq( $packet, 'from' );
			$id		= $this->getInfoFromIq( $packet, 'id' );
			$id		= ($id != "") ? $id : "time_" . time();

			if ($type == 'get')
			{
				$payload = "<utc>" . gmdate("Ydm\TH:i:s") . "</utc>
							<tz>" . date("T") . "</tz>
							<display>" . date("Y/d/m h:i:s A") . "</display>";

				$this->SendIq($from, 'result', $id, "jabber:iq:time", $payload);
			}
			$this->addToLog("EVENT: jabber:iq:time (type $type) from $from");
			return;
		}
		else if( $namespace == "jabber:iq:version" )
		{
			$type	= $this->getInfoFromIq( $packet, 'type' );
			$from	= $this->getInfoFromIq( $packet, 'from' );
			$id		= $this->getInfoFromIq( $packet, 'id' );
			$id		= ($id != "") ? $id : "version_" . time();
			if ($type == 'get')
			{
				$payload = "<name>{$this->iqVersionName}</name>
							<os>{$this->iqVersionOs}</os>
							<version>{$this->iqVersionVersion}</version>";
				#$this->SendIq($from, 'result', $id, "jabber:iq:version", $payload);
			}
			$this->addToLog("EVENT: jabber:iq:version (type $type) from $from -- DISABLED");
			return;
		}
		$from	= $this->getInfoFromIq( $packet, 'from' );
		$id		= $this->getInfoFromIq( $packet, 'id' );
		$this->SendError($from, $id, 501);
		$this->addToLog("EVENT: $namespace from $from");
	}
	
	// ======================================================================
	// <presence/> handlers
	// ======================================================================
	function Handler_presence_available($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );

		$show_status = $this->GetInfoFromPresenceStatus($packet) . " / " . $this->GetInfoFromPresenceShow($packet);
		$show_status = ($show_status != " / ") ? " ($addendum)" : '';

		$this->addToLog("EVENT: Presence (type: available) - $from is available $show_status");
	}

	function Handler_presence_unavailable($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );

		$show_status = $this->getInfoFromPresence( $packet, 'status' ) . " / " . $this->GetInfoFromPresenceShow($packet);
		$show_status = ($show_status != " / ") ? " ($addendum)" : '';

		$this->addToLog("EVENT: Presence (type: unavailable) - $from is unavailable $show_status");
	}

	function Handler_presence_subscribe($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );
		$this->SubscriptionAcceptRequest($from);
		$this->RosterUpdate();

		$this->logArray[] = "<b>Presence:</b> (type: subscribe) - Subscription request from $from, was added to \$this->subscriptionQueue, roster updated";
	}

	function Handler_presence_subscribed($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );
		$this->RosterUpdate();

		$this->addToLog("EVENT: Presence (type: subscribed) - Subscription allowed by $from, roster updated");
	}

	function Handler_presence_unsubscribe($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );
		$this->SendPresence("unsubscribed", $from);
		$this->RosterUpdate();

		$this->addToLog("EVENT: Presence (type: unsubscribe) - Request to unsubscribe from $from, was automatically approved, roster updated");
	}

	function Handler_presence_unsubscribed($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );
		$this->RosterUpdate();

		$this->addToLog("EVENT: Presence (type: unsubscribed) - Unsubscribed from $from's presence");
	}

	// Added By Nathan Fritz
	function Handler_presence_error($packet)
	{
		$from = $this->getInfoFromPresence( $packet, 'from' );
		$this->addToLog("EVENT: Presence (type: error) - Error in $from's presence");
	}

	// ======================================================================
	// Generic handlers
	// ======================================================================

	// Generic handler for unsupported requests
	function Handler_NOT_IMPLEMENTED($packet)
	{
		$packet_type	= $this->get_packet_type($packet);
		$from			= call_user_func(array(&$this, "GetInfoFrom" . ucfirst($packet_type) ), $packet, 'from' );
		$id				= call_user_func(array(&$this, "GetInfoFrom" . ucfirst($packet_type) ), $packet, 'id' );

		$this->SendError($from, $id, 501);
		$this->addToLog("EVENT: Unrecognized <$packet_type/> from $from");
	}

	// ======================================================================
	// Third party code
	// m@d pr0ps to the coders ;)
	// ======================================================================

	// xmlize()
	// (c) Hans Anderson / http://www.hansanderson.com/php/xml/
	function xmlize($data)
	{
		$vals = $index = $array = array();
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $data, $vals, $index);
		xml_parser_free($parser);

		$i = 0;

		$tagname = $vals[$i]['tag'];
		$array[$tagname]['@'] = $vals[$i]['attributes'];
		$array[$tagname]['#'] = $this->xml_depth($vals, $i);

		return $array;
	}

	// _xml_depth()
	// (c) Hans Anderson / http://www.hansanderson.com/php/xml/
	protected function xml_depth($vals, &$i)
	{
		$children = array();

		if ($vals[$i]['value'])
		{
			array_push($children, trim($vals[$i]['value']));
		}

		while (++$i < count($vals))
		{
			switch ($vals[$i]['type'])
			{
				case 'cdata':
					array_push($children, trim($vals[$i]['value']));
	 				break;

				case 'complete':
					$tagname = $vals[$i]['tag'];
					$size = sizeof($children[$tagname]);
					$children[$tagname][$size]['#'] = trim($vals[$i]['value']);
					if ($vals[$i]['attributes'])
					{
						$children[$tagname][$size]['@'] = $vals[$i]['attributes'];
					}
					break;

				case 'open':
					$tagname = $vals[$i]['tag'];
					$size = sizeof($children[$tagname]);
					if ($vals[$i]['attributes'])
					{
						$children[$tagname][$size]['@'] = $vals[$i]['attributes'];
						$children[$tagname][$size]['#'] = $this->xml_depth($vals, $i);
					}
					else
					{
						$children[$tagname][$size]['#'] = $this->xml_depth($vals, $i);
					}
					break;

				case 'close':
					return $children;
					break;
			}
		}
		return $children;
	}

	// TraverseXMLize()
	// (c) acebone@f2s.com, a HUGE help!
	function TraverseXMLize($array, $arrName = "array", $level = 0)
	{
		if ($level == 0)
		{
			echo "<pre>";
		}

		while (list($key, $val) = @each($array))
		{
			if (is_array($val))
			{
				$this->TraverseXMLize($val, $arrName . "[" . $key . "]", $level + 1);
			}
			else
			{
				echo '$' . $arrName . '[' . $key . '] = "' . $val . "\"\n";
			}
		}

		if ($level == 0)
		{
			echo "</pre>";
		}
	}
}
?>