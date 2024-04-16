<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Allows exception to be converted to HTML or plaintext.
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

use CeusMedia\Common\CLI\Exception\View as CliView;
use CeusMedia\Common\UI\HTML\Exception\View as HtmlView;
use CeusMedia\Common\Env;
use Exception;
use Throwable;

/**
 *	Allows exception to be converted to HTML or plaintext.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */
trait Renderable
{
//	const FORMAT_AUTO		= 0;
//	const FORMAT_PLAINTEXT	= 1;
//	const FORMAT_HTML		= 2;

	protected int $format	= 0;

	/**
	 *	@return		string
	 *	@throws		Exception
	 */
	public function render(): string
	{

		$format	= 0 !== $this->format ? $this->format : ( Env::isCli() ? 1 : 2 );
		/** @var Throwable $this */
		return match( $format ){
			2	=> HtmlView::render($this),
			default	=> CliView::getInstance($this)->render(),
		};
	}

	/**
	 *	Sets forced output format. 0:auto, 1:plaintext, 2:HTML
	 *	Constants will be available when using PHP 8.2.
	 *	@param		int		$format
	 *	@return		static
	 */
	public function setFormat( int $format ): static
	{
		if( !in_array( $format, [0, 1, 2] ) )
			throw new \OutOfBoundsException( 'Invalid format' );
		$this->format	= $format;
		return $this;
	}
}