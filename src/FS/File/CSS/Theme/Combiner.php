<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Combines Stylesheet Files of a cmFramework Theme to one single File.
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
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS\Theme;

use CeusMedia\Common\FS\File\CSS\Combiner as CssCombiner;

/**
 *	Combines Stylesheet Files of a cmFramework Theme to one single File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS_Theme
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Combiner extends CssCombiner
{
	public const PROTOCOL_NONE		= 0;
	public const PROTOCOL_HTTP		= 1;
	public const PROTOCOL_HTTPS		= 2;

	protected int $protocol			= self::PROTOCOL_NONE;

	/**
	 *	Callback Method for additional Modifications before Combination.
	 *	@access		protected
	 *	@param		string		$content		Content of Style File
	 *	@return		string		Revised Content of Style File
	 */
	protected function reviseStyle( string $content ): string
	{
		if( $this->protocol == self::PROTOCOL_HTTP ){
			$content	= str_ireplace( "https://", "http://", $content );
		}
		else if( $this->protocol == self::PROTOCOL_HTTPS ){
			$content	= str_ireplace( "http://", "https://", $content );
		}
		return $content;
	}

	public function setProtocol( int $integer ): self
	{
		$this->protocol	= $integer;
		return $this;
	}
}
