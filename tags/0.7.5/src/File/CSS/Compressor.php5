<?php
/**
 *	Compresses CSS Files..
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.09.2007
 *	@version		$Id$
 */
/**
 *	Compresses CSS Files..
 *	@category		cmClasses
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
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

/*	static public function compressFile( $fileName, $oneLine = FALSE ){
		return self::compressString( File_Reader::load( $fileName ), $oneLine );
	}
*/
	static public function compressSheet( ADT_CSS_Sheet $sheet, $oneLine = FALSE ){
		$converter	= new File_CSS_Converter( $sheet );
		return self::compressString( $converter->toString(), $oneLine );
	}

	static public function compressString( $string, $oneLine = FALSE ){
		$string	= preg_replace( '@/\*.*\*/@sU', '', $string );											//  remove code doc blocks
		$string	= preg_replace( '@(\S),\n(\S)@sU', "\\1,\\2", $string );								//  remove break in selector lists
		$string	= preg_replace( '@\n\s+@sU', "\n", $string );											//  remove spaces after breaks
		$string	= preg_replace( '@;\s+(\S)@s', ";\\1", $string );										//  remove breaks and spaces between properties
		$string	= preg_replace( '@\s*\{\s*@s', "{", $string );											//  @todo: may be slow
		$string	= preg_replace( '@\s*;?\}'.( $oneLine ? '\s*' : '' ).'@s', "}", $string );				//  @todo: may be slow
		$string	= preg_replace( '@\n+@s', "\n", $string );												//  compact breaks
		$string	= preg_replace( '@\s*:\s*@', ':', $string );											//  remove space after property key and before property value
		$string	= preg_replace( '@\.\./@', '', $string );												//  rewrite resource path
		$string	= trim( $string );
		return $string;
	}

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
		$content	= self::compressString( $content );
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
