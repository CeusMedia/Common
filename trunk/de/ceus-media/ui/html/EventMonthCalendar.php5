<?php
import( 'de.ceus-media.ui.html.MonthCalendar' );
/**
 *	Generator for Month Calendar with Events.
 *	@package	ui
 *	@subpackage	html
 *	@extends	MonthCalendar
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		20.03.06
 *	@version		0.1
 */
/**
 *	Generator for Month Calendar with Events.
 *	@package	ui
 *	@subpackage	html
 *	@extends	MonthCalendar
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		20.03.06
 *	@version		0.1
 */
class EventMonthCalendar extends MonthCalendar
{
	/**	@var	array		$events			Array of Days with Events */
	protected $events	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 *	@todo		remove Month Names and fix StaubSauger/Share
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( 'carrier_day', "day" );
		$months	= array(
			"Januar",
			"Februar",
			"März",
			"April",
			"Mai",
			"Juni",
			"Juli",
			"August",
			"September",
			"Oktober",
			"November",
			"Dezember"
			);
		$this->setMonthNames( $months );
		$days	= array(
			"Mo",
			"Di",
			"Mi",
			"Do",
			"Fr",
			"Sa",
			"So"
			);
		$this->setDayNames( $days );
	}
	
	/**
	 *	Sets Day to show.
	 *	@access		public
	 *	@param		int			$day			Day to show.
	 *	@return		void
	 */
	public function setDay( $day )
	{
		$this->setOption( 'show_day', $day );
	}
	
	/**
	 *	Sets Events for Links.
	 *	@access		public
	 *	@param		array		$events		Array of Days with Events
	 *	@return		void
	 */
	public function setEvents( $events )
	{
		$this->events	= $events;
	}
	
	//  -- PRIVATE METHODS  --  //
	/**
	 *	Modification of Cell Content of Days - to be overwritten.
	 *	@access		protected
	 *	@param		string		$day		Number of Day to modify
	 *	@return 	string
	 */
	protected function modifyDay( $day )
	{
		$classes	= array();
		if( (int)$this->getOption( 'show_day' ) == $day )
			$classes[]	= 'shown';
		if( in_array( $day, $this->events ) )
		{
			$url	= $this->getOption( 'url' );
			$url	.= "&".$this->getOption( 'carrier_year' )."=".$this->getOption( 'show_year' );
			$url	.= "&".$this->getOption( 'carrier_month' )."=".$this->getOption( 'show_month' );
			$url	.= "&".$this->getOption( 'carrier_day' )."=".$day;
			$day	= $this->html->Link( $url, $day );
			$classes[]	= 'event';
		}
		$class	= implode( ' ', $classes );
		return $data	= array( 'day'	=> $day, 'class' => $class );		
	}
}
?>