<?php
/**
 *	Builds HTML for Day Calendar.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.1
 */
/**
 *	Builds HTML for Day Calendar.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			17.03.2007
 *	@version		0.1
 */
class Framework_Krypton_View_Component_DayCalendar extends Framework_Krypton_Core_View
{
	/**	@var		string		$format			Format for Input Field */
	var $format		= "%m-%d-%Y %H:%M";
	/**	@var		bool		$type			Type of Calendar (-1:past|1:future) */
	var $type		= 1;
	/**	@var		int			$range			Range of Years */
	var $range		= 75;
	
	/**
	 *	Builds Calendar with Opener and Calendar Layer.
	 *	@access		public
	 *	@param		string		$id_input	ID of Input Field
	 *	@param		string		$id_opener	ID of Opener
	 *	@return		string
	 */
	public function buildCalendar( $id_input, $id_opener )
	{
		$ui['format']		= $this->format;
		$ui['id_input']		= $id_input;
		$ui['id_opener']	= $id_opener;

		$ui['range_start']	= (int)date( "Y" ) - 2;
		$ui['range_stop']	= (int)date( "Y" ) + 2;

		if( $this->range && $this->type != 0 )
		{
			if( $this->type	== -1 )
			{
				$ui['range_start']	= (int)date( "Y" ) - $this->range;
				$ui['range_stop']	= date( "Y" );
			}
			if( $this->type	== 1 )
			{
				$ui['range_start']	= date( "Y" );
				$ui['range_stop']	= (int)date( "Y" ) + $this->range;
			}
		}
		return $this->loadTemplate( 'daycalendar', $ui );
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
	 *	@param		int		$type		Type of Calendar (-1:past|1:future)
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->type	= $type;
	}
}
?>
