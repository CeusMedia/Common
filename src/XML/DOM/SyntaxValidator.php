<?php /** @noinspection PhpComposerExtensionStubsInspection */

declare(strict_types=1);

/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Validator for XML Syntax.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use DOMDocument;

/**
 *	Validator for XML Syntax.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SyntaxValidator
{
	/**	@var	DOMDocument|NULL		$document	DOM Document of Syntax is valid */
	protected ?DOMDocument $document	= NULL;

	/**	@var	string|NULL				$errors		Parsing Errors if Syntax is invalid */
	protected ?string $errors			= '';

	/**
	 *	Returns DOM Document Object of XML Document if Syntax is valid.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	public function & getDocument(): DOMDocument
	{
		return $this->document;
	}

	/**
	 *	Returns Array of parsing Errors.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getErrors(): ?string
	{
		return $this->errors ?: NULL;
	}

	/**
	 *	Validates XML syntax (well-formedness) and stores the DOM document if valid.
	 *	Errors are collected using libxml, independent of PHP error display settings.
	 *	No DTD is loaded or validated.
	 *	@access		public
	 *	@param		string		$xml		XML to be validated
	 *	@return		bool
	 */
	public function validate( string $xml ): bool
	{
		$this->document	= new DOMDocument();
		$this->errors	= NULL;
		$useErrors		= libxml_use_internal_errors( TRUE );
		libxml_clear_errors();
		$loaded			= '' !== trim( $xml ) && $this->document->loadXML( $xml, LIBXML_NONET );
		$messages		= [];
		foreach( libxml_get_errors() as $error )
			if( $error->level >= LIBXML_ERR_ERROR )
				$messages[]	= trim( $error->message ).' in line '.$error->line;
		libxml_clear_errors();
		libxml_use_internal_errors( $useErrors );
		if( !$loaded && [] === $messages )
			$messages[]	= 'XML could not be parsed';
		$this->errors	= [] !== $messages ? implode( PHP_EOL, $messages ) : NULL;
		return NULL === $this->errors;
	}
}
