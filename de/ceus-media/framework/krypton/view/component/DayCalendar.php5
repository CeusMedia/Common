<?php
/**
 *	Builds HTML for Day Calendar.
 *	@package		mv2.view.component
 *	@extends		Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.1
 */
/**
 *	Builds HTML for Day Calendar.
 *	@package		mv2.view.component
 *	@extends		Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.1
 */
class View_Component_DayCalendar extends Core_View
{
	/**	@var	string		$format			Format for Input Field */
	var $format		= "%m-%d-%Y %H:%M";
	/**	@var	bool		$future			Type of Calendar where true is 'future' */
	var $future		= true;
	/**	@var	int			$range			Range of Years */
	var $range		= 75;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *	Builds Calendar with Opener and Calendar Layer.
	 *	@access		public
	 *	@param		string		$id_input	ID of Input Field
	 *	@param		string		$id_opener	ID of Opener
	 *	@return		string
	 */
	public function buildCalendar( $id_input, $id_opener )
	{
        $config = $this->registry->get('config');
        $ui['account']      = $config['application']['account'];
        $ui['format']		= $this->format;
		$ui['id_input']		= $id_input;
		$ui['id_opener']	= $id_opener;

		return $this->loadTemplate( 'daycalendar', $ui );
		
//		$template	= new View_Component_Template( 'daycalendar', $ui );
//		$content	= $template->create();
//		return $content;
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