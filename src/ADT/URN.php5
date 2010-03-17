<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://www.ietf.org/rfc/rfc2141.txt
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://www.ietf.org/rfc/rfc2141.txt
 */
class ADT_URN
{
	public $nid;
	public $nss;
	
	public function __construct( $nid, $nss = NULL )
	{
		$nid	= preg_replace( "/^urn:/i", "", $nid );
		if( $nss === NULL && preg_match( "/^\S+:\S+$/", $nid ) )
		{
			$parts	= explode( ":", $nid );
			$nid	= array_shift( $parts );
			$nss	= implode( ":", $parts );
		}
		$this->setIdentifier( $nid );
		$this->setSpecificString( $nss );
	}
	
	public function getIdentifier()
	{
		return $this->nid;
	}
	
	public function getSpecificString()
	{
		return $this->nss;
	}
	
	public function getUrn( $withoutPrefix = FALSE )
	{
		$urn	= (string) $this;
		if( $withoutPrefix )
			$urn	= preg_replace( "/^urn:/", "", $urn );
		return $urn;
	}
	
	public function setIdentifier( $nid )
	{
		if( !preg_match( '/^[a-z0-9][a-z0-9-]{1,31}$/i', $nid ) )
			throw new InvalidArgumentException( 'Namespace Identifier "'.$nid.'" is invalid.' );
		$this->nid	= $nid;
	}
	
	public function setSpecificString( $nss )
	{
		$alpha		= 'a-z0-9';
		$others		= '()+,-.:=@;$_!*\\';
		$reserved	= '%\/?#';
		$trans		= '(['.$alpha.$others.$reserved.'])';
		$hex		= '(%[0-9a-f]{2})';
		if( !preg_match( '/^('.$trans.'|'.$hex.')+$/i', $nss ) )
			throw new InvalidArgumentException( 'Namespace Specific String "'.$nss.'" is invalid.' );
		$this->nss	= $nss;
	}
	
	public function __toString()
	{
		return "urn:".$this->nid.":".$this->nss;
	}
}
?>