<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 */

namespace CeusMedia\Common\Net\HTTP\Header;

use CeusMedia\Common\Deprecation;
use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use CeusMedia\Common\Net\HTTP\Header\Renderer as HeaderRenderer;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 *	@see			http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2 RFC 2616 HTTP Message Headers
 *
 *	GENERAL
 *	-------
 *	Cache-Control
 *	Connection
 *	Date
 *	Pragma
 *	Trailer
 *	Transfer-Encoding
 *	Upgrade
 *	Via
 *	Warning
 *
 *	REQUEST
 *	-------
 *	Accept
 *	Accept-Charset
 *	Accept-Encoding
 *	Accept-Language
 *	Authorization
 *	Expect
 *	From
 *	Host
 *	If-Match
 *	If-Modified-Since
 *	If-None-Match
 *	If-Range
 *	If-Unmodified-Since
 *	Max-Forwards
 *	Proxy-Authorization
 *	Range
 *	Referer
 *	TE
 *	User-Agent
 *
 *	RESPONSE
 *	--------
 *	Accept-Ranges
 *	Age
 *	ETag
 *	Location
 *	Proxy-Authenticate
 *	Retry-After
 *	Server
 *	Vary
 *	WWW-Authenticate
 *
 *	ENTITY
 *	------
 *	Allow
 *	Content-Encoding
 *	Content-Language
 *	Content-Length
 *	Content-Location
 *	Content-MD5
 *	Content-Range
 *	Content-Type
 *	Expires
 *	Last-Modified
 */
class Section
{
	protected $fields	= [
		'general'	=> [
			'cache-control'			=> [],
			'connection'			=> [],
			'date'					=> [],
			'pragma'				=> [],
			'trailer'				=> [],
			'transfer-encoding'		=> [],
			'upgrade'				=> [],
			'via'					=> [],
			'warning'				=> [],
		],
		'request'	=> [
			'accept'				=> [],
			'accept-charset'		=> [],
			'accept-encoding'		=> [],
			'accept-language'		=> [],
			'authorization'			=> [],
			'expect'				=> [],
			'from'					=> [],
			'host'					=> [],
			'if-match'				=> [],
			'if-modified-since'		=> [],
			'if-none-match'			=> [],
			'if-range'				=> [],
			'if-unmodified-since'	=> [],
			'max-forwards'			=> [],
			'proxy-authorization'	=> [],
			'range'					=> [],
			'referer'				=> [],
			'te'					=> [],
			'user-agent'			=> [],
		],
		'response'	=> [
			'accept-ranges'			=> [],
			'age'					=> [],
			'etag'					=> [],
			'location'				=> [],
			'proxy-authenticate'	=> [],
			'retry-after'			=> [],
			'server'				=> [],
			'vary'					=> [],
			'www-authenticate'		=> [],
		],
		'entity'	=> [
			'allow'		=> [],
			'content-encoding'		=> [],
			'content-language'		=> [],
			'content-length'		=> [],
			'content-location'		=> [],
			'content-md5'			=> [],
			'content-range'			=> [],
			'content-type'			=> [],
			'expires'				=> [],
			'last-modified'			=> [],
		],
		'others'	=> []
	];

	public function addField( HeaderField $field ): self
	{
		return $this->setField( $field, FALSE );
	}

	public function addFieldPair( string $name, $value ): self
	{
		$field	= new HeaderField( $name, $value );
		return $this->addField( $field );
	}

	/**
	 *	Add header fields from assoc array.
	 *	@access		public
	 *	@param		array		$fieldPairs		Map of header field names and values
	 *	@return		self
	 */
	public function addFieldPairs( array $fieldPairs = [] ): self
	{
		foreach( $fieldPairs as $key => $value )
			$this->addFieldPair( $key, $value );
		return $this;
	}

	public function addFields( array $fields = [] ): self
	{
		foreach( $fields as $field )
			$this->addField( $field );
		return $this;
	}

	public function getField( string $name ): array
	{
		$name	= strtolower( $name );
		foreach( $this->fields as $sectionName => $sectionPairs )
			if( array_key_exists( $name, $sectionPairs ) )
				return $this->fields[$sectionName][$name];
		return [];
	}

	public function getFields(): array
	{
		$list	= [];
		foreach( $this->fields as $sectionName => $sectionPairs )
			foreach( $sectionPairs as $name => $fieldList )
				if( count( $fieldList ) )
					foreach( $fieldList as $field )
						$list[]	= $field;
		return $list;
	}

	public function getFieldsByName( string $name, bool $latestOnly = FALSE )
	{
		$name	= strtolower( $name );
		foreach( $this->fields as $sectionName => $sectionPairs ){
			if( array_key_exists( $name, $sectionPairs ) ){
				if( $latestOnly )
					return end( $sectionPairs[$name] );
				return $sectionPairs[$name];
			}
		}
		if( $latestOnly )
			return NULL;
		return [];
	}

	public static function getInstance(): self
	{
		return new self();
	}

	public function hasField( string $name ): bool
	{
		$name	= strtolower( $name );
		foreach( $this->fields as $sectionName => $sectionPairs )
			if( array_key_exists( $name, $sectionPairs ) )
				return (bool) count( $this->fields[$sectionName][$name] );
		return FALSE;
	}

	public function removeField( HeaderField $field ): self
	{
		$name	= $field->getName();
		foreach( $this->fields as $sectionName => $sectionPairs )
		{
			if( !array_key_exists( $name, $sectionPairs ) )
				continue;
			foreach( $sectionPairs as $nr => $sectionField )
				if( $sectionField == $field )
					unset( $this->fields[$sectionName][$name][$nr] );
		}
		return $this;
	}

	public function removeByName( string $name ): self
	{
		if( isset( $this->fields['others'][$name] ) )
			unset( $this->fields['others'][$name] );
		foreach( $this->fields as $sectionName => $sectionPairs )
			if( array_key_exists( $name, $sectionPairs ) )
				$this->fields[$sectionName][$name]		= [];
		return $this;
	}

	public function setField( HeaderField $field, bool $emptyBefore = TRUE ): self
	{
		$name	= $field->getName();
		foreach( $this->fields as $sectionName => $sectionPairs ){
			if( array_key_exists( $name, $sectionPairs ) ){
				if( $emptyBefore )
					$this->fields[$sectionName][$name]	= [];
				$this->fields[$sectionName][$name][]	= $field;
				return $this;
			}
		}
		if( $emptyBefore || !isset( $this->fields['others'][$name] ) )
			$this->fields['others'][$name]	= [];
		$this->fields['others'][$name][]	= $field;
		return $this;
	}

	public function setFieldPair( string $name, $value, bool $emptyBefore = TRUE ): self
	{
		$this->setField( new HeaderField( $name, $value ), $emptyBefore );
		return $this;
	}

	public function render(): string
	{
		return HeaderRenderer::render( $this );
	}
}
