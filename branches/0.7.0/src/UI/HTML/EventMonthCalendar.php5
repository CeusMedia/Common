<?php
/**
 *	Generator for Month Calendar with Events.
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
 *	@package		UI.HTML
 *	@extends		UI_HTML_MonthCalendar
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.06
 *	@version		$Id$
 */
import( 'de.ceus-media.ui.html.MonthCalendar' );
/**
 *	Generator for Month Calendar with Events.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		UI_HTML_MonthCalendar
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.06
 *	@version		$Id$
 */
class UI_HTML_EventMonthCalendar extends UI_HTML_MonthCalendar
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
		$this->setDay( date( "j" ) );
	}
	
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
}
?>