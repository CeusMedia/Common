<?php
/**
 *	@package		ui
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package		ui
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo			finish Implementation or cancel
 *	@todo			Code Documentation
 */
class Object2UML
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		File name of Class to parse 
	 *	@return		void
	 */
	public function __construct( $object = NULL)
	{
		if( NULL !== $object && is_object( $object ) )
		{
		
		
		}
	}
	
	function scanObject( $object )
	{
		get_object_vars( $object );
		get_object_functions( $object );
		
	
	}

}
?>