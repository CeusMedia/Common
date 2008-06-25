<?php
import( 'de.ceus-media.file.csv.Iterator' );
/**
 *	Reads CSV Files using the File_CSV_Iterator.
 *	@package		file.csv
 *	@uses			File_CSV_Iterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2007
 *	@version		0.1
 */
/**
 *	Reads CSV Files using the File_CSV_Iterator.
 *	@package		file.csv
 *	@uses			File_CSV_Iterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2007
 *	@version		0.1
 */
class File_CSV_IteratorReader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of CSV File
	 *	@param		string		$delimiter		Delimiter between Information
	 *	@return		void
	 */
	public function __construct( $fileName, $delimiter = NULL )
	{
		$this->iterator	= new File_CSV_Iterator( $fileName, $delimiter );	
	}
	
	/**
	 *	Returns CSV Data as Array or associative Array.
	 *	@access		public
	 *	@param		bool		$useHeaders		Flag: use first Line as Headers and return associative Array
	 *	@return		array
	 */
	public function toArray( $useHeaders = false )
	{
		$list	= array();
		if( $useHeaders )
		{
			$headers	= $this->iterator->next();
			while( $data = $this->iterator->next() )
			{
				$list[]	= array_combine( $headers, $data );
			}
		}
		else
		{
			while( $list[] = $this->iterator->next() );
		}
		return $list;
	}
}
?>