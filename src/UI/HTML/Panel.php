<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	User Interface Component for Panels with Header, Footer and Content.
 *	Base Implementation for further Panel Systems.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	User Interface Component for Panels with Header, Footer and Content.
 *	Base Implementation for further Panel Systems.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Panel
{
	/**	@var	string			$classAbstract		CSS Class of Abstract DIV */
	public static string $classAbstract				= "panelAbstract";

	/**	@var	string			$classAbstractInner	CSS Class of inner Abstract DIV */
	public static string $classAbstractInner		= "panelAbstractInner";

	/**	@var	string			$classContent		CSS Class of Content DIV */
	public static string $classContent				= "panelContent";

	/**	@var	string			$classContentInner	CSS Class of inner Content DIV */
	public static string $classContentInner			= "panelContentInner";

	/**	@var	string			$classFooter		CSS Class of Footer DIV */
	public static string $classFooter				= "panelFoot";

	/**	@var	string			$classFooterInner	CSS Class of inner Footer DIV */
	public static string $classFooterInner			= "panelFootInner";

	/**	@var	string			$classHeader		CSS Class of Header DIV */
	public static string $classHeader				= "panelHead";

	/**	@var	string			$classHeaderInner	CSS Class of inner Header DIV */
	public static string $classHeaderInner			= "panelHeadInner";

	/**	@var	string			$classPanel			CSS Class of Panel DIV */
	public static string $classPanel				= "panel";

	/** @var	string|NULL		$abstract			Abstract of Panel */
	protected ?string $abstract						= NULL;

	/** @var	array			$attributes			Map of Attributes of Panel DIV */
	protected array $attributes						= [];

	/** @var	string|NULL		$content			Content of Panel */
	protected ?string $content						= NULL;

	/** @var	string|NULL		$footer				Footer of Panel */
	protected ?string $footer						= NULL;

	/** @var	string|NULL		$header				Header of Panel */
	protected ?string $header						= NULL;

	/**
	 *	Builds HTML Code of Panel after settings Contents using the set methods.
	 *	@param		string		$id				Tag ID of Panel
	 *	@param		string		$theme			Theme / additional CSS Class of Panel
	 *	@return		string
	 */
	public function build( string $id, string $theme = 'default' ): string
	{
		return static::create( $id, $this->content, $this->header, $this->abstract, $this->footer, $theme, $this->attributes );
	}

	/**
	 *	Builds HTML Code of Panel statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$id					Tag ID of Panel
	 *	@param		mixed			$content			Content of Panel
	 *	@param		mixed|NULL		$header				Content of Header
	 *	@param		mixed|NULL		$abstract			Content of Abstract
	 *	@param		mixed|NULL		$footer				Content of Footer
	 *	@param		string			$theme				Theme / additional CSS Class of Panel
	 *	@param		array			$attributes			Map of Attributes of Panel DIV
	 *	@return		string
	 */
	public static function create( string $id, $content, $header = NULL, $abstract = NULL, $footer = NULL, string $theme= 'default', array $attributes = [] ): string
	{
		$divContInner	= self::wrap( (string) $content, self::$classContentInner );
		$divCont		= self::wrap( $divContInner, self::$classContent );
		$divAbstract	= '';
		$divHead		= '';
		$divFoot		= '';

		if( !is_null( $abstract ) ){
			$divAbstractInner	= self::wrap( $abstract, self::$classAbstractInner );
			$divAbstract		= self::wrap( $divAbstractInner, self::$classAbstract );
		}
		if( !is_null( $footer ) ){
			$divFootInner	= self::wrap( $footer, self::$classFooterInner );
			$divFoot		= self::wrap( $divFootInner, self::$classFooter );
		}
		if( !is_null( $header ) ){
			$divHeadInner	= self::wrap( $header, self::$classHeaderInner );
			$divHead		= self::wrap( $divHeadInner, self::$classHeader );
		}

		$classes		= $theme ? self::$classPanel." ".$theme : self::$classPanel;
		$attributes		= array_merge( ['id' => $id], $attributes );
		return self::wrap( $divHead.$divAbstract.$divCont.$divFoot, $classes, $attributes );
	}

	/**
	 *	Sets Abstract of Panel.
	 *	@access		public
	 *	@param		mixed|NULL		$abstract			Abstract of Panel
	 *	@return		self
	 */
	public function setAbstract( $abstract ): self
	{
		$this->abstract	= $abstract;
		return $this;
	}

	/**
	 *	Set an Attributes of Panel DIV.
	 *	@access		public
	 *	@param		string		$key				Key of Attribute
	 *	@param		string		$value				Value of Attribute
	 *	@return		self
	 */
	public function setAttribute( string $key, string $value ): self
	{
		$this->attributes[$key]	= $value;
		return $this;
	}

	/**
	 *	Sets a Map of Attributes of Panel DIV.
	 *	@access		public
	 *	@param		array		$attributes			Map of Attribute
	 *	@return		self
	 */
	public function setAttributes( array $attributes ): self
	{
		foreach( $attributes as $key => $value )
			$this->attributes[$key]	= $value;
		return $this;
	}

	/**
	 *	Sets Content of Panel.
	 *	@access		public
	 *	@param		mixed|NULL		$content			Content of Panel
	 *	@return		self
	 */
	public function setContent( $content ): self
	{
		$this->content	= $content;
		return $this;
	}

	/**
	 *	Sets Footer Content of Panel.
	 *	@access		public
	 *	@param		mixed|NULL		$footer			Footer Content of Panel
	 *	@return		self
	 */
	public function setFooter( $footer ): self
	{
		$this->footer	= $footer;
		return $this;
	}

	/**
	 *	Sets Header Content of Panel.
	 *	@access		public
	 *	@param		mixed|NULL		$header			Header Content of Panel
	 *	@return		self
	 */
	public function setHeader( $header ): self
	{
		$this->header	= $header;
		return $this;
	}

	/**
	 *	Wraps Content in DIV.
	 *	@access		protected
	 *	@static
	 *	@param		mixed|NULL		$content			...
	 *	@param		string			$class				CSS Class of DIV
	 *	@param		array			$attributes			Array of Attributes
	 *	@return		string
	 */
	protected static function wrap( $content, string $class, array $attributes = [] ): string
	{
		$attributes	= array_merge( $attributes, ['class' => $class] );
		return Tag::create( 'div', $content, $attributes );
	}
}
