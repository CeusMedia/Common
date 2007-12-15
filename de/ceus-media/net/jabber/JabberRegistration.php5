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
	Jabber::AccountRegistration($reg_email {string}, $reg_name {string})
	Jabber::TransportRegistrationDetails($transport {string})
	Jabber::TransportRegistration($transport {string}, $details {array})
*/
class JabberRegistration extends Jabber
{
	// get the transport registration fields
	// method written by Steve Blinch, http://www.blitzaffe.com 
	function TransportRegistrationDetails($transport)
	{
		$this->txnid++;
		$packet = $this->SendIq($transport, 'get', "reg_{$this->txnid}", "jabber:iq:register", NULL, $this->jid);

		if ($packet)
		{
			$res = array();

			foreach ($packet['iq']['#']['query'][0]['#'] as $element => $data)
			{
				if ($element != 'instructions' && $element != 'key')
				{
					$res[] = $element;
				}
			}

			return $res;
		}
		else
		{
			return 3;
		}
	}

	// register with the transport
	// method written by Steve Blinch, http://www.blitzaffe.com 
	function TransportRegistration($transport, $details)
	{
		$this->txnid++;
		$packet = $this->SendIq($transport, 'get', "reg_{$this->txnid}", "jabber:iq:register", NULL, $this->jid);

		if ($packet)
		{
			$key = $this->getInfoFromIq( $packet, 'key' );	// just in case a key was passed back from the server
			unset($packet);
		
			$payload = ($key) ? "<key>$key</key>\n" : '';
			foreach ($details as $element => $value)
			{
				$payload .= "<$element>$value</$element>\n";
			}
		
			$packet = $this->SendIq($transport, 'set', "reg_{$this->txnid}", "jabber:iq:register", $payload);
		
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
			}
			elseif( $this->getInfoFromIq( $packet, 'type' ) == 'error' )
			{
				if (isset($packet['iq']['#']['error'][0]['#']))
				{
					$return_code = "Error " . $packet['iq']['#']['error'][0]['@']['code'] . ": " . $packet['iq']['#']['error'][0]['#'];
					$this->addToLog('ERROR: TransportRegistration()');
				}
			}

			return $return_code;
		}
		else
		{
			return 3;
		}
	}
}
?>