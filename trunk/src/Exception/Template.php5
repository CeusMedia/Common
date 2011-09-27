<?php
/**
 *	Exception for Templates.
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
 *	@package		Exception
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		$Id$
 */
/**
 *	Exception for Templates.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		RuntimeException
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		$Id$
 */
class Exception_Template extends RuntimeException
{
	const FILE_NOT_FOUND		= 0;
	const FILE_LABELS_MISSING	= 1;
	const LABELS_MISSING		= 2;

	/**	@var		string		$messages		Map of Exception Messages, can be overwritten statically */
	public static $messages	= array(
		self::FILE_NOT_FOUND		=> 'Template File "%1$s" is missing',
		self::FILE_LABELS_MISSING	=> 'Template "%1$s" is missing %2$s',
		self::LABELS_MISSING		=> 'Template is missing %1$s',
	);

	/**	@var		array		$labels			Holds all not used and non optional labels */
	protected $labels			= array();
	/**	@var		string		$filePath		File Path of Template, set only if not found */
	protected $filePath			= NULL;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$code			Exception Code
	 *	@param		string		$fileName		File Name of Template
	 *	@param		mixed		$data			Some additional data
	 *	@return		void
	 */
	public function __construct( $code, $fileName, $data = array() )
	{
		$tagList	= '"'.implode( '", "', $data ).'"';
		switch( $code )
		{
			case self::FILE_NOT_FOUND:
				$this->filePath	= $data;
				$message		= self::$messages[self::FILE_NOT_FOUND];
				$message		= sprintf( $message, $fileName );
				parent::__construct( $message, self::FILE_NOT_FOUND );
				break;
			case self::FILE_LABELS_MISSING:
				$this->labels	= $data;
				$message		= self::$messages[self::FILE_LABELS_MISSING];
				$message		= sprintf( $message, $fileName, $tagList );
				parent::__construct( $message, self::FILE_LABELS_MISSING );
				break;
			case self::LABELS_MISSING:
				$this->labels	= $data;
				$message		= self::$messages[self::LABELS_MISSING];
				$message		= sprintf( $message, $tagList );
				parent::__construct( $message, self::LABELS_MISSING );
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
	
	/**
	 *	Returns File Path of Template if not found.
	 *	@access	  public
	 *	@return	  array		{@link $filePath} 
	 */
	public function getFilePath()
	{
		return $this->filePath;
	}
}
?>
