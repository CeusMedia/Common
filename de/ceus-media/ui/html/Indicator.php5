<?php
/**
 *	Builds HTML of Bar Indicator.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Builds HTML of Bar Indicator.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class UI_HTML_Indicator
{
	/**	@var		string		$classIndicator			CSS Class of Indicator Block */
	public $classIndicator		= "indicator";
	/**	@var		string		$classIndicator			CSS Class of inner Block */
	public $classInner			= "indicator-inner";
	/**	@var		string		$classIndicator			CSS Class of outer Block */
	public $classOuter			= "indicator-outer";
	/**	@var		string		$classPercentage		CSS Class of Percentage Block */
	public $classPercentage		= "indicator-percentage";

	/**
	 *	Builds HTML of Indicator.
	 *	@access		public
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@param		int			$length		Length of inner Indicator Bar
	 *	@return		string
	 */
	public function build( $found, $count, $length = 200 )
	{
		$ratio	= $found / $count;
		$length	= floor( $ratio * $length );

		$colorR	= ( 1 - $ratio ) > 0.5 ? 255 : round( ( 1 - $ratio ) * 2 * 255 );
		$colorG	= $ratio > 0.5 ? 255 : round( $ratio * 2 * 255 );
		$colorB	= "0";

		return '<div class="'.$this->classIndicator.'">
	 <div class="'.$this->classOuter.'">
	   <div class="'.$this->classInner.'" style="width:'.$length.'px; background-color: rgb('.$colorR.','.$colorG.','.$colorB.')">
	   </div>
	 </div>
	 <div class="'.$this->classPercentage.'">
	   '.floor( $ratio * 100 ).' %
	 </div>
	</div>';
	}

	/**
	 *	Returns CSS Class of Indicator DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getIndicatorClass()
	{
		return $this->classIndicator;
	}

	/**
	 *	Returns CSS Class of inner DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getInnerClass()
	{
		return $this->classInner;
	}

	/**
	 *	Returns CSS Class of outer DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getOuterClass()
	{
		return $this->classOuter;
	}

	/**
	 *	Returns CSS Class of Percentage DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getPercentageClass()
	{
		return $this->classPercentage;
	}

	/**
	 *	Sets CSS Class of Indicator DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setIndicatorClass( $class )
	{
		$this->classIndicator	= $class;
	}

	/**
	 *	Sets CSS Class of inner DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setInnerClass( $class )
	{
		$this->classInner	= $class;
	}

	/**
	 *	Sets CSS Class of outer DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setOuterClass( $class )
	{
		$this->classOuter	= $class;
	}

	/**
	 *	Sets CSS Class of Percentage DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setPercentageClass( $class )
	{
		$this->classPercentage	= $class;
	}
}
?>