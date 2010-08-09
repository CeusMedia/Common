<?php
/**
 *	Filters HTML Content by stripping out unwanted Content Types like Scripts or Styles.
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
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.4
 *	@version		$Id$
 */
/**
 *	Filters HTML Content by stripping out unwanted Content Types like Scripts or Styles.
 *	@category		cmClasses
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.4
 *	@version		$Id$
 */
class Alg_Text_Filter
{
	/**
	 *	Strips all Comments from String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripComments( $string )
	{
		$string	= preg_replace( "@<![\s\S]*?--[ \t\n\r]*>@", "", $string );
		$string	= preg_replace( "@/\*.+\*/@siU", "", $string );
		return $string;
	}

	/**
	 *	Strips all JavaScripts from HTML String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripScripts( $string )
	{
		$string	= preg_replace( "@<\s*s\s*c\s*r\s*i\s*p\s*t[^>]*>.*<\s*/\s*s\s*c\s*r\s*i\s*p\s*t\s*>@siU", "", $string );
		return $string;
	}

	/**
	 *	Strips all Styles and Stylesheet Links from HTML String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripStyles( $string )
	{
		$string	= preg_replace( "@<style[^>]*?>.*?</style>@siU", "", $string );
		$string	= preg_replace( "@<link .*(('|\")\s*stylesheet\s*('|\")|\.css).+(/>|</link>)@siU", "", $string );
		return $string;
	}
	
	/**
	 *	Strips all Tags (<...>) from String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripTags( $string )
	{
		$string	= preg_replace( "@<[\/\!]*?[^<>]*?>@si", "", $string );
		return $string;
	}
	
	/**
	 *	Strips all JavaScript Event Attributes from HTML String.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to cleanse
	 *	@return		string
	 */
	public static function stripEventAttributes( $string )
	{
		$string	= preg_replace( '@(<[^>]+)\s+on[a-z]{4,}\s*=".+"@iU', "\\1", $string );
		$string	= preg_replace( "@(<[^>]+)\s+on[a-z]{4,}\s*='.+'@iU", "\\1", $string );
		return $string;
	}
}
?>