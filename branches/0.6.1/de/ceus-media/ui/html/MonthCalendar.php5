<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Calendar with Month View.
 *	@package		ui
 *	@subpackage		html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.03.2006
 *	@version		0.1
 */
/**
 *	Calendar with Month View.
 *	@package		ui
 *	@subpackage		html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.03.2006
 *	@version		0.1
 */
class MonthCalendar extends ADT_OptionObject
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
		
		$this->setOption( 'url', "?" );

		$this->html	= new UI_HTML_Elements;
	}
	
	/**
	 *	Builds Output of Month Calendar.
	 *	@access		public
	 *	@param		string		$cell_class		CSS Class of Cell
	 *	@param		bool		$heading_span	Flag: Span Heading over whole Table
	 *	@return		string
	 */
	public function buildCalendar( $cell_class = "", $heading_span = false )
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
				$line	= implode( "\n\t\t", $cells );
			}
			$lines[]	= "<tr>\n\t\t".$line."\n\t  </tr>";
		}
		$lines		= implode( "\n\t  ", $lines );
		$heading	= $this->buildHeading( $heading_span );
		$days		= $this->buildWeekDays();
		$code		= $this->getCode( $heading, $days, $lines );
		return $code;
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
	
	/**
	 *	Builds Table Cell for one Day.
	 *	@access		protected
	 *	@param		int			$day		Number of Day
	 *	@param		string		$class		Class of Cell
	 *	@return		string
	 */
	protected function buildCell( $day, $class = "")
	{
		$classes	= array();
		if(	$this->getOption( 'current_year' ) == $this->getOption( 'show_year' ) &&
			$this->getOption( 'current_month' ) == $this->getOption( 'show_month' ) &&
			$this->getOption( 'current_day' ) == $day )
			$classes[]	= 'current';
		if( $class )
			$classes[]	= $class;
		if( $day )
		{
			$classes[]	= 'day';
			$content		= $this->modifyDay( $day );
			$day		= $content['day'];
			if( isset( $content['class'] ) && $content['class'] )
				$classes[]	= $content['class'];
		}
		else
			$day	= "&nbsp;";
		if( count( $classes ) )
			$class	 = " class='".implode( " ", $classes )."'";
		$code	= "<td".$class.">".$day."</td>";
		return $code;
	}
	
	/**
	 *	Builds Table Heading with Month, Year and Links for Selection.
	 *	@access		protected
	 *	@param		int			$day		Number of Day
	 *	@param		string		$class		Class of Cell
	 *	@return		string
	 */
	protected function buildHeading( $span = false )
	{
		$month	= (int)$this->getOption( 'show_month' );
		$year	= (int)$this->getOption( 'show_year' );
		$prev_month	= $month - 1;
		$next_month	= $month + 1;
		$prev_year	= $next_year	= $year;

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
		if( $this->getOption( 'ajax_request' ) )
		{
			$prev	= "<a href='#' onClick=\"ajax.get( '".$this->getOption( 'ajax_request' )."?".$this->getOption( 'carrier_year' )."=".$prev_year."&".$this->getOption( 'carrier_month' )."=".$prev_month.$this->getOption( 'request_query' )."', 'processCalendar' );\">&lt;</a>";
			$next	= "<a href='#' onClick=\"ajax.get( '".$this->getOption( 'ajax_request' )."?".$this->getOption( 'carrier_year' )."=".$next_year."&".$this->getOption( 'carrier_month' )."=".$next_month.$this->getOption( 'request_query' )."', 'processCalendar' );\">&gt;</a>";
		}
		else
		{
			$prev	= $this->html->Link( $this->getOption( 'url' )."&".$this->getOption( 'carrier_year' )."=".$prev_year."&".$this->getOption( 'carrier_month' )."=".$prev_month, "&lt;" );
			$next	= $this->html->Link( $this->getOption( 'url' )."&".$this->getOption( 'carrier_year' )."=".$next_year."&".$this->getOption( 'carrier_month' )."=".$next_month, "&gt;" );
		}
		$code	= "<tr>
		<th width='14%'>".$prev."</th>
		<th width='72%'".$colspan.">".htmlspecialchars( $month )." ".$year."</th>
		<th width='14%'>".$next."</th>
	  </tr>";
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
			$days[]	= "<td>".$day."</td>";
		$days	= implode( "\n\t", $days );
		$code	= "\n\t<tr>".$days."</tr>";
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
			$content	= "
<table class='calendar' cellspacing='0'>
  <tr>
    <th>
	<table class='selector' cellspacing='0' width='100%'>
	  ".$heading."
	</table>
    </th>
  </tr>
  <tr>
    <td>
	<table class='days' cellspacing='0' width='100%'>
	  ".$weekdays."
	</table>
    </th>
  </tr>
  <tr>
    <td>
	<table class='weeks' cellspacing='0' width='100%'>
	  ".$weeks."
	</table>
    </td>
  </tr>
</table>
";
		return $content;
	}

	/**
	 *	Modification of Cell Content of Days - to be overwritten.
	 *	@access		protected
	 *	@param		string		$day			Number of Day to modify
	 *	@return 	string
	 */
	protected function modifyDay( $day )
	{
		return array( 'day'	=> $day, 'class'	=> '' );
	}
}
?>