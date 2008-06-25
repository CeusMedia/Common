<?php
/**
 *	Exception for Templates.
 *	@package		mv2.exception
 *	@extends		RuntimeException
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@since			03.03.2007
 *	@version		0.1
 */

/**   not all labels used constant */
define('TEMPLATE_EXCEPTION_LABELS_NOT_USED', 100);
 
/**
 *	Exception for Templates.
 *	@package		mv2.exception
 *	@extends		RuntimeException
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@since			03.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_Template extends RuntimeException
{
	/**	@var		string		$exceptionMessage		Message of Exception with Placeholder */
	public static $exceptionMessage	= 'Not all non-optional labels are defined in Template "%s"';

	/**	@var		array		$labels		Holds all not used and non optional labels */
	private $labels;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$code		Exception Code
	 *	@param		string		$filename	File Name of Template
	 *	@param		mixed		$data		Some additional data
	 *	@return		void
	 */
	public function __construct( $code, $filename, $data = null )
	{
		switch( $code )
		{
			case TEMPLATE_EXCEPTION_LABELS_NOT_USED:
				$this->labels	= $data;
				$message		= sprintf( self::$exceptionMessage, $filename );
				parent::__construct( $message, TEMPLATE_EXCEPTION_LABELS_NOT_USED );
				break;
		}
	}
	
	/**
	 *	Returns not used Labels.
	 *	@access	  public
	 *	@return	  array		{@link $labels} 
	 */
	public function getNotUsedLabels()
	{
		return $this->labels;
	}
}
?>
