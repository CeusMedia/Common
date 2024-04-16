<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@package		CeusMedia_Common_XML_Atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\Atom;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\Net\Reader as NetReader;
use Exception;
use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_XML_Atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Reader
{
	protected Parser $parser;

	public function __construct()
	{
		$this->parser	= new Parser();
	}

	/**
	 *	@param		string		$xml
	 *	@return		void
	 *	@throws		Exception
	 */
	public function readXml( string $xml ): void
	{
		$this->parser->parse( $xml );
	}

	/**
	 *	@param		string		$url
	 *	@return		void
	 *	@throws		IoException
	 *	@throws		Exception
	 */
	public function readUrl( string $url ): void
	{
		$xml	= NetReader::readUrl( $url );
		$this->parser->parse( $xml );
	}

	/**
	 *	@param		string		$fileName
	 *	@return		void
	 *	@throws		Exception
	 */
	public function readFile( string $fileName ): void
	{
		$xml	= FileReader::load( $fileName );
		$this->parser->parse( $xml );
	}

	protected function checkEntryIndex( int $index ): void
	{
		if( !isset( $this->parser->entries[$index] ) )
			throw new InvalidArgumentException( 'Entry with Index "'.$index.'" is not existing.' );
	}

	public function getChannelAuthors(): array
	{
		return $this->parser->channelData['author'];
	}

	public function getChannelCategories(): array
	{
		return $this->parser->channelData['category'];
	}

	public function getChannelContributors(): array
	{
		return $this->parser->channelData['contributor'];
	}

	/**
	 *	@param		string			$element
	 *	@param		string|NULL		$attribute
	 *	@return		mixed
	 *	@throws		Exception
	 */
	protected function getChannelElementAndAttribute( string $element, ?string $attribute = NULL ): mixed
	{
		if( !$attribute )
			return $this->parser->channelData[$element];
		if( !array_key_exists( $attribute, $this->parser->channelData[$element] ) )
			throw new Exception( 'Attribute "'.$attribute.'" is not set in Channel Element "'.$element.'".' );
		return $this->parser->channelData[$element][$attribute];
	}

	public function getChannelGenerator(): array
	{
		return $this->parser->channelData['generator'];
	}

	public function getChannelIcon(): string
	{
		return $this->parser->channelData['icon'];
	}

	public function getChannelId(): string
	{
		return $this->parser->channelData['id'];
	}

	public function getChannelLinks(): array
	{
		return $this->parser->channelData['link'];
	}

	public function getChannelLogo(): string
	{
		return $this->parser->channelData['logo'];
	}

	public function getChannelRights(): string
	{
		return $this->parser->channelData['rights'];
	}

	/**
	 *	@param		string		$attribute
	 *	@return		string|NULL
	 *	@throws		Exception
	 */
	public function getChannelSubtitle( string $attribute = 'content' ): ?string
	{
		return $this->getChannelElementAndAttribute( 'subtitle', $attribute );
	}

	/**
	 *	@param		string		$attribute
	 *	@return		string|NULL
	 *	@throws		Exception
	 */
	public function getChannelTitle( string $attribute = 'content' ): ?string
	{
		return $this->getChannelElementAndAttribute( 'title', $attribute );
	}

	/**
	 *	@return		mixed
	 */
	public function getChannelUpdated(): mixed
	{
		return $this->parser->channelData['updated'];
	}

	/**
	 *	@return		array
	 */
	public function getChannelData(): array
	{
		return $this->parser->channelData;
	}

	/**
	 *	@param		string|NULL		$language
	 *	@return		array
	 */
	public function getEntries( ?string $language = NULL ): array
	{
		return $this->parser->entries;
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 */
	public function getEntry( int $index ): mixed
	{
		$this->checkEntryIndex( $index );
		return $this->parser->entries[$index];
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryAuthors( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'author' );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryCategories( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'category' );
	}

	/**
	 *	@param		int			$index
	 *	@param		string		$attribute
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryContent( int $index, string $attribute = 'content' ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'content', $attribute );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryContributors( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'contributor' );
	}

	/**
	 *	@param		int			$index
	 *	@param		string		$element
	 *	@param		string|NULL	$attribute
	 *	@return		mixed
	 *	@throws		Exception
	 */
	protected function getEntryElementAndAttribute( int $index, string $element, string $attribute = NULL ): mixed
	{
		$this->checkEntryIndex( $index );
		if( !$attribute )
			return $this->parser->entries[$index][$element];
		if( !array_key_exists( $attribute, $this->parser->entries[$index][$element] ) ){
#			print_m( $this->parser->entries[$index][$element] );
			throw new Exception( 'Attribute "'.$attribute.'" is not set in Entry Element "'.$element.'".' );
		}
		return $this->parser->entries[$index][$element][$attribute];
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryId( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'id' );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryLinks( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'link' );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryPublished( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'published' );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryRights( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'rights' );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntrySource( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'source' );
	}

	/**
	 *	@param		int			$index
	 *	@param		string		$attribute
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntrySummary( int $index, string $attribute = 'content' ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'summary', $attribute );
	}

	/**
	 *	@param		int			$index
	 *	@param		string		$attribute
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryTitle( int $index, string $attribute = 'content' ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'title', $attribute );
	}

	/**
	 *	@param		int			$index
	 *	@return		mixed
	 *	@throws		Exception
	 */
	public function getEntryUpdated( int $index ): mixed
	{
		return $this->getEntryElementAndAttribute( $index, 'updated' );
	}

	/**
	 *	@return		string
	 */
	public function getLanguage(): string
	{
		return $this->parser->language;
	}
}
