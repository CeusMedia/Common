<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser and generator for XMPP JIDs.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\XMPP;

use InvalidArgumentException;

/**
 *	Parser and generator for XMPP JIDs.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class JID
{
	protected string $domain;
	protected ?string $node			= NULL;
	protected ?string $resource		= NULL;

	static protected string $regexJid	= "#^(?:([^@/<>'\"]+)@)?([^@/<>'\"]+)(?:/([^<>'\"]*))?#";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$domain		Domain name of XMPP server
	 *	@param		string|NULL		$node		Name of node on XMPP server
	 *	@param		string|NULL		$resource	Name of client resource
	 *	@return		void
	 */
	public function __construct( string $domain, ?string $node = NULL, ?string $resource = NULL )
	{
		if( str_contains( $domain, "@" ) ){
			$struct	= self::disassemble( $domain );
			extract( $struct );
		}
		$this->set( $domain, $node, $resource );
	}

	/**
	 *	Splits JID into parts.
	 *	@static
	 *	@access		public
	 *	@param		string		$jid				Jabber ID to split into parts
	 *	@return		array
	 *	@throws		InvalidArgumentException		if given JID is invalid
	 */
	static public function disassemble( string $jid ): array
	{
		if( !self::isValid( $jid ) )
			throw new InvalidArgumentException( 'Given JID is not valid.' );
		$matches	= [];
		preg_match_all( self::$regexJid, $jid, $matches );
		return [
			'domain'	=> $matches[2][0],
			'node'		=> $matches[1][0],
			'resource'	=> $matches[3][0],
		];
	}

	/**
	 *	Returns JID.
	 *	@access		public
	 *	@return		string
	 */
	public function get(): string
	{
		return self::getJid( $this->domain, $this->node, $this->resource );
	}

	/**
	 *	Returns JID domain part.
	 *	@access		public
	 *	@return		string
	 */
	public function getDomain(): string
	{
		return $this->domain;
	}

	/**
	 *	Builds and returns JID from parts.
	 *	@static
	 *	@access		public
	 *	@param		string			$domain		Domain name of XMPP server
	 *	@param		string|NULL		$node		Name of node on XMPP server
	 *	@param		string|NULL		$resource	Name of client resource
	 *	@return		string
	 */
	static public function getJid( string $domain, ?string $node = NULL, ?string $resource = NULL ): string
	{
		$jid	= $domain;
		if( $node !== NULL && strlen( trim( $node ) ) !== 0 )
			$jid	= $node.'@'.$domain;
		if( $resource !== NULL && strlen( trim( $resource ) ) !== 0 )
			$jid	.= '/'.$resource;
		return $jid;
	}

	/**
	 *	Returns JID node part.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getNode(): ?string
	{
		return $this->node;
	}

	/**
	 *	Returns JID resource part.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getResource(): ?string
	{
		return $this->resource;
	}

	/**
	 *	Indicates whether a given JID is of valid syntax.
	 *	@access		public
	 *	@param		string		$jid		JID
	 *	@return		boolean
	 */
	static public function isValid( string $jid ): bool
	{
		return preg_match( self::$regexJid, $jid );
	}

	/**
	 *	Sets JID by parts.
	 *	@access		public
	 *	@param		string			$domain		Domain name of XMPP server
	 *	@param		string|NULL		$node		Name of node on XMPP server
	 *	@param		string|NULL		$resource	Name of client resource
	 *	@return		self
	 */
	public function set( string $domain, ?string $node = NULL, ?string $resource = NULL ): self
	{
		if( strlen( trim( $domain ) ) === 0 )
			throw new InvalidArgumentException( 'Domain is missing' );
		$this->domain	= $domain;
		$this->node		= $node;
		$this->resource	= $resource;
		return $this;
	}

	/**
	 *	Sets JID.
	 *	@access		public
	 *	@param		string		$jid		JID: domain + optional node and resource
	 *	@return		self
	 */
	public function setJid( string $jid ): self
	{
		$parts	= (object) self::disassemble( $jid );
		$this->set( $parts->domain, $parts->node, $parts->resource );
		return $this;
	}
}
