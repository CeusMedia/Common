<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Buffer for standard output channel.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI;

use RuntimeException;

/**
 *	Buffer for standard output channel.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class OutputBuffer
{
	/**	@var		boolean		$isOpen		Flag: Buffer opened */
	protected bool $isOpen = FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		boolean		$open		Flag: open buffer with instance
	 *	@return		void
	 */
	public function __construct ( bool $open = TRUE )
	{
		if( $open )
			$this->open();
	}

	/**
	 *	Clears output buffer, if open.
	 *	@access		public
	 *	@return		static
	 */
	public function clear(): static
	{
		if( $this->checkStatus( TRUE, FALSE ) )
			ob_clean();
		return $this;
	}

	/**
	 *	Closes output buffer.
	 *	@access		public
	 *	@return		static
	 */
	public function close(): static
	{
		$this->checkStatus( TRUE, TRUE );
		ob_end_clean();
		$this->isOpen = FALSE;
		return $this;
	}

	/**
	 *	Sends content of output buffer to standard output stream.
	 *	Does not close the buffer, but clears it.
	 *	@access		public
	 *	@return		static
	 */
	public function flush(): static
	{
		ob_flush();
		return $this;
	}

	/**
	 *	Return content and clear output buffer.
	 *	@access		public
	 *	@return		static
	 */
	public function flushAndClose(): static
	{
		ob_flush();
		$this->close();
		return $this;
	}

	/**
	 *	Returns content of open output buffer.
	 *	@access		public
	 *	@param		boolean		$clear		Flag: clear output buffer afterwards
	 *	@return		string
	 *	@throws		RuntimeException		if buffer is not open
	 */
	public function get( bool $clear = FALSE ): string
	{
		$this->checkStatus( TRUE, TRUE );
		$content	= ob_get_contents();
		if( $clear )
			ob_clean();
		return $content;
	}

	/**
	 *	Returns content if open and closes output buffer.
	 *	@return		string
	 */
	public function getAndClose(): string
	{
		$content	= $this->get( TRUE );
		$this->close();
		return $content;
	}

	/**
	 *	Indicates whether output buffer is open and has content.
	 *	@return bool
	 */
	public function has(): bool
	{
		return $this->isOpen && '' !== $this->get();
	}

	/**
	 *	Indicates whether output buffer is open.
	 *	@access		public
	 *	@return		bool
	 */
	public function isOpen(): bool
	{
		return $this->isOpen;
	}

	/**
	 *	Opens output buffer.
	 *	@access		public
	 *	@return		static
	 *	@throws		RuntimeException		if buffer is open, already
	 */
	public function open(): static
	{
		$this->checkStatus( FALSE, TRUE );
		ob_start();
		$this->isOpen = TRUE;
		return $this;
	}

	/**
	 *	@param		bool		$shouldBeOpen
	 *	@param		bool		$strict			Flag: throw exception, default: no
	 *	@return		bool
	 */
	protected function checkStatus( bool $shouldBeOpen = TRUE, bool $strict = FALSE ): bool
	{
		if( $shouldBeOpen && $this->isOpen || !$shouldBeOpen && !$this->isOpen )
			return TRUE;
		if( !$strict )
			return FALSE;
		if( $shouldBeOpen )
			throw new RuntimeException( 'Output Buffer is not open.' );
		$this->close();
		throw new RuntimeException( 'Output Buffer is already open.' );
	}
}
