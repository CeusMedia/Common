<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Wrapper of jQuery plugin 'cmOptions' to create HTML and JavaScript.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

/**
 *	Wrapper of jQuery plugin 'cmOptions' to create HTML and JavaScript.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Options
{
	protected bool $async		= TRUE;
	protected bool $cache		= TRUE;
	protected string $class		= 'cmOptions';
	protected array $data		= [];
	protected ?string $name		= NULL;
	protected array $options	= [];
	protected string $selected	= '';
	protected ?string $url		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name of Select Box
	 *	@return		void
	 */
	public function __construct( string $name )
	{
		$this->name	= $name;
	}

	/**
	 *	Builds HTML Code of Select Box.
	 *	@access		public
	 *	@return		string
	 */
	public function buildCode(): string
	{
		return Elements::Select( $this->name, $this->options, $this->class );
	}

	/**
	 *	Builds JavaScript Code for AJAX Options.
	 *	@access		public
	 *	@return		string
	 */
	public function buildScript(): string
	{
		$options	= array(
			'url'		=> $this->url,
			'async'		=> $this->async,
			'cache'		=> $this->cache,
			'data'		=> $this->data,
			'selected'	=> $this->selected
		);
		return JQuery::buildPluginCall( 'ajaxOptions', "select[name='".$this->name."']", $options );
	}

	/**
	 *	Set asynchronous mode (enabled by default).
	 *	@access		public
	 *	@param		bool		$bool		Flag: asynchronous mode
	 *	@return		self
	 */
	public function setAsync( bool $bool ): self
	{
		$this->async	= $bool;
		return $this;
	}

	/**
	 *	Sets jQuery AJAX Cache mode (enabled by default).
	 *	@access		public
	 *	@param		bool		$bool		Flag: use jQuery AJAX Cache
	 *	@return		self
	 */
	public function setCache( bool $bool ): self
	{
		$this->cache	= $bool;
		return $this;
	}

	/**
	 *	Sets Class of Select Box for CSS.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name(s)
	 *	@return		self
	 */
	public function setClass( string $class ): self
	{
		$this->class	= $class;
		return $this;
	}

	/**
	 *	Sets value and label of default option.
	 *	@access		public
	 *	@param		string		$value		Value of default option
	 *	@param		string		$label		Label of default option
	 *	@return		self
	 */
	public function setDefaultItem( string $value, string $label ): self
	{
		$this->options[$value]	= $label;
		return $this;
	}

	/**
	 *	Sets selected Option.
	 *	@access		public
	 *	@param		string		$value		Value of selected Option
	 *	@return		self
	 */
	public function setSelectedItem( string $value ): self
	{
		$this->selected	= $value;
		return $this;
	}

	/**
	 *	Sets URL to request JSON Options at.
	 *	@access		public
	 *	@param		string		$url		URL of Options in JSON
	 *	@return		self
	 */
	public function setUrl( string $url ): self
	{
		$this->url	= $url;
		return $this;
	}
}
