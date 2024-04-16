<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Adds description, suggestion to exception.
 *	Also, makes exception properties visible.
 *
 *	Copyright (c) 2011-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */

namespace CeusMedia\Common\Exception\Traits;

/**
 *	Adds description, suggestion to exception.
 *	Also, makes exception properties visible.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */
trait Descriptive
{
	protected string $description	= '';

	protected string $suggestion	= '';

	public function getAdditionalProperties(): array
	{
		$variables	= get_object_vars( $this );
		foreach( $variables as $key => $value )
			if( in_array( $key, ['message', 'code', 'file', 'line', 'trace', 'previous'], TRUE ) )
				unset( $variables[$key] );
		return $variables;
	}

	/**
	 *	@return		string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 *	@return		string
	 */
	public function getSuggestion(): string
	{
		return $this->suggestion;
	}

	/**
	 * @param string $suggestion
	 * @return static
	 *
	 */
	public function setSuggestion( string $suggestion ): static
	{
		$this->suggestion	= $suggestion;
		return $this;
	}

	public function setDescription( string $description ): self
	{
		$this->description	= $description;
		return $this;
	}
}
