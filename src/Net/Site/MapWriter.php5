<?php
/**
 *	Google Sitemap XML Writer.
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
 *	@package		net.site
 *	@uses			GoogleSitemapBuilder
 *	@uses			File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.12.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.net.site.MapBuilder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Google Sitemap XML Writer.
 *	@category		cmClasses
 *	@package		net.site
 *	@uses			GoogleSitemapBuilder
 *	@uses			File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.12.2006
 *	@version		$Id$
 */
class Net_Site_MapWriter
{
	/**	@var		string		$fileName			File Name of Sitemap XML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Writes Sitemap for List of URLs.
	 *	@access		public
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public function write( $urls, $mode = 0755 )
	{
		return $this->save( $this->fileName, $urls, $mode );
	}
	
	/**
	 *	Saves Sitemap for List of URLs statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public static function save( $fileName, $urls, $mode = 0777 )
	{
		$builder	= new Net_Site_MapBuilder();
		$file		= new File_Writer( $fileName, $mode );
		$xml		= $builder->build( $urls );
		return $file->writeString( $xml );
	}
}
?>