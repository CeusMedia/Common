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
 * XMPPHP Roster Object
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP_XMPPHP
 *	@author			Nathanael C. Fritz <JID: fritzy@netflint.net>
 *	@author			Stephan Wentz <JID: stephan@jabber.wentz.it>
 *	@author			Michael Garvin <JID: gar@netflint.net>
 *	@copyright		2008 Nathanael C. Fritz
 */

class Roster
{
	/**
	 * @var array<string, array<string, mixed>>
	 */
	public $roster_array = [];
	/**
	 * Constructor
	 *
	 */
	public function __construct(array $roster_array = [])
	{
		if ($this->verifyRoster($roster_array)) {
			$this->roster_array = $roster_array; //Allow for prepopulation with existing roster
		} else {
			$this->roster_array = [];
		}
	}

	/**
	 *
	 * Check that a given roster array is of a valid structure (empty is still valid)
	 *
	 * @param array $roster_array
	 */
	protected function verifyRoster(array $roster_array): bool
	{
		#TODO once we know *what* a valid roster array looks like
		return True;
	}

	/**
	 *
	 * Add given contact to roster
	 *
	 * @param string $jid
	 * @param string $subscription
	 * @param string $name
	 * @param array $groups
	 */
	public function addContact(string $jid, string $subscription, string $name='', array $groups=array()): void
	{
		$contact = array('jid' => $jid, 'subscription' => $subscription, 'name' => $name, 'groups' => $groups);
		if ($this->isContact($jid)) {
			$this->roster_array[$jid]['contact'] = $contact;
		} else {
			$this->roster_array[$jid] = array('contact' => $contact);
		}
	}

	/**
	 *
	 * Retrieve contact via jid
	 *
	 * @param string $jid
	 */
	public function getContact(string $jid): ?array
	{
		if ($this->isContact($jid)) {
			return $this->roster_array[$jid]['contact'];
		}
		return null;
	}

	/**
	 *
	 * Discover if a contact exists in the roster via jid
	 *
	 * @param string $jid
	 */
	public function isContact(string $jid): bool
	{
		return (array_key_exists($jid, $this->roster_array));
	}

	/**
	 *
	 * Set presence
	 *
	 * @param string $presence
	 * @param integer $priority
	 * @param string $show
	 * @param string $status
	*/
	public function setPresence(string $presence, int $priority, string $show, string $status): void
	{
		list($jid, $resource) = explode("/", $presence);
		if ($show != 'unavailable') {
			if (!$this->isContact($jid)) {
				$this->addContact($jid, 'not-in-roster');
			}
			$resource = $resource ? $resource : '';
			$this->roster_array[$jid]['presence'][$resource] = array('priority' => $priority, 'show' => $show, 'status' => $status);
		} else { //Nuke unavailable resources to save memory
			unset($this->roster_array[$jid]['resource'][$resource]);
		}
	}

	/*
	 *
	 * Return best presence for jid
	 *
	 * @param string $jid
	 */
	public function getPresence(string $jid): ?array
	{
		$split = explode("/", $jid);
		$jid = $split[0];
		if($this->isContact($jid)) {
			$current = array('resource' => '', 'active' => '', 'priority' => -129, 'show' => '', 'status' => ''); //Priorities can only be -128 = 127
			foreach($this->roster_array[$jid]['presence'] as $resource => $presence) {
				//Highest available priority or just highest priority
				if ($presence['priority'] > $current['priority'] and (($presence['show'] == "chat" or $presence['show'] == "available") or ($current['show'] != "chat" or $current['show'] != "available"))) {
					$current = $presence;
					$current['resource'] = $resource;
				}
			}
			return $current;
		}
		return null;
	}
	/**
	 *
	 * Get roster
	 *
	 */
	public function getRoster(): array
	{
		return $this->roster_array;
	}
}
