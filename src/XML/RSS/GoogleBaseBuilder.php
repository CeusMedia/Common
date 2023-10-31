<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds RSS for Google Base - Froogle.
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
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\RSS;

use Exception;

/**
 *	Builds RSS for Google Base - Froogle.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class GoogleBaseBuilder extends Builder
{
	protected $itemElements	= [
		'title'						=> FALSE,
		'description'				=> FALSE,
		'link'						=> TRUE,
		'g:id'						=> TRUE,
/*		'g:preis'					=> TRUE,
		'g:autor'					=> TRUE,
		'g:isbn'					=> FALSE,
		'g:bild_url'				=> FALSE,
		'g:name_publikation'		=> FALSE,
		'g:produktart'				=> FALSE,
		'g:sprache'					=> FALSE,
		'g:standort'				=> FALSE,
		'g:währung'					=> FALSE,
		'g:zustand'					=> FALSE,
		'g:herstellungsjahr'		=> FALSE,
		'g:veröffentlichungs_datum'	=> FALSE,
		'g:veröffentlichung_band'	=> FALSE,
		'g:name_der_veröffentlichung'	=> TRUE,*/
	];
	/**	@var		string		$namespaceUri		URI of Google Base Namespace */
	public static $namespaceUri	= "http://base.google.com/ns/1.0";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct()
	{
		parent::__construct();
		$this->registerNamespace( 'g', self::$namespaceUri );
	}

	public function addItemElement( string $name, bool $mandatory = FALSE ): self
	{
		$this->itemElements[$name]	= $mandatory;
		return $this;
	}
}
