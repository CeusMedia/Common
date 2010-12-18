<?php
/**
 *	Exception interface.
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
 *	Exception interface.
 *	@category		cmClasses
 *	@package		Exception
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 *	@todo			test and write unit tests, remove see-link later
 */
interface Exception_Interface
{
	/* Protected methods inherited from Exception class */
	public function getMessage();												// Exception message
	public function getCode();													// User-defined Exception code
	public function getFile();													// Source filename
	public function getLine();													// Source line
	public function getTrace();													// An array of the backtrace()
	public function getTraceAsString();											// Formated string of trace

	/* Overrideable methods inherited from Exception class */
	public function __toString();												// formated string for display
	public function __construct( $message = NULL, $code = 0 );
}
?>