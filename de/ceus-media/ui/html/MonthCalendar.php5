<?php
/**
 *	Calendar with Month View.
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
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.03.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Calendar with Month View.
 *	@category		cmClasses
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.03.2006
 *	@version		$Id$
 */
class UI_HTML_MonthCalendar extends ADT_OptionObject
{
	/**	@var	array	months		Array of Month Names */
	protected $months	= array(
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
	);

	/**	@var	array	days		Array of Day Names */
	protected $days	= array(
		"Mo",
		"Tu",
		"We",
		"Thu",
		"Fr",
		"Sa",
		"Su"
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setYear( date( "Y" ) );
		$this->setMonth( date( "m" ) );
		
		$this->setOption( 'carrier_year', "year" );
		$this->setOption( 'carrier_month', "month" );

		$this->setOption( 'current_year', date( "Y" ) );
		$this->setOption( 'current_month', date( "m" ) );
		$this->setOption( 'current_day', date( "j" ) );
		
		$this->setOption( 'url', "./?" );
		$this->setOption( 'template', NULL );

		$this->html	= new UI_HTML_Elements;
	}
	
	/**
	 *	Builds Output of Month Calendar.
	 *	@access		public
	 *	@param		string		$cell_class		CSS Class of Cell
	 *	@param		bool		$heading_span	Flag: Span Heading over whole Table
	 *	@return		string
	 */
	public function buildCalendar( $cell_class = NULL, $heading_span = NULL )
	{
		$time	= mktime( 0, 0, 0, $this->getOption( 'show_month'), 1, $this->getOption( 'show_year' ) );
		$offset	= date( 'w', $time )-1;
		if( $offset < 0 )
			$offset += 7;
		$days	= date( "t", $time );
		$weeks	= ceil( ( $days + $offset ) / 7 );
		$d	= 1;
		$lines	= array();
		for( $w=0; $w<$weeks; $w++ )
		{
			$cells	= array();
			for( $i=0; $i<7; $i++ )
			{
				if( $offset )
				{
					$cells[]	= $this->buildCell( 0, $cell_class );
					$offset --;
				}
				else if( $d > $days )
					$cells[]	= $this->buildCell( 0, $cell_class );
				else
				{
					$cells[]	= $this->buildCell( $d, $cell_class );
					$d++;
				}
				$line	= implode( "", $cells );
			}
			$lines[]	= UI_HTML_Tag::create( "div", $line, array( 'class' => 'day-week' ) );
		}
		$lines		= implode( "\n\t  ", $lines );
		$days		= UI_HTML_Tag::create( "div", $lines, array( 'class' => "days" ) );
		$heading	= $this->buildHeading( $heading_span );
		$weekdays	= $this->buildWeekDays();
		$code		= $this->getCode( $heading, $weekdays, $days );
		return $code;
	}
	
	/**
	 *	Builds Table Cell for one Day.
	 *	@access		protected
	 *	@param		int			$day		Number of Day
	 *	@param		string		$class		Class of Cell
	 *	@return		string
	 */
	protected function buildCell( $day, $class = "")
	{
		$classes	= array( 'day' );
		if(	$this->getOption( 'current_year' ) == $this->getOption( 'show_year' ) &&
			$this->getOption( 'current_month' ) == $this->getOption( 'show_month' ) &&
			$this->getOption( 'current_day' ) == $day )
			$classes[]	= 'current';
		if( $class )
			$classes[]	= $class;
		if( $day )
		{
			$content	= $this->modifyDay( $day );
			$day		= $content['day'];
			if( isset( $content['class'] ) && $content['class'] )
				$classes[]	= $content['class'];
		}
		else
			$day	= "&nbsp;";
		$code	= UI_HTML_Tag::create( "div", $day, array( 'class' => implode( " ", $classes ) ) );
		return $code;
	}
	
	/**
	 *	Builds Table Heading with Month, Year and Links for Selection.
	 *	@access		protected
	 *	@param		int			$day		Number of Day
	 *	@param		string		$class		Class of Cell
	 *	@return		string
	 */
	protected function buildHeading( $span = NULL )
	{
		$month	= (int) $this->getOption( 'show_month' );
		$year	= (int) $this->getOption( 'show_year' );
		$prev_month	= $month - 1;
		$next_month	= $month + 1;
		$prev_year	= $next_year = $year;

		$month	= $this->months[$month - 1];
		if( $prev_month < 1 )
		{
			$prev_month	= 12;
			$prev_year--;
		}
		else if( $next_month > 12 )
		{
			$next_month	= 1;
			$next_year++;
		}
		$colspan	= "";
		if( $span )
			$colspan	= " colspan='5'";
		$url	= $this->getOption( 'url' )."&".$this->getOption( 'carrier_year' )."=".$prev_year."&".$this->getOption( 'carrier_month' )."=".$prev_month;
		$prev	= UI_HTML_Elements::Link( $url, "&lt;" );
		$url	= $this->getOption( 'url' )."&".$this->getOption( 'carrier_year' )."=".$next_year."&".$this->getOption( 'carrier_month' )."=".$next_month;
		$next	= UI_HTML_Elements::Link( $url, "&gt;" );

		$left	= UI_HTML_Tag::create( "div", $prev, array( 'class' => "go-left" ) );
		$right	= UI_HTML_Tag::create( "div", $next, array( 'class' => "go-right" ) );
		$label	= UI_HTML_Tag::create( "div", htmlspecialchars( $month ).' '.$year, array( 'class' => "label" ) );
		$code	= UI_HTML_Tag::create( "div", $left.$label.$right, array( 'class' => 'month' ) );
		return $code;
	}
	
	/**
	 *	Builds Table Row with Week Days.
	 *	@access		protected
	 *	@return		string
	 */
	protected function buildWeekDays()
	{
		foreach( $this->days as $day )
			$days[]	= UI_HTML_Tag::create( "div", $day, array( 'class' => "weekday" ) );
		$days	= implode( "", $days );
		$code	= UI_HTML_Tag::create( "div", $days, array( 'class' => "weekdays" ) );
		return $code;
	}
	
	/**
	 *	Builds Output Code by loading a Template or with built-in Template.
	 *	@access		protected
	 *	@param		string		$heading		Table Row of Heading
	 *	@param		string		$weekdays		Table Row of Weekdays
	 *	@param		string		$weeks			Table Rows of Weeks
	 *	@return		string
	 */
	protected function getCode( $heading, $weekdays, $weeks )
	{
		if( $template = $this->getOption( 'template' ) )
		{
			$options	= $this->getOptions();
			require( $template );
		}
		else
		{
			$clearFix	= UI_HTML_Tag::create( 'div', "", array( 'style' => 'clear: both' ) );
			$content	= UI_HTML_Tag::create( 'div', $heading.$weekdays.$weeks.$clearFix, array( 'class' => 'calendar' ) );
		}
		return $content;
	}
	
	/**
	 *	Sets Month to show.
	 *	@access		public
	 *	@param		int			$year			Year with 4 digits
	 *	@return		void
	 */
	public function getMonth()
	{
		$this->getOption( 'show_month' );
	}

	/**
	 *	Sets Year to show.
	 *	@access		public
	 *	@param		int			$year			Year with 4 digits
	 *	@return		void
	 */
	public function getYear()
	{
		$this->getOption( 'show_year' );
	}

	/**
	 *	Modification of Cell Content of Days - to be overwritten.
	 *	@access		protected
	 *	@param		string		$day			Number of Day to modify
	 *	@return 	string
	 */
	protected function modifyDay( $day )
	{
		return array(
			'day'	=> $day,
			'class'	=> ''
		);
	}
	
	/**
	 *	Sets Weekday Names.
	 *	@access		public
	 *	@param		array		$name			List of Day Names
	 *	@return		void
	 */
	public function setDayNames( $names )
	{
		$this->days	= $names;
	}
	
	/**
	 *	Sets Month to show.
	 *	@access		public
	 *	@param		int			$month			Number of Month (1-12)
	 *	@return		void
	 */
	public function setMonth( $month )
	{
		$this->setOption( 'show_month',	$month );
	}
	
	/**
	 *	Sets Month Names.
	 *	@access		public
	 *	@param		array		$name			List of Month Names
	 *	@return		void
	 */
	public function setMonthNames( $names )
	{
		$this->months	= $names;
	}
	
	/**
	 *	Sets Template to use.
	 *	@access		public
	 *	@param		string		$template		Path and File Name of Template to use for Calendar Output
	 *	@return		void
	 */
	public function setTemplate( $template )
	{
		$this->setOption( 'template', $template );
	}
	
	/**
	 *	Sets Year to show.
	 *	@access		public
	 *	@param		int			$year			Year with 4 digits
	 *	@return		void
	 */
	public function setYear( $year )
	{
		$this->setOption( 'show_year',	$year );
	}
}
?>