<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Validates an XML Element built form an Atom XML String against most of the ATOM Rules.
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
 *	@see			http://www.atomenabled.org/developers/syndication/atom-format-spec.php#element.entry
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\Atom;

use CeusMedia\Common\XML\Element as XmlElement;

/**
 *	Validates an XML Element built form an Atom XML String against most of the ATOM Rules.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_Atom
 *	@see			http://www.atomenabled.org/developers/syndication/atom-format-spec.php#element.entry
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Validator
{
	/**	@var		array			$errors			List of broken Atom Rules */
	protected array $errors	= [];

	/**	@var		array			$rules			Error Messages of Atom Rules */
	protected array $rules	= [
		'feed_author'		=> "Feed element MUST contain one or more author elements, unless all of the feed element's child entry elements contain at least one author element.",
		'feed_generator'	=> "Feed element MUST NOT contain more than one generator element.",
		'feed_icon'			=> "Feed element MUST NOT contain more than one icon element.",
		'feed_logo'			=> "Feed element MUST NOT contain more than one logo element.",
		'feed_id'			=> "Feed element MUST contain exactly one id element.",
		'feed_rights'		=> "Feed element MUST NOT contain more than one rights element.",
		'feed_subtitle'		=> "Feed element MUST NOT contain more than one subtitle element.",
		'feed_title'		=> "Feed element MUST contain exactly one title element.",
		'feed_updated'		=> "Feed element MUST contain exactly one updated element.",
		'feed_link_unique'	=> "Feed element MUST NOT contain more than one link element with a rel attribute value of \"alternate\" that has the same combination of type and hreflang attribute values.",

		'entry_author'		=> "Entry Element MUST contain one or more author elements, unless the entry contains an source element that contains an author element or, in an Atom Feed Document, the feed element contains an author element itself.",
		'entry_content'		=> "Entry Element MUST NOT contain more than one content element.",
		'entry_id'			=> "Entry Element MUST contain exactly one id element.",
		'entry_link_alt'	=> "Entry Element that contain no child content element MUST contain at least one link element with a rel attribute value of \"alternate\".",
		'entry_published'	=> "Entry Element MUST NOT contain more than one published element.",
		'entry_rights'		=> "Entry Element MUST NOT contain more than one rights element.",
		'entry_source'		=> "Entry Element MUST NOT contain more than one source element.",
		'entry_summary'		=> "Entry Element MUST NOT contain more than one summary element.",
		'entry_title'		=> "Entry Element MUST contain exactly one title element.",
		'entry_updates'		=> "Entry Element MUST contain exactly one updated element.",
		'entry_link_unique'	=> "Entry Element MUST NOT contain more than one link element with a rel attribute value of \"alternate\" that has the same combination of type and hreflang attribute values.",
	];

	/**
	 *	Returns Error Messages of all Atom Rules hurt by Validation.
	 *	Call Method 'validate'.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors(): array
	{
		$list	= [];
		foreach( $this->errors as $errorKey )
			$list[$errorKey]	= $this->rules[$errorKey];
		return $list;
	}

	/**
	 *	Returns first Error Message from Validation.
	 *	@access		public
	 *	@return		string
	 */
	public function getFirstError(): string
	{
		if( !$this->errors )
			return '';
		$slice	= array_slice( $this->errors, 0, 1 );
		$error	= array_pop( $slice );
		return $this->rules[$error];
	}

	/**
	 *	Indicates whether an XML Element built form an Atom XML String is a valid Atom Feed.
	 *	@access		protected
	 *	@param		XmlElement		$xmlElement		Root Element of Atom Feed
	 *	@return		bool
	 */
	public function isValid( XmlElement $xmlElement ): bool
	{
		$this->validate( $xmlElement );
		return count( $this->errors ) === 0;
	}

	/**
	 *	Validates an XML Element built form an Atom XML String and returns broken Atom Rules.
	 *	@access		protected
	 *	@param		XmlElement		$xmlElement		Root Element of Atom Feed
	 *	@return		array
	 */
	protected function validate( XmlElement $xmlElement ): array
	{
		$errors	= [];
		foreach( $xmlElement->getDocNamespaces() ?: [] as $prefix => $namespace ){
			$prefix	= $prefix ?: "atom";
			$xmlElement->registerXPathNamespace( $prefix, $namespace );
		}
		$key	= "//atom:feed/";
		if( !count( $xmlElement->xpath( $key.'atom:author' ) ?: [] ) )
			foreach( $xmlElement->entry as $entry )
				if( !$entry->author )
					$errors[]	= "feed_author";
		if( count( $xmlElement->xpath( $key.'atom:generator' ) ?: [] ) > 1 )
			$errors[]	= "feed_generator";
		if( count( $xmlElement->xpath( $key.'atom:icon' ) ?: [] ) > 1 )
			$errors[]	= "feed_icon";
		if( count( $xmlElement->xpath( $key.'atom:logo' ) ?: [] ) > 1 )
			$errors[]	= "feed_logo";
		if( count( $xmlElement->xpath( $key.'atom:id' ) ?: [] ) != 1 )
			$errors[]	= "feed_id";
		if( count( $xmlElement->xpath( $key.'atom:rights' ) ?: [] ) > 1 )
			$errors[]	= "feed_rights";
		if( count( $xmlElement->xpath( $key.'atom:subtitle' ) ?: [] ) > 1 )
			$errors[]	= "feed_subtitle";
		if( count( $xmlElement->xpath( $key.'atom:title' ) ?: [] ) != 1 )
			$errors[]	= "feed_title";
		if( count( $xmlElement->xpath( $key.'atom:updated' ) ?: [] ) != 1 )
			$errors[]	= "feed_updated";
		$ids	= [];
		foreach( $xmlElement->xpath( $key.'atom:link[@rel="alternate"]' ) ?: [] as $link ){
			$id	= "";
			if( $link->hasAttribute( 'type' ) )
				$id	= $link->getAttribute( 'type' );
			if( $link->hasAttribute( 'hreflang' ) )
				$id	.= "_".$link->getAttribute( 'hreflang');
			if( in_array( $id, $ids, TRUE ) ){
				$errors[]	= "feed_link_unique";
				break;
			}
			$ids[]	= $id;
		}

		$numberEntries	= count( $xmlElement->xpath( $key.'atom:entry' ) ?: [] );
		for( $i=1; $i<=$numberEntries; $i++ ){
			$key	= "//atom:feed/atom:entry[$i]/";
			if( !count( $xmlElement->xpath( $key.'atom:author' ) ?: [] ) && !count( $xmlElement->xpath( '//atom:feed/atom:author' ) ?: [] ) )
				$errors[]	= "entry_author";
			if( count( $xmlElement->xpath( $key.'atom:content' ) ?: [] ) > 1 )
				$errors[]	= "entry_content";
			if( count( $xmlElement->xpath( $key.'atom:id' ) ?: [] ) != 1 )
				$errors[]	= "entry_id";
			if( !count( $xmlElement->xpath( $key.'atom:content' ) ?: [] ) && !count( $xmlElement->xpath( $key.'atom:link[@rel="alternate"]' ) ?: [] ) )
				$errors[]	= "entry_link_alt";
			if( count( $xmlElement->xpath( $key.'atom:published' ) ?: [] ) > 1 )
				$errors[]	= "entry_published";
			if( count( $xmlElement->xpath( $key.'atom:rights' ) ?: [] ) > 1 )
				$errors[]	= "entry_rights";
			if( count( $xmlElement->xpath( $key.'atom:source' ) ?: [] ) > 1 )
				$errors[]	= "entry_source";
			if( count( $xmlElement->xpath( $key.'atom:summary' ) ?: [] ) > 1 )
				$errors[]	= "entry_summary";
			if( count( $xmlElement->xpath( $key.'atom:title' ) ?: [] ) != 1 )
				$errors[]	= "entry_title";
			if( count( $xmlElement->xpath( $key.'atom:updated' ) ?: [] ) != 1 )
				$errors[]	= "entry_updated";

			$keys	= [];
			foreach( $xmlElement->xpath( $key.'atom:link[@rel="alternate"]' ) ?: [] as $link ){
				$key	= "";
				if( $link->hasAttribute( 'type' ) )
					$key	= $link->getAttribute( 'type' );
				if( $link->hasAttribute( 'hreflang' ) )
					$key	.= "_".$link->getAttribute( 'hreflang');
				if( in_array( $key, $keys, TRUE ) ){
					$errors[]	= "entry_link_unique";
					break;
				}
				$keys[]	= $key;
			}
		}
		$this->errors	= $errors;
		return $errors;
	}
}
