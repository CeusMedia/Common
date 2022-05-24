<?php
/**
 *	Visualisation of Exception.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */

namespace CeusMedia\Common\UI\HTML\Exception;

use CeusMedia\Common\Exception\SQL as SqlException;
use CeusMedia\Common\Exception\Logic as LogicException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\UI\HTML\Tag;
use CeusMedia\Common\XML\ElementReader as XmlElementReader;
use Exception;

/**
 *	Visualisation of Exception Stack Trace.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class View
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
		$root	= XmlElementReader::readFile( dirname( __FILE__ ).'/SQLSTATE.xml' );

		$query	= 'class[@id="'.$class1.'"]/subclass[@id="000"]';
		$result	= $root->xpath( $query );
		$class	= array_pop( $result );
		if( $class ){
			$query		= 'class[@id="'.$class1.'"]/subclass[@id="'.$class2.'"]';
			$result		= $root->xpath( $query );
			$subclass	= array_pop( $result );
			if( $subclass )
				return $class->getAttribute( 'meaning' ).' - '.$subclass->getAttribute( 'meaning' );
			return $class->getAttribute( 'meaning' );
		}
		return '';
	}

	public static function render( Exception $e, $showTrace = TRUE, $showPrevious = TRUE )
	{
		$list	= array();

		$msg	= htmlentities( $e->getMessage(), ENT_COMPAT, 'UTF-8' );
		$list[]	= Tag::create( 'dt', 'Message', array( 'class' => 'exception-message' ) );
		$list[]	= Tag::create( 'dd', $msg, array( 'class' => 'exception-message' ) );

		if( (int) $e->getCode() !== 0 ){
			$code	= htmlentities( $e->getCode(), ENT_COMPAT, 'UTF-8' );
			$list[]	= Tag::create( 'dt', 'Code', array( 'class' => 'exception-code' ) );
			$list[]	= Tag::create( 'dd', $code, array( 'class' => 'exception-code' ) );
		}

		if( $e instanceof SqlException && $e->getSQLSTATE() ){
			$meaning	= self::getMeaningOfSQLSTATE( $e->getSQLSTATE() );
			$list[]	= Tag::create( 'dt', 'SQLSTATE', array( 'class' => 'exception-code-sqlstate' ) );
			$list[]	= Tag::create( 'dd', $e->getSQLSTATE().': '.$meaning, array( 'class' => 'exception-code-sqlstate' ) );
		}
		if( $e instanceof IoException  ){
			$list[]	= Tag::create( 'dt', 'Resource', array( 'class' => 'exception-resource' ) );
			$list[]	= Tag::create( 'dd', $e->getResource(), array( 'class' => 'exception-resource' ) );
		}
		if( $e instanceof LogicException ){
			$list[]	= Tag::create( 'dt', 'Subject', array( 'class' => 'exception-subject' ) );
			$list[]	= Tag::create( 'dd', $e->getSubject(), array( 'class' => 'exception-subject' ) );
		}

		$list[]	= Tag::create( 'dt', 'Type', array( 'class' => 'exception-type' ) );
		$list[]	= Tag::create( 'dd', get_class( $e ), array( 'class' => 'exception-type' ) );

		$pathName	= self::trimRootPath(  $e->getFile() );
		$fileName	= '<span class="file">'.pathinfo( $pathName, PATHINFO_FILENAME ).'</span>';
		$extension	= pathinfo( $pathName, PATHINFO_EXTENSION );
		$extension	= '<span class="ext">'.( $extension ? '.'.$extension : '' ).'</span>';
		$path		= '<span class="path">'.dirname( $pathName ).'/</span>';
		$file		= $path.$fileName.$extension;

		$list[]	= Tag::create( 'dt', 'File', array( 'class' => 'exception-file' ) );
		$list[]	= Tag::create( 'dd',$file, array( 'class' => 'exception-file' ) );

		$list[]	= Tag::create( 'dt', 'Line', array( 'class' => 'exception-line' ) );
		$list[]	= Tag::create( 'dd', (string) $e->getLine(), array( 'class' => 'exception-line' ) );

		if( $showTrace )
		{
			$trace	= Trace::render( $e );
			if( $trace )
			{
				$list[]	= Tag::create( 'dt', 'Trace' );
				$list[]	= Tag::create( 'dd', $trace );
			}
		}
		if( $showPrevious )
		{
			if( method_exists( $e, 'getPrevious' ) && $e->getPrevious() )
			{
				$list[]	= Tag::create( 'dt', 'Previous' );
				$list[]	= Tag::create( 'dd', View::render( $e->getPrevious() ) );
			}
		}
		return Tag::create( 'dl', join( $list ), array( 'class' => 'exception' ) );
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
