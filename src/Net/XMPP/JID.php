<?php
/**
 *	Parser and generator for XMPP JIDs.
 *
 *	Copyright (c) 2015-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.06.2014
 *	@version		$Id$
 */
/**
 *	Parser and generator for XMPP JIDs.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.06.2014
 *	@version		$Id$
 */
class Net_XMPP_JID{

	protected $domain;
	protected $node;
	protected $resource;

	static protected $regexJid	= "#^(?:([^@/<>'\"]+)@)?([^@/<>'\"]+)(?:/([^<>'\"]*))?#";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$domain		Domain name of XMPP server
	 *	@param		string		$node		Name of node on XMPP server
	 *	@param		string		$resource	Name of client resource
	 *	@return		void
	 */
	public function __construct( $domain, $node = NULL, $resource = NULL ){
		if( preg_match( "/@/", $domain ) )
			extract( self::disassemble( $domain ) );
		$this->set( $domain, $node, $resource );
	}

	/**
	 *	Spilts JID into parts.
	 *	@static
	 *	@access		public
	 *	@return		array
	 */
	static public function disassemble( $jid ){
		if( !self::isValid( $jid ) )
			throw new InvalidArgumentException( 'Given JID is not valid.' );
		$matches	= array();
		preg_match_all( self::$regexJid, $jid, $matches );
		return array(
			'domain'	=> $matches[2][0],
			'node'		=> $matches[1][0],
			'resource'	=> $matches[3][0]
		);
	}

	/**
	 *	Returns JID.
	 *	@access		public
	 *	@return		string
	 */
	public function get(){
		return self::getJid( $this->domain, $this->node, $this->resource );
	}

	/**
	 *	Returns JID domain part.
	 *	@access		public
	 *	@return		string
	 */
	public function getDomain(){
		return $this->domain;
	}

	/**
	 *	Builds and returns JID from parts.
	 *	@static
	 *	@access		public
	 *	@param		string		$domain		Domain name of XMPP server
	 *	@param		string		$node		Name of node on XMPP server
	 *	@param		string		$resource	Name of client resource
	 *	@return		string
	 */
	static public function getJid( $domain, $node = NULL, $resource = NULL ){
		$jid	= $domain;
		if( strlen( trim( $node ) ) ) 
			$jid	= $node.'@'.$domain;
		if( strlen( trim( $resource ) ) )
			$jid	.= "/".$resource;
		return $jid;
	}

	/**
	 *	Returns JID node part.
	 *	@access		public
	 *	@return		string
	 */
	public function getNode(){
		return $this->node;
	}

	/**
	 *	Returns JID resource part.
	 *	@access		public
	 *	@return		string
	 */
	public function getResource(){
		return $this->resource;
	}

	/**
	 *	Indicates whether a given JID is of valid syntax.
	 *	@access		public
	 *	@param		string		$jid		JID
	 *	@return		boolean
	 */
	static public function isValid( $jid ){
		return preg_match( self::$regexJid, $jid );
	}

	/**
	 *	Sets JID by parts.
	 *	@access		public
	 *	@param		string		$domain		Domain name of XMPP server
	 *	@param		string		$node		Name of node on XMPP server
	 *	@param		string		$resource	Name of client resource
	 *	@return		void
	 */
	public function set( $domain, $node = NULL, $resource = NULL ){
		if( !strlen( trim( $domain ) ) )
			throw new InvalidArgumentException( 'Domain is missing' );
		$this->domain	= $domain;
		$this->node		= $node;
		$this->resource	= $resource;
	}

	/**
	 *	Sets JID.
	 *	@access		public
	 *	@param		string		$jid		JID: domain + optional node and resource
	 *	@return		void
	 */
	public function setJid( $jid ){
		extract( self::disassemble( $jid ) );
		$this->set( $domain, $node, $resource );
	}
}
