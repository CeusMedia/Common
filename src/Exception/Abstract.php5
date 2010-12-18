<?php
/**
 *	Abstract exception.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		Exception
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Abstract exception.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		Exception
 *	@implements		Exception_Interface
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://www.php.net/manual/de/language.exceptions.php#91159
 *	@since			0.7.0
 *	@version		$Id$
 *	@todo			test and write unit tests, remove see-link later
 */
abstract class Exception_Abstract extends Exception implements Exception_Interface
{
	protected $message = 'Unknown exception';     // Exception message
	private   $string;                            // Unknown
	protected $code    = 0;                       // User-defined exception code
	protected $file;                              // Source filename of exception
	protected $line;                              // Source line of exception
	private   $trace;                             // Unknown

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$message
	 *	@param		integer		$code
	 *	@return		void
	 */
	public function __construct( $message = NULL, $code = 0 )
	{
		if( !$message )
			throw new $this( 'Unknown '.get_class( $this ) );
		parent::__construct( $message, $code );
	}

	/**
	 *	String representation of exception.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return sprintf(
			'%1$s "{%2$s}" in {%3$s}(%4$s) '.PHP_EOL.'%5$s',
			get_class( $this ),
			$this->message,
			$this->file,
			$this->line,
			$this->getTraceAsString()
		);
	}
}
?>