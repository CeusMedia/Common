<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */

/**
 *	...
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use CeusMedia\Common\Net\HTTP\Header\Renderer as HeaderRenderer;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2 RFC 2616 HTTP Message Headers
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
	protected array $sectionedFields	= [
		'general'	=> [
			'cache-control',
			'connection',
			'date',
			'pragma',
			'trailer',
			'transfer-encoding',
			'upgrade',
			'via',
			'warning',
		],
		'request'	=> [
			'accept',
			'accept-charset',
			'accept-encoding',
			'accept-language',
			'authorization',
			'expect',
			'from',
			'host',
			'if-match',
			'if-modified-since',
			'if-none-match',
			'if-range',
			'if-unmodified-since',
			'max-forwards',
			'proxy-authorization',
			'range',
			'referer',
			'te',
			'user-agent',
		],
		'response'	=> [
			'accept-ranges',
			'age',
			'etag',
			'location',
			'proxy-authenticate',
			'retry-after',
			'server',
			'vary',
			'www-authenticate',
		],
		'entity'	=> [
			'allow',
			'content-encoding',
			'content-language',
			'content-length',
			'content-location',
			'content-md5',
			'content-range',
			'content-type',
			'expires',
			'last-modified',
		],
		'others'	=> []
	];

	protected array $fields			= [];

	public function addField( HeaderField $field ): self
	{
		return $this->setField( $field, FALSE );
	}

	/**
	 *	@param		string				$name
	 *	@param		string|float|int	$value
	 *	@return		self
	 */
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

	public function getField( string $name ): ?HeaderField
	{
		$fields		= $this->fields[strtolower( $name )] ?? [];
		return 0 !== count( $fields ) ? end( $fields ) : NULL;
	}

	/**
	 *	Returns list of all set fields.
	 *	@return		array
	 */
	public function getFields(): array
	{
		$list	= [];
		foreach( $this->fields as $name => $fields )
			foreach( $fields as $field )
				$list[]	= $field;
		return $list;
	}

	/**
	 *	@param		string		$name
	 *	@param		bool		$latestOnly
	 *	@return		array<HeaderField>|HeaderField|NULL
	 */
	public function getFieldsByName( string $name, bool $latestOnly = FALSE )
	{
		if( $latestOnly )
			return $this->getField( $name );
		return $this->fields[strtolower( $name )] ?? [];
	}

	public static function getInstance(): self
	{
		return new self();
	}

	public function getSectionedFields(): array
	{
		$list	= [];
		$names	= array_keys( $this->fields );
		foreach($this->sectionedFields as $sectionName => $sectionFields ){
			foreach( $sectionFields as $sectionFieldName ){
				if( 0 !== count( $this->fields[$sectionFieldName] ?? [] ) ){
					$list[$sectionName]	??= [];
					$list[$sectionName][$sectionFieldName]	= $this->fields[$sectionFieldName];
				}
				$names	= array_diff( $names, [$sectionFieldName] );
			}
		}
		if( 0 !== count( $names ) ){
			$list['others']	= [];
			foreach( $names as $name )
				$list['others'][$name]	= $this->fields[$name];
		}
		return $list;
	}

	public function hasField( string $name ): bool
	{
		return 0 !== count( $this->fields[strtolower( $name )] ?? [] );
	}

	public function removeField( HeaderField $field ): self
	{
		$name			= strtolower( $field->getName() );
		foreach( $this->fields[$name] ?? [] as $nr => $item )
			if( $item === $field || $item->getValue() === $field->getValue() )
				unset( $this->fields[$name][$nr] );
		return $this;
	}

	public function removeByName( string $name ): self
	{
		$name	= strtolower( $name );
		$this->fields[$name]	= [];
		return $this;
	}

	public function setField( HeaderField $field, bool $emptyBefore = TRUE ): self
	{
		$name		= strtolower( $field->getName() );
		$fields		= !$emptyBefore ? ( $this->fields[$name] ?? [] ) : [];
		$fields[]	= $field;
		$this->fields[$name]	= $fields;
		return $this;
	}

	public function setFieldPair( string $name, string|int|float $value, bool $emptyBefore = TRUE ): self
	{
		$this->setField( new HeaderField( $name, $value ), $emptyBefore );
		return $this;
	}

	public function render(): string
	{
		return HeaderRenderer::render( $this );
	}
}
