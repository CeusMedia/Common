<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Buffer for Standard Output Channel.
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
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI;

use RuntimeException;

/**
 *	Buffer for Standard Output Channel.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class OutputBuffer
{
	/**	@var		boolean		$isOpen		Flag: Buffer opened */
	protected $isOpen = FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		boolean		$open		Flag: open Buffer with Instance
	 *	@return		void
	 */
	public function __construct ( bool $open = TRUE )
	{
		if( $open )
			$this->open();
	}

	/**
	 *	Clears Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		ob_clean();
	}

	/**
	 *	Closes Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function close()
	{
		ob_end_clean();
		$this->isOpen = FALSE;
	}

	/**
	 *	Return Content and clear Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function flush()
	{
		ob_flush();
	}

	/**
	 *	Returns Content of Output Buffer.
	 *	@access		public
	 *	@param		boolean		$clear		Flag: clear Output Buffer afterwards
	 *	@return		string
	 */
	public function get( bool $clear = FALSE ): string
	{
		if( !$this->isOpen() )
			throw new RuntimeException( 'Output Buffer is not open.' );
		return $clear ? ob_get_clean() : ob_get_contents();
	}

	public function has(): bool
	{
		return strlen( $this->get() ) !== 0;
	}

	/**
	 *	Indicates whether Output Buffer is open.
	 *	@access		public
	 *	@return		bool
	 */
	public function isOpen(): bool
	{
		return $this->isOpen;
	}

	/**
	 *	Opens Output Buffer.
	 *	@access		public
	 *	@return		void
	 *	@throws		RuntimeException		if buffer is open, already
	 */
	public function open()
	{
		if( $this->isOpen() )
			throw new RuntimeException( 'Output Buffer is already open.' );
		ob_start();
		$this->isOpen = TRUE;
	}
}
