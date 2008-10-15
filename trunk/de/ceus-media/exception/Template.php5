<?php
/**
 *	Exception for Templates.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		framework.krypton.exception
 *	@extends		RuntimeException
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		0.1
 */
/**   not all labels used constant */
define('TEMPLATE_EXCEPTION_LABELS_NOT_USED', 100);
/**
 *	Exception for Templates.
 *	@package		framework.krypton.exception
 *	@extends		RuntimeException
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		0.1
 */
class Exception_Template extends RuntimeException
{
	/**	@var		string		$exceptionMessage		Message of Exception with Placeholder */
	public static $exceptionMessage	= 'Not all non-optional labels are defined in Template "%s"';

	/**	@var		array		$labels		Holds all not used and non optional labels */
	private $labels;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$code		Exception Code
	 *	@param		string		$filename	File Name of Template
	 *	@param		mixed		$data		Some additional data
	 *	@return		void
	 */
	public function __construct( $code, $filename, $data = null )
	{
		switch( $code )
		{
			case TEMPLATE_EXCEPTION_LABELS_NOT_USED:
				$this->labels	= $data;
				$message		= sprintf( self::$exceptionMessage, $filename );
				parent::__construct( $message, TEMPLATE_EXCEPTION_LABELS_NOT_USED );
				break;
		}
	}
	
	/**
	 *	Returns not used Labels.
	 *	@access	  public
	 *	@return	  array		{@link $labels} 
	 */
	public function getNotUsedLabels()
	{
		return $this->labels;
	}
}
?>
