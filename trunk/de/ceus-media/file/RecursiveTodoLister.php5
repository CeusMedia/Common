<?php
import( 'de.ceus-media.file.TodoLister' );
import( 'de.ceus-media.file.RecursiveRegexFilter' );
/**
 *	Class to find all Files with ToDos inside.
 *	@package		file
 *	@extends		File_TodoLister
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			11.06.2008
 *	@version		0.1
 */
/**
 *	Class to find all Files with ToDos inside.
 *	@package		file
 *	@extends		File_TodoLister
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			11.06.2008
 *	@version		0.1
 */
class File_RecursiveTodoLister extends File_TodoLister
{
	protected function getIndexIterator( $path, $filePattern, $contentPattern = NULL )
	{
		return new File_RecursiveRegexFilter( $path, $filePattern, $contentPattern );
	}
}
?>