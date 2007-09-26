<?php
import( 'net.sourceforge.html2fpdf.HTML2FPDF' );
import( 'de.ceus-media.file.File' );
/**
 *	Transformes HTML to PDF.
 *	@package		ui
 *	@subpackage		html
 *	@uses			HTML2FPDF
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.10.2006
 *	@version		0.1
 */
/**
 *	Transformes HTML to PDF.
 *	@package		ui
 *	@subpackage		html
 *	@uses			HTML2FPDF
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.10.2006
 *	@version		0.1
 */
class HTML2PDF
{
	/**	@var		HTML2FPDF	$_html2fpdf	Instance of HTML2FPDF for Transformation */
	var $_html2fpdf;
	/**	@var		bool			$_loaded		Flag: HTML is loaded an can be transformed to Text */
	var $_loaded;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_html2fpdf	= new HTML2FPDF();
		$this->_html2fpdf->UseCSS();
		$this->_html2fpdf->DisableTags();
		$this->_loaded	= false;
	}

	/**
	 *	Loads HTML from HTML File.
	 *	@access		public
	 *	@param		string		$filename		File Name of HTML File
	 *	@return		bool
	 */
	function loadHTML( $filename )
	{
		if( file_exists( $filename ) )
		{
			$html_file		= new File( $filename );
			$html		= $html_file->readString();
			$this->setHTML( $html );
			return true;
		}
		return false;
	}

	/**
	 *	Sets HTML for Transformation.
	 *	@access		public
	 *	@param		string		$html		HTML to transform
	 *	@return		void
	 */
	function setHTML( $html )
	{
		$this->_html2fpdf->AddPage();
		$this->_html2fpdf->WriteHTML( $html );
		$this->_loaded	= true;
	}

	/**
	 *	Writes built PDF to File.
	 *	@access		public
	 *	@param		string		$filename		File Name of PDF File
	 *	@return		void
	 */
	function writePDF( $filename )
	{
		if( !$this->_loaded )
			trigger_error( 'No HTML loaded', E_USER_ERROR );
		$this->_html2fpdf->Output( $filename );	
	}

	/**
	 *	Delivers built PDF as Download.
	 *	@access		public
	 *	@param		string		$filename		File Name of PDF File
	 *	@return		void
	 */
	function downloadPDF( $filename )
	{
		if( !$this->_loaded )
			trigger_error( 'No HTML loaded', E_USER_ERROR );
		$this->_html2fpdf->Output( $filename, "D" );	
	}
	
	/**
	 *	Returns built PDF to Browser.
	 *	@access		public
	 *	@return		void
	 */
	function showPDF()
	{
		if( !$this->_loaded )
			trigger_error( 'No HTML loaded', E_USER_ERROR );
		$this->_html2fpdf->Output();	
	}
}
?>