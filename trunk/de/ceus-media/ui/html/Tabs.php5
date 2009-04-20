<?php
/**
 *	Tabbed Content Builder - builds Tab List and Content Divs and applies JavaScript 'tabs.js'.
 *	The Script is a jQuery Plugin and must be loaded within the surrounding HTML.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.11.2008
 *	@version		0.1
 */
import( 'de.ceus-media.ui.html.Tag' );
import( 'de.ceus-media.ui.html.JQuery' );
/**
 *	Tabbed Content Builder - builds Tab List and Content Divs and applies JavaScript 'tabs.js'.
 *	The Script is a jQuery Plugin and must be loaded within the surrounding HTML.
 *	@package		ui.html
 *	@uses			UI_HTML_Tag
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.11.2008
 *	@version		0.1
 */
class UI_HTML_Tabs
{
	/**	@var		int			$counter	Internal Tab Counter for creating unique IDs for Tabs and Contents */
	protected static $counter	= 0;
	/**	@var		array		$pairs		List of Content Divs */
	protected $divs	= array();
	/**	@var		array		$options	Array of Options for the jQuery Plugin Call */
	protected $options	= array(
		'navClass'	=> "tabs-nav"
	);
	/**	@var		array		$tabs		List of Tab Labels */
	protected $tabs	= array();

	/**
	 *	Constructor, can set Tabs.
	 *	@access		public
	 *	@param		array		$tabs		Array of Labels and Contents
	 *	@return		void
	 */
	public function __construct( $tabs = array(), $class = NULL )
	{
		if( $tabs )
			$this->addTabs( $tabs );
		if( $class )
			$this->setOption( 'navClass', $class );
	}

	/**
	 *	Constructor, can set Tabs.
	 *	@access		public
	 *	@param		array		$tabs		Array of Labels and Contents
	 *	@return		void
	 */
	public function addTabs( $tabs = array() )
	{
		if( !is_array( $tabs ) )
			throw new InvalidArgumentException( 'Tabs must be given as array of labels and contents.' );
		foreach( $tabs as $label => $content )
			$this->addTab( $label, $content );
	}

	/**
	 *	Adds a new Tab by its Label and Content.
	 *	@access		public
	 *	@param		string		$label		Label of Tab
	 *	@param		string		$content	Content related to Tab
	 *	@return		void
	 */
	public function addTab( $label, $content )
	{
		$this->tabs[]	= $label;
		$this->divs[]	= $content;
	}

	/**
	 *	Builds HTML Code of Tabbed Content.
	 *	@access		public
	 *	@param		string		$id			ID of whole Tabbed Content Block
	 *	@param		string		$class		CSS Class of Tabs DIV (main container)
	 *	@return		string
	 */
	public function buildTabs( $id, $class = NULL )
	{
		return self::createTabs( $id, $this->tabs, $this->divs, $this->options['navClass'] );
	}

	/**
	 *	Creates JavaScript Call, applying afore set Options and given Options.
	 *	@access		public
	 *	@param		string		$selector		jQuery Selector of Tabs DIV (mostly '#' + ID)
	 *	@param		array		$options		Tabs Options Array, additional to afore set Options
	 *	@return 	string
	 *	@link		http://stilbuero.de/jquery/tabs/
	 */
	public function buildScript( $selector, $options = array() )
	{
		$options	= array_merge( $this->options, $options );
		return self::createScript( $selector, $options );
	}
	
	/**
	 *	Builds HTML Code of Tabbed Content statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$id			ID of whole Tabbed Content Block
	 *	@param		array		$label		List of Tab Labels
	 *	@param		array		$contents	List of Contents related to the Tabs
	 *	@param		string		$class		CSS Class of Tabs DIV (main container)
	 *	@return		string
	 */
	public static function createTabs( $id, $labels = array(), $contents = array(), $class = NULL )
	{
		if( count( $labels ) != count( $contents ) )
			throw new Exception( 'Number of labels and contents is not equal.' );

		$tabs		= array();
		$divs		= array();
		$labels		= array_values( $labels );
		$contents	= array_values( $contents );
		foreach( $labels as $index => $label )
		{
			$tabKey		= 'tab-'.self::$counter;
			$divKey		= $tabKey."-container";
			$label		= UI_HTML_Tag::create( 'span', $label );
			$link		= UI_HTML_Tag::create( 'a', $label, array( 'href' => "#".$divKey ) );
			$tabs[]		= UI_HTML_Tag::create( 'li', $link, array( 'id' => $tabKey ) );

			$divClass	= $class ? $class."-container" : NULL;
			$attributes	= array( 'id' => $divKey, 'class' => $divClass );
			$divs[]		= UI_HTML_Tag::create( 'div', $contents[$index], $attributes );
			self::$counter++;
		}
		$tabs		= UI_HTML_Tag::create( 'ul', implode( "\n", $tabs ) );
		$divs		= implode( "\n", $divs );
		$content	= UI_HTML_Tag::create( 'div', $tabs.$divs, array( 'id' => $id ) );
		return $content;
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
	public static function createScript( $selector, $options = array() )
	{
		return UI_HTML_JQuery::buildPluginCall( "tabs", $selector, $options );	
	}

	/**
	 *	Sets an Option for the jQuery Tabs Plugin Call.
	 *	Attention: To set a String it must be quoted, iE setOption( 'stringKey', '"stringValue"' ).
	 *	Numbers (Integer, Double, Float) and Booleans can be set directly.
	 *	It is also possible to set Array and Objects by using json_encode, iE setOption( 'key', json_encode( array( 1, 2 ) ) ).
	 *	JavaScript Callback Functions will be given as a simple String (without mentioned quotes).
	 *
	 *	@access		public
	 *	@param		string		$key
	 *	@param		mixed		$value			Option Value (Strings must be quoted)
	 *	@return		string
	 *	@link		http://stilbuero.de/jquery/tabs/
	 */
	public function setOption( $key, $value )
	{
		$this->options[$key]	= $value;
	}
}
?>