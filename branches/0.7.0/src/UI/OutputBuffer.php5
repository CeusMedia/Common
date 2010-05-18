<?php
/**
 *	Buffer for Standard Output Channel.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		UI
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.09.2005
 *	@version		$Id$
 */
/**
 *	Buffer for Standard Output Channel.
 *	@category		cmClasses
 *	@package		UI
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.09.2005
 *	@version		$Id$
 */
class UI_OutputBuffer
{
	/**	@var		bool		$isOpen		Flag: Buffer opened */
	protected $isOpen = false;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$open		Flag: open Buffer with Instance
	 *	@return		void
	 */
	public function __construct ( $open = true )
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
		$this->isOpen = false;
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
	 *	@param		bool		$clear		Flag: clear Output Buffer afterwards		
	 *	@return		string
	 */
	public function get( $clear = false )
	{
		if( !$this->isOpen() )
			throw new RuntimeException( 'Output Buffer is not open.' );
		return $clear ? ob_get_clean() : ob_get_contents();
	}

	/**
	 *	Indicates whether Output Buffer is open.
	 *	@access		public
	 *	@return		void
	 */
	public function isOpen()
	{
		return (bool) $this->isOpen;
	}
	
	/**
	 *	Opens Output Buffer.
	 *	@access		public
	 *	@return		void
	 */
	public function open()
	{
		if( $this->isOpen() )
			throw new RuntimeException( 'Output Buffer is already open.' );
		ob_start();
		$this->isOpen = true;
	}
}
?>