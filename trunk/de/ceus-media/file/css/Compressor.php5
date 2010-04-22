<?php
/**
 *	Compresses CSS Files..
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
 *	@package		file.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.09.2007
 *	@version		$Id$
 */
/**
 *	Compresses CSS Files..
 *	@category		cmClasses
 *	@package		file.css
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.09.2007
 *	@version		$Id$
 */
class File_CSS_Compressor
{
	/**	@var		string			$prefix			Prefix of compressed File Name */
	var $prefix		= "";
	/**	@var		array			$statistics		Statistical Data */
	var $statistics	= array();
	/**	@var		string			$suffix			Suffix of compressed File Name */
	var $suffix		= ".min";
	
	/**
	 *	Returns statistical Data of last Combination.
	 *	@access		public
	 *	@return		array	
	 */
	public function getStatistics()
	{
		return $this->statistics;
	}
	
	/**
	 *	Compresses a CSS String.
	 *	@access		public
	 *	@param		string		$content		Content of CSS
	 *	@return		string
	 */
	public function compressString( $content )
	{
		$map	= array(
			'!/\*[^*]*\*+([^/][^*]*\*+)*/!'	=> '',								// remove comments
			'!\s//.*(\r)?\n!U'				=> "\n",							// remove comments (single line)
			'@( +):@'						=> ':',
			'@:( +)@'						=> ':',
			'@( +){@'						=> '{',
		);
		foreach( $map as $pattern => $replacement )
			$content	= preg_replace( $pattern, $replacement, $content );		// replace pattern if found

		$toRemove	= array( "\r", "\n", "\t", '  ', '    ', '    ' );
		$content	= str_replace( $toRemove, '', $content );					// remove tabs, spaces, newlines, etc.
		return $content;
	}
	
	/**
	 *	Reads and compresses a CSS File and returns Length of compressed File.
	 *	@access		public
	 *	@param		string		$fileUri		Full URI of CSS File
	 *	@return		string
	 */
	public function compressFile( $fileUri )
	{
		if( !file_exists( $fileUri ) )
			throw new Exception( "Style File '".$fileUri."' is not existing." );

		$this->statistics	= array();

		$content	= file_get_contents( $fileUri );
		$this->statistics['before']	= strlen( $content );
		$content	= $this->compressString( $content );
		$this->statistics['after']	= strlen( $content );
		
		$pathName	= dirname( $fileUri );
		$styleFile	= basename( $fileUri );
		$styleName	= preg_replace( "@\.css$@", "", $styleFile );
		$fileName	= $this->prefix.$styleName.$this->suffix.".css";
		$fileUri	= $pathName."/".$fileName;
		$fileUri	= str_replace( "\\", "/", $fileUri );
		
		file_put_contents( $fileUri, $content );
		return $fileUri;
	}

	/**
	 *	Sets Prefix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Prefix of compressed File Name
	 *	@return		void
	 */
	public function setPrefix( $prefix )
	{
		if( trim( $prefix ) )
			$this->prefix	= $prefix;
	}	
	
	/**
	 *	Sets Suffix of compressed File Name.
	 *	@access		public
	 *	@param		string		$prefix			Suffix of compressed File Name
	 *	@return		void
	 */
	public function setSuffix( $suffix )
	{
		if( trim( $suffix ) )
			$this->suffix	= $suffix;
	}	
}
?>