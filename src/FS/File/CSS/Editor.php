<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Editor for CSS files or given sheet structures.
 *
 *	Copyright (c) 2011-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use Exception;
use OutOfRangeException;
use RuntimeException;

use CeusMedia\Common\ADT\CSS\Rule as CssRule;
use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use CeusMedia\Common\ADT\CSS\Property as CssProperty;

/**
 *	Editor for CSS files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Editor
{
	/** @var		CssSheet|NULL		$sheet */
	protected ?CssSheet $sheet			= NULL;

	protected ?string $fileName			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param 		string|NULL		$fileName
	 *	@throws		Exception
	 */
	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->setFileName( $fileName );
	}

	public function addRuleBySelector( string $selector, array $properties = [] ): self
	{
		$this->checkIsLoaded();
		$rule	= new CssRule( $selector, $properties );
		$this->sheet->addRule( $rule );
		$this->save();
		return $this;
	}

	public function changePropertyKey( string $selector, string $keyOld, string $keyNew ): self
	{
		$this->checkIsLoaded();
		$rule	= $this->sheet->getRuleBySelector( $selector );
		if( !$rule->hasPropertyByKey( $keyOld ) )
			throw new OutOfRangeException( 'Property with key "'.$keyOld.'" is not existing' );
		$property	= $rule->getPropertyByKey( $keyOld );
		$property->setKey( $keyNew );
		$this->save();
		return $this;
	}

	public function changeRuleSelector( string $selectorOld, string $selectorNew ): self
	{
		$this->checkIsLoaded();
		$rule	= $this->sheet->getRuleBySelector( $selectorOld );
		if( !$rule )
			throw new OutOfRangeException( 'Rule with selector "'.$selectorOld.'" is not existing' );
		$rule->setSelector( $selectorNew );
		$this->save();
		return $this;
	}

	protected function checkIsLoaded(): void
	{
		if( NULL === $this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
	}

	/**
	 *
	 *	@access		public
	 *	@param		string			$selector		Rule selector
	 *	@param		string			$key			Property key
	 *	@return		CssProperty|NULL
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function get( string $selector, string $key ): ?CssProperty
	{
		$this->checkIsLoaded();
		return $this->sheet->get( $selector, $key );
	}

	/**
	 *	Returns a list of CSS property objects by a rule selector.
	 *	@access		public
	 *	@param		string		$selector		Rule selector
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function getProperties( string $selector ): array
	{
		$this->checkIsLoaded();
		$rule	= $this->sheet->getRuleBySelector( $selector );
		if( !$rule )
			return [];
		return $rule->getProperties();
	}

	/**
	 *	Returns list of found rule selectors.
	 *	@access		public
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function getSelectors(): array
	{
		$this->checkIsLoaded();
		return $this->sheet->getSelectors();
	}

	/**
	 *
	 */
	public function getSheet(): CssSheet
	{
		$this->checkIsLoaded();
		return $this->sheet;
	}

	/**
	 *	Removes a rule property by rule selector and property key.
	 *	@access		public
	 *	@param		string			$selector		Rule selector
	 *	@param		string|NULL		$key			Property key
	 *	@return		self
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function remove( string $selector, ?string $key = NULL ): self
	{
		$this->checkIsLoaded();
		$this->sheet->remove( $selector, $key ) && $this->save();
		return $this;
	}

	/**
	 *	Writes current sheet to CSS file and returns number of written bytes.
	 *	@access		protected
	 *	@return		integer		Number of written bytes
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	protected function save(): int
	{
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return Writer::save( $this->fileName, $this->sheet );
	}

	public function set( string $selector, string $key, ?string $value ): self
	{
		$this->checkIsLoaded();
		$this->sheet->set( $selector, $key, $value ) && $this->save();
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$fileName
	 *	@return		self
	 *	@throws		Exception
	 */
	public function setFileName( string $fileName ): self
	{
		$this->fileName	= $fileName;
		$this->sheet	= Parser::parseFile( $fileName );
		return $this;
	}

	public function setSheet( CssSheet $sheet ): self
	{
		$this->sheet	= $sheet;
		return $this;
	}
}
