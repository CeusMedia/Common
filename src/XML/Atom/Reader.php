<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@package		CeusMedia_Common_XML_Atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\Atom;

use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\Net\Reader as NetReader;
use Exception;
use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_XML_Atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Reader
{
	protected $parser;

	public function __construct()
	{
		$this->parser	= new Parser();
	}

	public function readXml( string $xml ): void
	{
		$this->parser->parse( $xml );
	}

	public function readUrl( string $url ): void
	{
		$xml	= NetReader::readUrl( $url );
		$this->parser->parse( $xml );
	}

	public function readFile( string $fileName ): void
	{
		$xml	= FileReader::load( $fileName );
		$this->parser->parse( $xml );
	}

	protected function checkEntryIndex( int $index )
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

	protected function getChannelElementAndAttribute( string $element, ?string $attribute = NULL )
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

	public function getChannelSubtitle( string $attribute = 'content' )
	{
		return $this->getChannelElementAndAttribute( 'subtitle', $attribute );
	}

	public function getChannelTitle( string $attribute = 'content' )
	{
		return $this->getChannelElementAndAttribute( 'title', $attribute );
	}

	public function getChannelUpdated()
	{
		return $this->parser->channelData['updated'];
	}

	public function getChannelData()
	{
		return $this->parser->channelData;
	}

	public function getEntries( ?string $language = NULL )
	{
		return $this->parser->entries;
	}

	public function getEntry( int $index )
	{
		$this->checkEntryIndex( $index );
		return $this->parser->entries[$index];
	}

	public function getEntryAuthors( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'author' );
	}

	public function getEntryCategories( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'category' );
	}

	public function getEntryContent( int $index, string $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'content', $attribute );
	}

	public function getEntryContributors( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'contributor' );
	}

	protected function getEntryElementAndAttribute( int $index, string $element, string $attribute = NULL )
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

	public function getEntryId( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'id' );
	}

	public function getEntryLinks( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'link' );
	}

	public function getEntryPublished( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'published' );
	}

	public function getEntryRights( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'rights' );
	}

	public function getEntrySource( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'source' );
	}

	public function getEntrySummary( int $index, string $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'summary', $attribute );
	}

	public function getEntryTitle( int $index, string $attribute = 'content' )
	{
		return $this->getEntryElementAndAttribute( $index, 'title', $attribute );
	}

	public function getEntryUpdated( int $index )
	{
		return $this->getEntryElementAndAttribute( $index, 'updated' );
	}

	public function getLanguage(): string
	{
		return $this->parser->language;
	}
}
