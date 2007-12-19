<?php
import( 'de.ceus-media.framework.krypton.core.Action' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_Action
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 *	@deprecated		XML Definition moved to Core_Logic, to be deleted soon
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_Action
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_DefinitionAction extends Framework_Krypton_Core_Action
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version		0.1
	 */
	function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
		$this->loadLanguage( 'validator', false, false );
	}
}
?>
