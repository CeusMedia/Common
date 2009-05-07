<?php
import( 'de.ceus-media.framework.krypton.Base' );
/**
 *	Handler for static Error Pages.
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
 *	@package		framework.krypton
 *	@extends		Framework_Krypton_Base
 *	@uses			Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.03.2008
 *	@version		0.1
 */
/**
 *	Handler for static Error Pages.
 *	@package		framework.krypton
 *	@extends		Framework_Krypton_Base
 *	@uses			Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			28.03.2008
 *	@version		0.1
 */
class Framework_Krypton_ErrorPageHandler extends Framework_Krypton_Base
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$configPath			Path to basic Configuration Files
	 *	@param		bool		$identifyLanguage	Flag: use Language-Accept from Browser
	 *	@return		void
	 */
	public function __construct( $configPath = "config/" )
	{
		$this->type		= array_shift( array_keys( $_REQUEST ) );
		$this->level	= 0;
		try
		{
			//  --  ENVIRONMENT  --  //
			$this->initRegistry();							//  must be first
			$this->initConfiguration( $configPath );		//  must be one of the first
			$this->initEnvironment();						//  must be one of the first
			$this->initSession();
			$this->initRequest();

			//  --  RESOURCE SUPPORT  --  //
			$this->initDatabase( $configPath );				//  needs Configuration
			$this->initLanguage();							//  needs Request and Session
			$this->initThemeSupport();						//  needs Configuration, Request and Session

			$this->level	= 1;
		}
		catch( PDOException $e )
		{
		}
		catch( Exception $e )
		{
			throw $e;
		}
	}

	/**
	 *	Builds Error Page View.
	 *	@access		public
	 *	@return		string
	 */
	public function buildView()
	{
		if( !$this->type )
		{
			throw new Exception( "No Error Type defined." );
		}
		if( $this->level )
		{
			try
			{
				import( 'de.ceus-media.framework.krypton.core.View' );
				$view	= new Framework_Krypton_Core_View();
				$html	= $view->loadContent( $this->type.'.html' );

			}
			catch( Exception $e )
			{
#				die( $e->getMessage() );
				throw new Exception( "Invalid Error Type given." );
			}
		}
		else
		{
			$languages	= array(
				"de",
				"fr",
				"es",
				"it",
			);
			import( 'de.ceus-media.net.http.LanguageSniffer' );
			$sniffer	= new Net_HTTP_LanguageSniffer;
			$language	= $sniffer->getLanguage( $languages );
			$html		= file_get_contents( "contents/html/".$language."/".$this->type.".html" );
		}
		return $html;
	}
}
?>