<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Validator for XML Syntax.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use DOMDocument;

/**
 *	Validator for XML Syntax.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SyntaxValidator
{
	/**	@var	DOMDocument|NULL		$document	DOM Document of Syntax is valid */
	protected $document	= NULL;

	/**	@var	string|NULL				$errors		Parsing Errors if Syntax is invalid */
	protected $errors	= '';

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
	 *	Validates XML Document.
	 *	@access		public
	 *	@param		string		$xml		XML to be validated
	 *	@return		bool
	 */
	public function validate( string $xml ): bool
	{
		$this->document	= new DOMDocument();
		ob_start();
		$this->document->validateOnParse	= TRUE;
		$this->document->loadXML( $xml );
		$this->errors	= ob_get_contents() ?: NULL;
		ob_end_clean();
		if( !$this->errors )
			return TRUE;
		return FALSE;
	}
}
