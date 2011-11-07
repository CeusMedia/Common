<?php
/**
 *	Visualisation of Exception.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		UI.HTML.Exception
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Visualisation of Exception Stack Trace.
 *	@category		cmClasses
 *	@package		UI.HTML.Exception
 *	@uses			Alg_Text_Trimmer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Exception_View
{
	/**
	 *	Prints exception view.
	 *	@access		public
	 *	@param		Exception	$exception		Exception
	 *	@return		void
	 */
	public static function display( Exception $e )
	{
		print self::render( $e );
	}

	/**
	 *	Resolves SQLSTATE Code and returns its Meaning.
	 *	@access		protected
	 *	@static
	 *	@return		string
	 *	@see		http://developer.mimer.com/documentation/html_92/Mimer_SQL_Mobile_DocSet/App_Return_Codes2.html
	 *	@see		http://publib.boulder.ibm.com/infocenter/idshelp/v10/index.jsp?topic=/com.ibm.sqls.doc/sqls520.htm
	 */
	protected static function getMeaningOfSQLSTATE( $SQLSTATE )
	{
		$class1	= substr( $SQLSTATE, 0, 2 );
		$class2	= substr( $SQLSTATE, 2, 3 );
		$root	= XML_ElementReader::readFile( dirname( __FILE__ ).'/SQLSTATE.xml' );

		$query	= 'class[@id="'.$class1.'"]/subclass[@id="000"]';
		$class	= array_pop( $root->xpath( $query ) );
		if( $class ){
			$query		= 'class[@id="'.$class1.'"]/subclass[@id="'.$class2.'"]';
			$subclass	= array_pop( $root->xpath( $query ) );
			if( $subclass )
				return $class->getAttribute( 'meaning' ).' - '.$subclass->getAttribute( 'meaning' );	
			return $class->getAttribute( 'meaning' );	
		}
		return '';
	}

	public static function render( Exception $e )
	{
		$list	= array();
		$list[]	= UI_HTML_Tag::create( 'dt', 'Message', array( 'class' => 'exception-message' ) );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getMessage(), array( 'class' => 'exception-message' ) );

		$list[]	= UI_HTML_Tag::create( 'dt', 'Code', array( 'class' => 'exception-code' ) );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getCode(), array( 'class' => 'exception-code' ) );

		if( $e instanceof Exception_SQL && $e->getSQLSTATE() ){
			$meaning	= self::getMeaningOfSQLSTATE( $e->getSQLSTATE() );
			$list[]	= UI_HTML_Tag::create( 'dt', 'SQLSTATE', array( 'class' => 'exception-code-sqlstate' ) );
			$list[]	= UI_HTML_Tag::create( 'dd', $e->getSQLSTATE().': '.$meaning, array( 'class' => 'exception-code-sqlstate' ) );
		}
		if( $e instanceof Exception_IO && $e->getResource() ){
			$list[]	= UI_HTML_Tag::create( 'dt', 'Resource', array( 'class' => 'exception-resource' ) );
			$list[]	= UI_HTML_Tag::create( 'dd', $e->getResource(), array( 'class' => 'exception-resource' ) );
		}
		if( $e instanceof Exception_Logic && $e->getResource() ){
			$list[]	= UI_HTML_Tag::create( 'dt', 'Subject', array( 'class' => 'exception-subject' ) );
			$list[]	= UI_HTML_Tag::create( 'dd', $e->getSubject(), array( 'class' => 'exception-subject' ) );
		}

		$list[]	= UI_HTML_Tag::create( 'dt', 'Type', array( 'class' => 'exception-type' ) );
		$list[]	= UI_HTML_Tag::create( 'dd', get_class( $e ), array( 'class' => 'exception-type' ) );

		$list[]	= UI_HTML_Tag::create( 'dt', 'File', array( 'class' => 'exception-file' ) );
		$list[]	= UI_HTML_Tag::create( 'dd', self::trimRootPath( $e->getFile() ), array( 'class' => 'exception-file' ) );

		$list[]	= UI_HTML_Tag::create( 'dt', 'Line', array( 'class' => 'exception-line' ) );
		$list[]	= UI_HTML_Tag::create( 'dd', $e->getLine(), array( 'class' => 'exception-line' ) );

		$trace	= new UI_HTML_Exception_Trace( $e );
		if( $trace )
		{
			$list[]	= UI_HTML_Tag::create( 'dt', 'Trace' );
			$list[]	= UI_HTML_Tag::create( 'dd', $trace->render() );
		}
		if( method_exists( $e, 'getPrevious' ) && $e->getPrevious() )
		{
			$list[]	= UI_HTML_Tag::create( 'dt', 'Previous' );
			$list[]	= UI_HTML_Tag::create( 'dd', UI_HTML_Exception_View::render( $e->getPrevious() ) );
		}
		return UI_HTML_Tag::create( 'dl', join( $list ), array( 'class' => 'exception' ) );
	}


	/**
	 *	Removes Document Root in File Names.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name to clear
	 *	@return		string
	 */
	protected static function trimRootPath( $fileName )
	{
		$rootPath	= isset( $_SERVER['DOCUMENT_ROOT'] ) ? $_SERVER['DOCUMENT_ROOT'] : "";
		if( !$rootPath || !$fileName )
			return;
		$fileName	= str_replace( '\\', "/", $fileName );
		$cut		= substr( $fileName, 0, strlen( $rootPath ) );
		if( $cut == $rootPath )
			$fileName	= substr( $fileName, strlen( $rootPath ) );
		return $fileName;
	}
}
?>