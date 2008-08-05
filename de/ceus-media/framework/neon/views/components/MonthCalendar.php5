<?php
/**
 *	Builds HTML for Month Calendar.
 *	@package		framework.neon.view.component
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.03.2007
 *	@version		0.2
 */
/**
 *	Builds HTML for Month Calendar.
 *	@package		framework.neon.view.component
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.03.2007
 *	@version		0.2
 */
class MonthCalendar extends Framework_Neon_View
{
	/**	@var	array		$months			Array of Months */
	var $months	= array(
		'01'	=> "Januar",
		'02'	=> "Februar",
		'03'	=> "März",
		'04'	=> "April",
		'05'	=> "Mail",
		'06'	=> "Juni",
		'07'	=> "Juli",
		'08'	=> "August",
		'09'	=> "September",
		'10'	=> "Oktober",
		'11'	=> "November",
		'12'	=> "Dezember",
	);
	
	/**	@var	string		$format			Format for Input Field */
	var $format	= "m.y";
	/**	@var	int			$year			Year to start from */
	var $year;
	/**	@var	bool		$future			Type of Calendar where true is 'future' */
	var $future	= true;
	/**	@var	bool		$asc			Direction where true is ascending */
	var $asc	= true;
	/**	@var	int			$range			Range of Years */
	var $range	= 75;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->html	= new Elements;
		$this->year	= date( "Y" );
	}
	
	/**
	 *	Builds Calendar with Opener and Calendar Layer.
	 *	@access		public
	 *	@param		string		$id_input	ID of Input Field
	 *	@param		string		$id_mcal	ID of Calendar Layer
	 *	@param		string		$id_opener	ID of Opener
	 *	@param		string		$js_name	Name of JavaScript Instance of UI.MonthCalendar
	 *	@return		string
	 */
	public function buildCalendar( $id_input, $id_mcal, $id_opener, $js_name )
	{
		$opt_year	= $this->buildYears();
		$ui['select_year']	= $this->html->Select( 'year', $opt_year, 'seltiny' );
		foreach( $this->months as $key => $value )
		{
			$link	= $this->html->Link( "javascript:".$js_name.".select('".$key."');", $value );
			$list[]	= $this->html->ListItem( $link );
		}
		$ui['list']	= $this->html->unorderedList( $list );

		$ui['format']	= $this->format;
		$ui['id_input']	= $id_input;
		$ui['id_mcal']	= $id_mcal;
		$ui['id_opener']	= $id_opener;
		$ui['js_name']	= $js_name;

		$template	= new View_Component_Template( 'monthcalendar', $ui );
		$content	= $template->create();
		return $content;
	}

	/**
	 *	Builds  Range of Years.
	 *	@access		protected
	 *	@return		array
	 */
	protected function buildYears()
	{
		$opt_year	= array();
		switch( $this->future )
		{
			case true:
				$start	= $this->year;
				$end	= $this->year+$this->range;
				break;
			default:
				$start	= $this->year-$this->range;
				$end	= $this->year;
				break;
		}
		for( $i=$start; $i<=$end; $i++ )
			$opt_year[$i]	= $i;
		if( !$this->asc )
			krsort( $opt_year );
		return $opt_year;
	
	}
	
	/**
	 *	Sets Direction of Years.
	 *	@access		public
	 *	@param		bool		$asc		Direction where true is ascending
	 *	@return		void
	 */
	public function setDirection( $asc )
	{
		$this->asc	= (bool)$asc;
	}
	
	/**
	 *	Sets Format for Input Field.
	 *	@access		public
	 *	@param		string		$format		Format for Input Field (eg. y/m)
	 *	@return		void
	 */
	public function setFormat( $format )
	{
		$this->format	= $format;
	}

	/**
	 *	Sets new Month Names.
	 *	@access		public
	 *	@param		array		$months		List of Month Names
	 *	@return		void
	 */
	public function setMonths( $months )
	{
		$key	= array_keys( $this->month );
		$months	= array_values( $months );
		$this->month	= array_combine( $key, $months );
	}
	
	/**
	 *	Sets Range of Years.
	 *	@access		public
	 *	@param		int			$years		Range of Years
	 *	@return		void
	 */
	public function setRange( $years )
	{
		$this->range	= abs( $years );
	}
	
	/**
	 *	Sets Type to 'future' or 'past'.
	 *	@access		public
	 *	@param		string		$type		Type of Calendar (future|past)
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->future	= $type == "future";
	}
}
?>