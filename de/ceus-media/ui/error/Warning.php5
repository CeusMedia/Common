<?php
/**
 *	@package	ui
 *	@subpackage	error
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	ui
 *	@subpackage	error
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Warning
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$errstr		Error Message
	 *	@return 		void
	 */
	public function __construct( $errstr )
	{
		$backtrace = debug_backtrace();
		print_m($backtrace);
		$last = array();
		$backtrace = array_reverse( $backtrace );

		$as = array( 'file','line', 'class', 'function', 'type', 'args' );
		$last = array();
		for( $i = 0; $i<count( $backtrace ); $i++ )
		{
			echo "i: ".$i."<br/>";
			echo "<b>".$backtrace[$i]['file']."</b><br/>";
			print_m($backtrace[$i]);
			$class	= isset( $backtrace[$i]['class'] ) ? $backtrace[$i]['class'] : "";
			$function	= isset( $backtrace[$i]['function'] ) ? $backtrace[$i]['function'] : "";
			if( $class ==  __CLASS__ && $function ==  __FUNCTION__ )
			{
				$last['file'] = $backtrace[$i]['file'];
				$last['line'] = $backtrace[$i]['line'];
				break;
			}
			else
			{
				foreach( $as as $a )
					if( isset( $backtrace[$i][$a] ) )
						$last[$a]	=	$backtrace[$i][$a];
			}
		}
		trigger_error( $errstr, E_USER_WARNING );
	}
}
?>