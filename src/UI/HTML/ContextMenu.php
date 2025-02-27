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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ContextMenu
{
	public static function buildCode( string|Stringable $context, string|Stringable $content, string $id ): string
	{
		$label		= Tag::create( 'div', $context, ['class' => 'label'] );
		$opener		= Tag::create( 'div', Tag::create( 'span', '&nabla;' ), ['class' => 'opener'] );
		$options	= Tag::create( 'div', $content, ['class' => 'contextMenu', 'id' => $id] );
		$html		= Tag::create( 'div', $label.$opener.$options, ['class' => 'cmContextMenu'] );
		return $html;
	}

	public static function buildScript( string $selector, array $options	= [] ): string
	{
		return JQuery::buildPluginCall( 'cmContextMenu', $selector, $options );
	}

}
