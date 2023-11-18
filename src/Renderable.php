<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Interface for all Classes which are render-able.
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

/**
 *	Interface for all Classes which are render-able.
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
interface Renderable
{
	/**
	 *	Returns as string.
	 *	@return		string
	 */
	public function render(): string;
}
