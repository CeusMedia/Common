<?php
/**
 *	User Interface Component for Panels with Header, Footer and Content.
 *	Base Implementation for further Panel Systems.
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
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			21.11.2008
 *	@version		0.7
 */
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	User Interface Component for Panels with Header, Footer and Content.
 *	Base Implementation for further Panel Systems.
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			21.11.2008
 *	@version		0.7
 */
class UI_HTML_Panel
{
	/**	@var		string		$classContent		CSS Class of Content DIV */
	public static $classContent						= "panelContent";
	/**	@var		string		$classContentInner	CSS Class of inner Content DIV */
	public static $classContentInner				= "panelContentInner";
	/**	@var		string		$classFooter		CSS Class of Footer DIV */
	public static $classFooter						= "panelFoot";
	/**	@var		string		$classFooterInner	CSS Class of inner Footer DIV */
	public static $classFooterInner					= "panelFootInner";
	/**	@var		string		$classHeader		CSS Class of Header DIV */
	public static $classHeader						= "panelHead";
	/**	@var		string		$classHeaderInner	CSS Class of inner Header DIV */
	public static $classHeaderInner					= "panelHeadInner";
	/**	@var		string		$classPanel			CSS Class of Panel DIV */
	public static $classPanel						= "panel";

	/** @var		array		$attributes			Map of Attributes of Panel DIV */
	protected $attributes		= array();
	/** @var		string		$content			Content of Panel */
	protected $content			= NULL;
	/** @var		string		$footer				Footer of Panel */
	protected $footer			= NULL;
	/** @var		string		$header				Header of Panel */
	protected $header			= NULL;

	/**
	 *	Builds HTML Code of Panel after settings Contents using the set methods.
	 *	@param		string		$id				Tag ID of Panel
	 *	@param		string		$theme			Theme / additional CSS Class of Panel
	 *	@return		string
	 */
	public function build( $id, $theme = "default" )
	{
		return $this->create( $id, $this->header, $this->content, $this->footer, $theme, $this->attributes );
	}

	/**
	 *	Builds HTML Code of Panel statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$id				Tag ID of Panel
	 *	@param		string		$header			Content of Header
	 *	@param		string		$content		Content of Panel
	 *	@param		string		$footer			Content of Footer
	 *	@param		string		$theme			Theme / additional CSS Class of Panel
	 *	@param		array		$attributes		Map of Attributes of Panel DIV
	 *	@return		string
	 */
	public static function create( $id, $header, $content, $footer = NULL, $theme= "default", $attributes = array() )
	{
		$divContInner	= self::wrap( (string) $content, self::$classContentInner );
		$divCont		= self::wrap( $divContInner, self::$classContent );
		$divHead		= "";
		$divFoot		= "";
		
		if( !is_null( $footer ) )
		{
			$divFootInner	= self::wrap( $footer, self::$classFooterInner );
			$divFoot		= self::wrap( $divFootInner, self::$classFooter );
		}
		if( !is_null( $header ) )
		{
			$divHeadInner	= self::wrap( $header, self::$classHeaderInner );
			$divHead		= self::wrap( $divHeadInner, self::$classHeader );
		}

		$classes		= $theme ? self::$classPanel." ".$theme : self::$classPanel;
		$attributes		= array_merge( array( "id" => $id ), $attributes );
		$divPanel		= self::wrap( $divHead.$divCont.$divFoot, $classes, $attributes );
		return $divPanel;
	}

	/**
	 *	Set an Attributes of Panel DIV.
	 *	@access		public
	 *	@param		string		$key			Key of Attribute
	 *	@param		string		$value			Value of Attribute
	 *	@return		void
	 */
	public function setAttribute( $key, $value )
	{
		$this->attributes[$key]	= $value;
	}
	
	/**
	 *	Sets a Map of Attributes of Panel DIV.
	 *	@access		public
	 *	@param		array		$attributes		Map of Attribute
	 *	@return		void
	 */
	public function setAttributes( $attributes )
	{
		foreach( $attributes as $key => $value )
			$this->attributes[$key]	= $value;
	}

	/**
	 *	Sets Content of Panel.
	 *	@access		public
	 *	@param		string		$content		Content of Panel
	 *	@return		void
	 */
	public function setContent( $content )
	{
		$this->content	= $content;
	}

	/**
	 *	Sets Footer Content of Panel.
	 *	@access		public
	 *	@param		string		$content		Footer Content of Panel
	 *	@return		void
	 */
	public function setFooter( $footer )
	{
		$this->footer	= $footer;
	}

	/**
	 *	Sets Header Content of Panel.
	 *	@access		public
	 *	@param		string		$content		Header Content of Panel
	 *	@return		void
	 */
	public function setHeader( $header )
	{
		$this->header	= $header;
	}

	/**
	 *	Wraps Content in DIV.
	 *	@access		protected
	 *	@static
	 *	@param		string		$content		...
	 *	@param		string		$class			CSS Class of DIV
	 *	@param		array		$attributes		Array of Attributes
	 *	@return		string
	 */
	protected static function wrap( $content, $class, $attributes = array() )
	{
		$attributes	= array_merge( $attributes, array( 'class' => $class ) );
		return UI_HTML_Tag::create( "div", $content, $attributes );
	}
}
?>