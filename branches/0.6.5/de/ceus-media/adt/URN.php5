<?php
/**
 *	@package		adt
 *	@see		http://www.ietf.org/rfc/rfc2141.txt
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