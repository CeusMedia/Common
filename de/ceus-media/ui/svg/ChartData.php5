<?php
/**
 *	This class represents an object in a chart, i.e. a line in a line diagram, a piece of pie in a 
 *	pie chart and so on.
 *	@package Chart
 */
class UI_SVG_ChartData
{
	/**
	 *	Description of the data object.
	 *	@var string
	 *	@access public
	 */
	var $desc;
	
	/**
	 *	Value of the data object.
	 *	@var float
	 *	@access public
	 */
	var $value;
	
	/**
	 * 	The constructor.
	 *	It receives the description o the data, not needed, but for some chart types useful,
	 *	and, as a float, the value of the data.
	 *	@param		string		Description
	 *	@param		float		Value
	 *	@return		void
	 */
	public function __construct( $value, $desc = "" )
	{
		$this->desc = $desc;
		$this->value = $value;
	}
}
?>