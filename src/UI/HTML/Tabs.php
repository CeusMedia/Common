<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Tabbed Content Builder - builds Tab List and Content Divs and applies JavaScript 'tabs.js'.
 *	The Script is a jQuery Plugin and must be loaded within the surrounding HTML.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\UI\HTML\JQuery as JQuery;
use Exception;

/**
 *	Tabbed Content Builder - builds Tab List and::$version Content Divs and applies JavaScript 'tabs.js'.
 *	The Script is a jQuery Plugin and must be loaded within the surrounding HTML.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Tabs
{
	public static int $version	= 2;

	/**	@var		int				$counter	Internal Tab Counter for creating unique IDs for Tabs and Contents */
	protected static int $counter	= 0;

	/**	@var		array			$pairs		List of Content Divs */
	protected array $divs			= [];

	/**	@var		array			$options	Array of Options for the jQuery Plugin Call */
	protected array $options		= [
		'navClass'	=> "tabs-nav"
	];

	/**	@var		array			$tabs		List of Tab Labels */
	protected array $tabs			= [];


	/**
	 *	Constructor, can set Tabs.
	 *	@access		public
	 *	@param		array		$tabs		Array of Labels and Contents
	 *	@return		void
	 */
	public function __construct( array $tabs = [], ?string $class = NULL )
	{
		if( $tabs )
			$this->addTabs( $tabs );
		if( $class )
			$this->setOption( 'navClass', $class );
	}

	/**
	 *	Adds a new Tab by its Label and Content.
	 *	@access		public
	 *	@param		string			$label			Label of Tab
	 *	@param		string			$content		Content related to Tab
	 *	@param		string|NULL		$fragmentId	...
	 *	@return		self
	 *	@throws		Exception		if fragment ID is set, already
	 */
	public function addTab( string $label, string $content, ?string $fragmentId = NULL ): self
	{
		if( is_null( $fragmentId ) ){
			$this->tabs[]	= $label;
			$this->divs[]	= $content;
		}
		else{
			if( isset( $this->tabs[$fragmentId] ) )
				throw new Exception( 'Tab with Fragment ID "'.$fragmentId.'" is already set' );
			$this->tabs[$fragmentId]	= $label;
			$this->divs[$fragmentId]	= $content;
		}
		return $this;
	}

	/**
	 *	Constructor, can set Tabs.
	 *	@access		public
	 *	@param		array		$tabs		Array of Labels and Contents
	 *	@return		self
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public function addTabs( array $tabs = [] ): self
	{
		foreach( $tabs as $label => $content )
			/** @noinspection PhpUnhandledExceptionInspection */
			$this->addTab( $label, $content );
		return $this;
	}

	/**
	 *	Creates JavaScript Call, applying before set Options and given Options.
	 *	@access		public
	 *	@param		string		$selector		jQuery Selector of Tabs DIV (mostly '#' + ID)
	 *	@param		array		$options		Tabs Options Array, additional to before set Options
	 *	@return 	string
	 *	@link		http://stilbuero.de/jquery/tabs/
	 */
	public function buildScript( string $selector, array $options = [] ): string
	{
		$options	= array_merge( $this->options, $options );
		return self::createScript( $selector, $options );
	}

	/**
	 *	Builds HTML Code of Tabbed Content.
	 *	@access		public
	 *	@param		string			$id			ID of whole Tabbed Content Block
	 *	@param		string|NULL		$class		CSS Class of Tabs DIV (main container)
	 *	@return		string
	 *	@throws		Exception		if number of labels and contents does not match
	 */
	public function buildTabs( string $id, ?string $class = NULL ): string
	{
		if( empty( $class ) && !empty( $this->options['navClass'] ) )
			$class	= $this->options['navClass'];
		return self::createTabs( $id, $this->tabs, $this->divs, $class );
	}

	/**
	 *	Creates JavaScript Call statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$selector		jQuery Selector of Tabs DIV (mostly '#' + ID)
	 *	@param		array		$options		Tabs Options Array
	 *	@return 	string
	 *	@link		http://stilbuero.de/jquery/tabs/
	 */
	public static function createScript( string $selector, array $options = [] ): string
	{
		return JQuery::buildPluginCall( "tabs", $selector, $options );
	}

	/**
	 *	Builds HTML Code of Tabbed Content statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$id			ID of whole Tabbed Content Block
	 *	@param		array			$labels		List of Tab Labels
	 *	@param		array			$contents	List of Contents related to the Tabs
	 *	@param		string|NULL		$class		CSS Class of Tabs DIV (main container)
	 *	@return		string
	 *	@throws		Exception		if number of labels and contents does not match
	 */
	public static function createTabs( string $id, array $labels = [], array $contents = [], ?string $class = NULL ): string
	{
		if( count( $labels ) != count( $contents ) )
			throw new Exception( 'Number of labels and contents is not equal.' );

		$belowV3	= version_compare( "3", (string) self::$version );
		$urlPrefix	= ( $belowV3 && getEnv( 'REDIRECT_URL' ) ) ? getEnv( 'REDIRECT_URL' ) : '';
		$tabs		= [];
		$divs		= [];
		foreach( $labels as $index => $label ) {
			$tabKey		= is_int( $index ) ? 'tab-'.$index : $index;
			$divKey		= $index."-container";
			$url		= $urlPrefix."#".$divKey;
			$label		= Tag::create( 'span', $label );
			$link		= Tag::create( 'a', $label, ['href' => $url] );
			$tabs[]		= Tag::create( 'li', $link, ['id' => $tabKey] );

			$divClass	= $class ? $class."-container" : NULL;
			$attributes	= ['id' => $divKey, 'class' => $divClass];
			$divs[]		= Tag::create( 'div', $contents[$index], $attributes );
			self::$counter++;
		}
		$tabs		= Tag::create( 'ul', implode( "\n", $tabs ), ['class' => $class] );
		$divs		= implode( "\n", $divs );
		return Tag::create( 'div', $tabs.$divs, ['id' => $id] );
	}

	/**
	 *	Sets an Option for the jQuery Tabs Plugin Call.
	 *	Attention: To set a String it must be quoted, iE setOption( 'stringKey', '"stringValue"' ).
	 *	Numbers (Integer, Double, Float) and Booleans can be set directly.
	 *	It is also possible to set Array and Objects by using json_encode, iE setOption( 'key', json_encode( [1, 2] ) ).
	 *	JavaScript Callback Functions will be given as a simple String (without mentioned quotes).
	 *
	 *	@access		public
	 *	@param		string		$key
	 *	@param		mixed		$value			Option Value (Strings must be quoted)
	 *	@return		self
	 *	@link		http://stilbuero.de/jquery/tabs/
	 */
	public function setOption( string $key, $value ): self
	{
		if( is_null( $value ) )
			unset( $this->options[$key] );
		else
			$this->options[$key]	= $value;
		return $this;
	}

	public function setVersion( int $version ): self
	{
		self::$version	= $version;
		return $this;
	}
}
