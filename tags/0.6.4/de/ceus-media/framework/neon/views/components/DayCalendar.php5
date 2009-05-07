<?php
/**
 *	Builds HTML for Day Calendar.
 *	@package		framework.neon.view.component
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.2
 */
/**
 *	Builds HTML for Day Calendar.
 *	@package		framework.neon.view.component
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.2
 */
class DayCalendar extends Framework_Neon_View
{
	/**	@var		string		$format			Format for Input Field */
	protected $format			= "%m-%d-%Y %H:%M";
	/**	@var		bool		$future			Type of Calendar where true is 'future' */
	protected $future			= true;
	/**	@var		int			$range			Range of Years */
	protected $range			= 75;
	
	/**
	 *	Builds Calendar with Opener and Calendar Layer.
	 *	@access		public
	 *	@param		string		$inputId		ID of Input Field
	 *	@param		string		$openerId		ID of Opener
	 *	@return		string
	 */
	public function buildCalendar( $inputId, $openerId )
	{
		$ui['format']		= $this->format;
		$ui['inputId']		= $inputId;
		$ui['openerId']		= $openerId;

		return $this->loadTemplate( 'daycalendar', $ui );
		
//		$template	= new View_Component_Template( 'daycalendar', $ui );
//		$content	= $template->create();
//		return $content;
	}
	
	/**
	 *	Sets Format for Input Field.
	 *	@access		public
	 *	@param		string		$format			Format for Input Field (eg. y/m)
	 *	@return		void
	 */
	public function setFormat( $format )
	{
		$this->format	= $format;
	}

	/**
	 *	Sets Range of Years.
	 *	@access		public
	 *	@param		int			$years			Range of Years
	 *	@return		void
	 */
	public function setRange( $years )
	{
		$this->range	= abs( $years );
	}
	
	/**
	 *	Sets Type to 'future' or 'past'.
	 *	@access		public
	 *	@param		string		$type			Type of Calendar (future|past)
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->future	= $type == "future";
	}
}
?>