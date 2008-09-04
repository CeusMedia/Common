<?php
import( 'de.ceus-media.framework.krypton.core.Template' );
/**
 *	Template Component.
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
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_Template
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.03.2007
 *	@version		0.2
 */
/**
 *	Template Component.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_Template
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.03.2007
 *	@version		0.2
 */
class Framework_Krypton_View_Component_Template extends Framework_Krypton_Core_Template
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		File Name of Template
	 *	@param		array		$elements		Array of Elements to set in Template
	 *	@return		void
	 */
	public function __construct( $filename, $elements = null )
	{
		$config		= Framework_Krypton_Core_Registry::getStatic( 'config' );

		//  --  BASICS  --  //
		$basepath	= $config['paths']['templates'];
		$basename	= str_replace( ".", "/", $filename ).".html";

		//  --  FILE URI CHECK  --  //
		$uri	= $filename = $basepath.$basename;
		if( !file_exists( $uri ) )							//  check file
			throw new Framework_Krypton_Exception_IO( "Template '".$filename."' is existing in '".$uri."'." );	
		parent::__construct( $uri, $elements );
	}
}  
?>
