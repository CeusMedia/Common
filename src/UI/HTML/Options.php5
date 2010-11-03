<?php
/**
 *	Wrapper of jQuery plugin 'cmOptions' to create HTML and JavaScript.
 *
 *	Copyright (c) 2009-2010 Christian Würker (ceus-media.de)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Wrapper of jQuery plugin 'cmOptions' to create HTML and JavaScript.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_JQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class UI_HTML_Options
{
	protected $async	= TRUE;
	protected $cache	= TRUE;
	protected $class	= 'cmOptions';
	protected $data		= array();
	protected $name		= NULL;
	protected $options	= array();
	protected $selected	= '';
	protected $url		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name of Select Box
	 *	@return		void
	 */
	public function __construct( $name )
	{
		$this->name	= $name;
	}

	/**
	 *	Builds HTML Code of Select Box.
	 *	@access		public
	 *	@return		string
	 */
	public function buildCode()
	{
		$select		= UI_HTML_Elements::Select( $this->name, $this->options, $this->class );
		return $select;
	}
	
	/**
	 *	Builds JavaScript Code for AJAX Options.
	 *	@access		public
	 *	@return		string
	 */
	public function buildScript()
	{
		$options	= array(
			'url'		=> $this->url,
			'async'		=> $this->async,
			'cache'		=> $this->cache,
			'data'		=> $this->data,
			'selected'	=> $this->selected
		);
		return UI_HTML_JQuery::buildPluginCall( 'ajaxOptions', "select[name='".$this->name."']", $options );
	}

	/**
	 *	Set asynchronous mode (enabled by default).
	 *	@access		public
	 *	@param		bool		$bool		Flag: asynchronous mode
	 *	@return		void
	 */
	public function setAsync( $bool )
	{
		$this->async	= $bool;
	}

	/**
	 *	Sets jQuery AJAX Cache mode (enabled by default).
	 *	@access		public
	 *	@param		bool		$bool		Flag: use jQuery AJAX Cache
	 *	@return		void
	 */
	public function setCache( $bool )
	{
		$this->cache	= $bool;
	}
	
	/**
	 *	Sets Class of Select Box for CSS.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name(s)
	 *	@return		void
	 */
	public function setClass( $class )
	{
		$this->class	= $class;
	}

	/**
	 *	Sets value and label of default option.
	 *	@access		public
	 *	@param		string		$value		Value of default option
	 *	@param		string		$label		Label of default option
	 *	@return		void
	 */
	public function setDefaultItem( $value, $label )
	{
		$this->options[$value]	= $label;
	}

	/**
	 *	Sets selected Option.
	 *	@access		public
	 *	@param		string		$value		Value of selected Option
	 *	@return		void
	 */
	public function setSelectedItem( $value )
	{
		$this->selected	= $value;
	}
	
	/**
	 *	Sets URL to request JSON Options at.
	 *	@access		public
	 *	@param		string		$url		URL of Options in JSON
	 *	@return		void
	 */
	public function setUrl( $url )
	{
		$this->url	= $url;	
	}
}
?>