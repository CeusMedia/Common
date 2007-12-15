<?php
import( 'de.ceus-media.net.jabber.Jabber' );
/***************************************************************************
	Class.Jabber.PHP v0.4.2
	(c) 2004 Nathan "Fritzy" Fritz
	http://cjphp.netflint.net *** fritzy@netflint.net

	This is a bugfix version, specifically for those who can't get 
	0.4 to work on Jabberd2 servers. 

	last modified: 24.03.2004 13:01:53 

 ***************************************************************************/
/*
	Jabber::RosterUpdate()
	Jabber::RosterAddUser($jid {string}, $id {string}, $name {string})
	Jabber::RosterRemoveUser($jid {string}, $id {string})
	Jabber::RosterExistsJID($jid {string})
*/
class JabberRoster extends Jabber
{
	function AccountRegistration($reg_email = NULL, $reg_name = NULL)
	{
		$packet = $this->SendIq($this->server, 'get', 'reg_01', 'jabber:iq:register');

		if ($packet)
		{
			$key = $this->getInfoFromIqKey( $packet, 'key' );	// just in case a key was passed back from the server
			unset($packet);

			$payload = "<username>{$this->username}</username>
						<password>{$this->password}</password>
						<email>$reg_email</email>
						<name>$reg_name</name>\n";

			$payload .= ($key) ? "<key>$key</key>\n" : '';

			$packet = $this->SendIq( $this->server, 'set', "reg_01", "jabber:iq:register", $payload );

			if( $this->getInfoFromIq( $packet, 'type' ) == 'result' )
			{
				if (isset($packet['iq']['#']['query'][0]['#']['registered'][0]['#']))
				{
					$return_code = 1;
				}
				else
				{
					$return_code = 2;
				}

				if ($this->resource)
				{
					$this->jid = "{$this->username}@{$this->server}/{$this->resource}";
				}
				else
				{
					$this->jid = "{$this->username}@{$this->server}";
				}

			}
			else if( $this->getInfoFromIq( $packet, 'type' ) == 'error' && isset( $packet['iq']['#']['error'][0]['#'] ) )
			{
				// "conflict" error, i.e. already registered
				if ($packet['iq']['#']['error'][0]['@']['code'] == '409')
				{
					$return_code = 1;
				}
				else
				{
					$return_code = "Error " . $packet['iq']['#']['error'][0]['@']['code'] . ": " . $packet['iq']['#']['error'][0]['#'];
				}
			}

			return $return_code;

		}
		else
		{
			return 3;
		}
	}

	function RosterUpdate()
	{
		$roster_request_id = "roster_" . time();

		$incoming_array = $this->SendIq(null, 'get', $roster_request_id, "jabber:iq:roster");

		if( is_array($incoming_array ) )
		{
			if( $incoming_array['iq']['@']['type'] == 'result'
				&& $incoming_array['iq']['@']['id'] == $roster_request_id
				&& $incoming_array['iq']['#']['query']['0']['@']['xmlns'] == "jabber:iq:roster")
			{
				$number_of_contacts = count($incoming_array['iq']['#']['query'][0]['#']['item']);
				$this->roster = array();

				for ($a = 0; $a < $number_of_contacts; $a++)
				{
					$this->roster[$a] = array(
						"jid"			=> strtolower($incoming_array['iq']['#']['query'][0]['#']['item'][$a]['@']['jid']),
						"name"			=> $incoming_array['iq']['#']['query'][0]['#']['item'][$a]['@']['name'],
						"subscription"	=> $incoming_array['iq']['#']['query'][0]['#']['item'][$a]['@']['subscription'],
						"group"			=> $incoming_array['iq']['#']['query'][0]['#']['item'][$a]['#']['group'][0]['#']
					);
				}

				return true;
			}
			else
			{
				$this->addToLog("ERROR: RosterUpdate() #1");
				return false;
			}
		}
		else
		{
			$this->addToLog("ERROR: RosterUpdate() #2");
			return false;
		}
	}

	function RosterAddUser($jid = null, $id = null, $name = null)
	{
		$id = ($id) ? $id : "adduser_" . time();

		if ($jid)
		{
			$payload = "		<item jid='$jid'";
			$payload .= ($name) ? " name='" . htmlspecialchars($name) . "'" : '';
			$payload .= "/>\n";

			$packet = $this->SendIq(null, 'set', $id, "jabber:iq:roster", $payload);

			if ($this->GetInfoFromIqType($packet) == 'result')
			{
				$this->RosterUpdate();
				return true;
			}
			else
			{
				$this->addToLog("ERROR: RosterAddUser() #2");
				return false;
			}
		}
		else
		{
			$this->addToLog("ERROR: RosterAddUser() #1");
			return false;
		}
	}

	function RosterRemoveUser($jid = null, $id = null)
	{
		$id = ($id) ? $id : 'deluser_' . time();

		if ($jid && $id)
		{
			$packet = $this->SendIq(null, 'set', $id, "jabber:iq:roster", "<item jid='$jid' subscription='remove'/>");

			if ($this->GetInfoFromIqType($packet) == 'result')
			{
				$this->RosterUpdate();
				return true;
			}
			else
			{
				$this->addToLog("ERROR: RosterRemoveUser() #2");
				return false;
			}
		}
		else
		{
			$this->addToLog("ERROR: RosterRemoveUser() #1");
			return false;
		}
	}

	function RosterExistsJID($jid = null)
	{
		if ($jid)
		{
			if ($this->roster)
			{
				for ($a = 0; $a < count($this->roster); $a++)
				{
					if ($this->roster[$a]['jid'] == strtolower($jid))
					{
						return $a;
					}
				}
			}
			else
			{
				$this->addToLog("ERROR: RosterExistsJID() #2");
				return false;
			}
		}
		else
		{
			$this->addToLog("ERROR: RosterExistsJID() #1");
			return false;
		}
	}
}
?>