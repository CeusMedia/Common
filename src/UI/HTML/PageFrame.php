<?php
/** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds XHTML Page Frame containing Doctype, Meta Tags, Title, Title, JavaScripts, Stylesheets and additional Head and Body.
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Renderable;
use InvalidArgumentException;
use OutOfRangeException;

/**
 *	Builds XHTML Page Frame containing Doctype, Meta Tags, Title, Title, JavaScripts, Stylesheets and additional Head and Body.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PageFrame
{
	public string $indent			= "  ";

	protected ?string $title		= NULL;
	protected ?string $heading		= NULL;
	protected array $scripts		= [];
	protected array $metaTags		= [];
	protected array $links			= [];
	protected ?string $baseHref		= NULL;
	protected array $head			= [];
	protected array $body			= [];
	protected string $charset;
	protected string $language;
	protected string $docType		= 'XHTML_10_STRICT';
	protected array $docTypes		= [
		'HTML_5'					=> '<!DOCTYPE html>',
		'XHTML_11'					=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "https://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
		'XHTML_10_STRICT'			=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'XHTML_10_TRANSITIONAL'		=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'XHTML_10_FRAMESET'			=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'HTML_401_STRICT'			=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/html4/strict.dtd">',
		'HTML_401_TRANSITIONAL'		=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "https://www.w3.org/TR/html4/loose.dtd">',
		'HTML_401_FRAMESET'			=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "https://www.w3.org/TR/html4/frameset.dtd">',
	];
	protected array $prefixes		= [];
	protected ?string $profile		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$docType		Document type key
	 *	@param		string		$language		Language of Page
	 *	@param		string		$charset		Default Charset Encoding
	 *	@param		string		$scriptType		Default JavaScript MIME-Type
	 *	@param		string		$styleType		Default Stylesheet MIME-Type
	 *	@return		void
	 */
	public function __construct( string $docType = "XHTML_10_STRICT", string $language = "en", string $charset = "UTF-8", string $scriptType = "text/javascript", string $styleType = "text/css" )
	{
		$this->setDocType( $docType );
		$this->setLanguage( $language );
		$this->charset	= $charset;
		if( $docType == "HTML_5" ){
			$this->metaTags["charset"]	= ['charset' => $charset];
			$this->addMetaTag( "http-equiv", "Content-Type", "text/html" );
		}
		else{
			$this->addMetaTag( "http-equiv", "Content-Type", "text/html; charset=".strtoupper( $charset ) );
		}
		$this->addMetaTag( "http-equiv", "Content-Script-Type", $scriptType );
		$this->addMetaTag( "http-equiv", "Content-Style-Type", $styleType );
	}

	/**
	 *	Adds further HTML to Body.
	 *	@access		public
	 *	@param		string|Renderable		$string			HTML String for Head
	 *	@return		self
	 */
	public function addBody( $string ): self
	{
		$this->body[]	= $string instanceof Renderable ? $string->render() : $string;
		return $this;
	}

	/**
	 *	Adds a favourite Icon to the Page (supports ICO and other Formats).
	 *	@access		public
	 *	@param		string|URL		$url			URL of Icon or Image
	 *	@return		self
	 */
	public function addFavouriteIcon( $url ): self
	{
		$url	= $url instanceof URL ? $url->get() : $url;
		$ext	= strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		$type	= "image/x-icon";
		if( $ext === 'png' )
			$type	= "image/png";
		if( $ext === 'gif' )
			$type	= "image/gif";
		$this->links[]	= [
			'rel'		=> "icon",
			'type'		=> $type,
			'href'		=> $url,
		];
		return $this;
	}

	/**
	 *	Adds further HTML to Head.
	 *	@access		public
	 *	@param		string|Renderable		$string			HTML String for Head
	 *	@return		self
	 */
	public function addHead( $string ): self
	{
		$this->head[]	= $string instanceof Renderable ? $string->render() : $string;
		return $this;
	}

	/**
	 *	Adds a JavaScript Link to Head.
	 *	@access		public
	 *	@param		URL|string		$uri			URI to Script
	 *	@param		string|NULL		$type			MIME Type of Script
	 *	@param		string|NULL		$charset		Charset of Script
	 *	@return		self
	 */
	public function addJavaScript( $uri, ?string $type = NULL, ?string $charset = NULL ): self
	{
		$typeDefault	= 'text/javascript';
		if( isset( $this->metaTags["http-equiv:content-script-type"] ) )
			$typeDefault	= $this->metaTags["http-equiv:content-script-type"]['content'];
		$scriptData	= [
			'type'		=> $type ?: $typeDefault,
			'charset'	=> $charset ?: NULL,
			'src'		=> $uri instanceof URL ? $uri->get() : $uri,
		];
		$this->scripts[]	= $scriptData;
		return $this;
	}

	/**
	 *	Adds link to head.
	 *	@access		public
	 *	@param		URL|string		$uri			URI to linked resource
	 *	@param		string			$relation		Relation to resource like stylesheet, canonical etc.
	 *	@param		string|NULL		$type			Type of resource
	 *	@return		self
	 */
	public function addLink( $uri, string $relation, ?string $type = NULL ): self
	{
		$this->links[]	= [
			'uri'		=> $uri instanceof URL ? $uri->get() : $uri,
			'rel'		=> $relation,
			'type'		=> $type
		];
		return $this;
	}

	/**
	 *	Adds a Meta Tag to Head.
	 *	@access		public
	 *	@param		string		$type			Meta Tag Key Type (name|http-equiv)
	 *	@param		string		$key			Meta Tag Key Name
	 *	@param		string		$value			Meta Tag Value
	 *	@return		self
	 */
	public function addMetaTag( string $type, string $key, string $value ): self
	{
		$metaData	= [
			$type		=> $key,
			'content'	=> $value,
		];
		$this->metaTags[strtolower( $type.":".$key )]	= $metaData;
		return $this;
	}

	/**
	 *	@param		string			$prefix
	 *	@param		URL|string		$namespace
	 *	@return		self
	 */
	public function addPrefix( string $prefix, $namespace ): self
	{
		$this->prefixes[$prefix]	= $namespace instanceof URL ? $namespace->get() : $namespace;
		return $this;
	}

	public function addScript( string $script, string $type = "text/javascript" ): self
	{
		$this->addHead( Tag::create( 'script', $script, ['type' => $type] ) );
		return $this;
	}

	/**
	 *	Adds a Stylesheet Link to Head.
	 *	@access		public
	 *	@param		URL|string		$uri			URI to CSS File
	 *	@param		string			$media			Media Type (all|screen|print|...), default: screen
	 *	@param		string|NULL		$type			Content Type, by default 'text/css'
	 *	@return		self
	 *	@see		https://www.w3.org/TR/html4/types.html#h-6.13
	 */
	public function addStylesheet( $uri, string $media = "all", ?string $type = NULL ): self
	{
		$typeDefault	= 'text/css';
		if( isset( $this->metaTags["http-equiv:content-style-type"] ) )
			$typeDefault	= $this->metaTags["http-equiv:content-style-type"]['content'];
		$styleData	= [
			'rel'		=> "stylesheet",
			'type'		=> $type ?: $typeDefault,
			'media'		=> $media,
			'href'		=> $uri instanceof URL ? $uri->get() : $uri,
		];
		$this->links[]	= $styleData;
		return $this;
	}

	/**
	 *	Builds Page Frame HTML.
	 *	@access		public
	 *	@param		array		$bodyAttributes
	 *	@param		array		$htmlAttributes
	 *	@return		string
	 */
	public function build( array $bodyAttributes = [], array $htmlAttributes = [] ): string
	{
		if( !is_array( $bodyAttributes ) )
			throw new InvalidArgumentException( 'Parameter "bodyAttributes" need to be an array or empty' );
		if( !is_array( $htmlAttributes ) )
			throw new InvalidArgumentException( 'Parameter "htmlAttributes" need to be an array or empty' );
		$tagsHead	= [];
		$tagsBody	= [];

		if( $this->baseHref )
			$tagsHead[]	= Tag::create( 'base', NULL, ['href' => $this->baseHref] );
		foreach( $this->metaTags as $attributes )
			$tagsHead[]	= Tag::create( 'meta', NULL, $attributes );

		if( $this->title )
			$tagsHead[]	= Tag::create( 'title', $this->title );

		if( $this->heading )
			$tagsBody[]	= Tag::create( 'h1', $this->heading );

		foreach( $this->links as $attributes )
			$tagsHead[]	= Tag::create( "link", NULL, $attributes );

		foreach( $this->scripts as $attributes )
			$tagsHead[]	= Tag::create( "script", "", $attributes );

		$headAttributes	= [
			'profile'	=> $this->profile
		];

		$tagsHead	= implode( "\n".$this->indent.$this->indent, $tagsHead );
		$tagsHead	.= implode( "\n".$this->indent.$this->indent, $this->head );
		$tagsBody	= implode( "\n".$this->indent.$this->indent, $tagsBody );
		$tagsBody	.= implode( "\n".$this->indent.$this->indent, $this->body );
		if( $tagsBody )
			$tagsBody	= "\n".$this->indent.$this->indent.$tagsBody."\n".$this->indent;
		if( $tagsHead )
			$tagsHead	= "\n".$this->indent.$this->indent.$tagsHead."\n".$this->indent;
		$head		= Tag::create( "head", $tagsHead, $headAttributes );
		$body		= Tag::create( "body", $tagsBody, $bodyAttributes );

		$docType	= $this->docTypes[$this->docType];
		$attributes	= ['lang' => $this->language];
		if( is_int( strpos( $docType, 'xhtml' ) )/* || $this->docType == 'HTML_5'*/ ){
			$attributes	= ['xml:lang' => $this->language] + $attributes;
			$attributes	= ['xmlns' => "https://www.w3.org/1999/xhtml"] + $attributes;
		}
		if( $this->prefixes ){
			$list	= [];
			foreach( $this->prefixes as $prefix => $namespace )
				$list[]	= $prefix.": ".$namespace;
			$attributes['prefix']	= join( " ", $list );
		}
		foreach( $htmlAttributes as $key => $value ){
			if( isset( $attributes[$key] ) && $key == "prefix" )
				$attributes['prefix']	.= " ".$value;
			else
				$attributes[$key]	= $value;
		}
		$content	= "\n".$this->indent.$head."\n".$this->indent.$body."\n";
		$html		= Tag::create( "html", $content, $attributes );
		return $docType."\n".$html;
	}

	/**
	 *	Returns set page body.
	 *	@access		public
	 *	@param		string		$separator		Glue between added body blocks
	 *	@return		string
	 */
	public function getBody( string $separator = "\n" ): string
	{
		return join( $separator, $this->body );
	}

	public function getLanguage(): string
	{
		return $this->language;
	}

	/**
	 *	Returns set page title.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 *	Sets base URI for all referencing resources.
	 *	@access		public
	 *	@param		URL|string		$uri			Base URI for all referencing resources
	 *	@return		self
	 */
	public function setBaseHref( $uri ): self
	{
		$this->baseHref	= $uri instanceof URL ? $uri->get() : $uri;
		return $this;
	}

	/**
	 *	Sets body of HTML page.
	 *	@access		public
	 *	@param		string|Renderable		$string			Body of HTML page
	 *	@return		self
	 */
	public function setBody( $string ): self
	{
		$this->body		= [$string instanceof Renderable ? $string->render() : $string];
		return $this;
	}

	/**
	 *	Sets canonical link.
	 *	Removes link having been set before.
	 *	@access		public
	 *	@param		URL|string		$url			URL of canonical link
	 *	@return		self
	 */
	public function setCanonicalLink( $url ): self
	{
		$url	= $url instanceof URL ? $url->get() : $url;
		foreach( $this->links as $nr => $link )
			if( $link['rel'] === 'canonical' )
				unset( $this->links[$nr] );
		$this->addLink( $url, 'canonical' );
		return $this;
	}

	/**
	 *	Sets document type of page.
	 *	@access		public
	 *	@param		string		$docType		Document type to set
	 *	@return		self
	 *	@see		https://www.w3.org/QA/2002/04/valid-dtd-list.html
	 */
	public function setDocType( string $docType ): self
	{
		$key		= str_replace( [' ', '-'], '_', trim( $docType ) );
		$key		= preg_replace( "/[^A-Z\d_]/", '', strtoupper( $key ) );
		if( !strlen( trim( $key ) ) )
			throw new InvalidArgumentException( 'No doctype given' );
		if( !array_key_exists( $key, $this->docTypes ) )
			throw new OutOfRangeException( 'Doctype "'.$docType.'" (understood as '.$key.') is invalid' );
		$this->docType	= $key;
		return $this;
	}

	/**
	 *	Sets Application Heading in Body.
	 *	@access		public
	 *	@param		string|Renderable		$heading		Application Heading
	 *	@return		self
	 */
	public function setHeading( $heading ): self
	{
		$this->heading	= $heading instanceof Renderable ? $heading->render() : $heading;
		return $this;
	}

	/**
	 *	@param		URL|string		$url
	 *	@return		self
	 */
	public function setHeadProfileUrl( $url ): self
	{
		$this->profile	= $url instanceof URL ? $url->get() : $url;
		return $this;
	}

	public function setLanguage( string $language ): self
	{
		$this->language	= $language;
		return $this;
	}

	/**
	 *	Sets Page Title, visible in Browser Title Bar.
	 *	@access		public
	 *	@param		string		$title			Page Title
	 *	@param		string		$mode			Concat mode: set, append, prepend
	 *	@param		string		$separator		Default: " | "
	 *	@return		self
	 */
	public function setTitle( string $title, string $mode = 'set', string $separator = ' | ' ): self
	{
		if( $mode == 'append' )
			$title	= $this->title.$separator.$title;
		else if( $mode == 'prepend' )
			$title	= $title.$separator.$this->title;
		$this->title	= $title;
		return $this;
	}
}
