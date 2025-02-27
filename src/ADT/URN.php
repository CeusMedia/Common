<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.ietf.org/rfc/rfc2141.txt
 */

namespace CeusMedia\Common\ADT;

use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.ietf.org/rfc/rfc2141.txt
 */
class URN
{
	public string $nid;

	public string $nss;

	public function __construct( string $nid, ?string $nss = NULL )
	{
		$nid	= preg_replace( "/^urn:/i", "", $nid );
		if( $nss === NULL && preg_match( "/^\S+:\S+$/", $nid ) ){
			$parts	= explode( ":", $nid );
			$nid	= array_shift( $parts );
			$nss	= implode( ":", $parts );
		}
		$this->setIdentifier( $nid );
		$this->setSpecificString( $nss );
	}

	public function getIdentifier(): string
	{
		return $this->nid;
	}

	public function getSpecificString(): string
	{
		return $this->nss;
	}

	public function getUrn( bool $withoutPrefix = FALSE ): string
	{
		$urn	= (string) $this;
		if( $withoutPrefix )
			$urn	= preg_replace( "/^urn:/", "", $urn );
		return $urn;
	}

	public function setIdentifier( string $nid ): self
	{
		if( !preg_match( '/^[a-z0-9][a-z0-9-]{1,31}$/i', $nid ) )
			throw new InvalidArgumentException( 'Namespace Identifier "'.$nid.'" is invalid.' );
		$this->nid	= $nid;
		return $this;
	}

	public function setSpecificString( string $nss ): self
	{
		$alpha		= 'a-z0-9';
		$others		= '()+,-.:=@;$_!*\\';
		$reserved	= '%\/?#';
		$trans		= '(['.$alpha.$others.$reserved.'])';
		$hex		= '(%[0-9a-f]{2})';
		if( !preg_match( '/^('.$trans.'|'.$hex.')+$/i', $nss ) )
			throw new InvalidArgumentException( 'Namespace Specific String "'.$nss.'" is invalid.' );
		$this->nss	= $nss;
		return $this;
	}

	public function __toString()
	{
		return "urn:".$this->nid.":".$this->nss;
	}
}
