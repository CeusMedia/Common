<?php
/**
 *	Combines Stylesheet Files of a cmFramework Theme to one single File.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		file.css.theme
 *	@uses			File_CSS_Combiner
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
import( 'de.ceus-media.file.css.Combiner' );
/**
 *	Combines Stylesheet Files of a cmFramework Theme to one single File.
 *	@category		cmClasses
 *	@package		file.css.theme
 *	@uses			File_CSS_Combiner
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.1
 */
class File_CSS_Theme_Combiner extends File_CSS_Combiner
{
	const PROTOCOL_NONE		= 0;
	const PROTOCOL_HTTP		= 1;
	const PROTOCOL_HTTPS	= 2;

	protected $protocol;

	/**
	 *	Callback Method for additional Modifikations before Combination.
	 *	@access		protected
	 *	@param		string		$content		Content of Style File
	 *	@return		string		Revised Content of Style File
	 */
	protected function reviseStyle( $content )
	{
		if( $this->protocol == self::PROTOCOL_HTTP )
		{
			$content	= str_ireplace( "https://", "http://", $content );
		}
		else if( $this->protocol == self::PROTOCOL_HTTPS )
		{
			$content	= str_ireplace( "http://", "https://", $content );
		}
		return $content;
	}

	public function setProtocol( $integer )
	{
		$this->protocol	= $integer;
	}
}
?>