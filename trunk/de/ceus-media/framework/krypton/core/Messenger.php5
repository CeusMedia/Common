<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Message Output Handler within a Session.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		framework.krypton.core
 *	@uses			Core_Registry
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		0.6
 */
/**
 *	Message Output Handler within a Session.
 *	@package		framework.krypton.core
 *	@uses			Core_Registry
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		0.6
 */
class Framework_Krypton_Core_Messenger
{
	/**	@var	object			$registry			Registry for Objects */
	protected $registry;
	/**	@var	array			$classes			CSS Classes of Message Types */
	protected $classes	= array(
		'0'	=> 'failure',
		'1'	=> 'error',
		'2'	=> 'notice',
		'3'	=> 'success',
		);

	/**	@var		string		$headingSeparator	Separator of Headings */
	public $headingSeparator	= " / ";
	/**	@var		string		$keyHeadings		Key of Headings within Session */
	public $keyHeadings			= "messenger_headings";
	/**	@var		string		$keyMessages		Key of Messages within Session */
	public $keyMessages			= "messenger_messages";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$keyMessages		Key of Messages within Session
	 *	@return		void
	 */
	public function __construct( $keyMessages = NULL )
	{
		if( !empty( $keyMessages ) )
			$this->keyMessages			= $keyMessages;
	}

	/**
	 *	Adds a Heading Text to Message Block.
	 *	@access		public
	 *	@param		string		$heading			Text of Heading
	 *	@return		void
	 */
	public function addHeading( $heading )
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$headings	= $session->get( $this->keyHeadings );
		if( !is_array( $headings ) )
			$headings	= array();
		$headings[]	= $heading;
		$session->set( $this->keyHeadings, $headings );
	}
	
	/**
	 *	Build Headings for Message Block.
	 *	@access		public
	 *	@return		string
	 */
	public function buildHeadings()
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$headings	= $session->get( $this->keyHeadings );
		$heading	= implode( $this->headingSeparator, $headings );
		return $heading;
	}

	/**
	 *	Builds Output for each Message on the Message Stack.
	 *	@access		public
	 *	@return		string
	 */
	public function buildMessages( $autoClear = TRUE )
	{
		$config		= Framework_Krypton_Core_Registry::getStatic( 'config' );
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$tc			= new Alg_TimeConverter;
		$messages	= (array) $session->get( $this->keyMessages );

		$list		= "";
		if( count( $messages ) )
		{
			$list	= array();
			foreach( $messages as $message )
			{
				$time	= $message['timestamp'] ? "[".$tc->convertToHuman( $message['timestamp'], $config['layout.formatTimestamp'] )."] " : "";
				$class	= $this->classes[$message['type']];
				$list[] = "<div class='".$class."'><span class='info'>".$time."</span><span class='message'>".$message['message']."</span></div>";
			}
			$list	= "<div class='messageList'>".implode( "\n", $list )."</div>";
			if( $autoClear )
				$this->clear();
		}
		return $list;
	}
	
	/**
	 *	Clears stack of Messages.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$session->set( $this->keyHeadings, array() );
		$session->set( $this->keyMessages, array() );
	}
	
	/**
	 *	Saves a Error Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteError( $message, $arg1 = NULL, $arg2 = NULL )
	{
		$message	= $this->setIn( $message, $arg1, $arg2 );
		$this->noteMessage( 1, $message);
	}

	/**
	 *	Saves a Failure Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteFailure( $message, $arg1 = NULL, $arg2 = NULL )
	{
		$message	= $this->setIn( $message, $arg1, $arg2 );
		$this->noteMessage( 0, $message);
	}
	
	/**
	 *	Saves a Message on the Message Stack.
	 *	@access		protected
	 *	@param		int			$type			Message Type (0-Failure|1-Error|2-Notice|3-Success)
	 *	@param		string		$message		Message to display
	 *	@return		void
	 */
	protected function noteMessage( $type, $message)
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$messages	= (array) $session->get( $this->keyMessages );
		$messages[]	= array( "message" => $message, "type" => $type, "timestamp" => time() );
		$session->set( $this->keyMessages, $messages );
	}
	
	/**
	 *	Saves a Notice Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteNotice( $message, $arg1 = NULL, $arg2 = NULL )
	{
		$message	= $this->setIn( $message, $arg1, $arg2 );
		$this->noteMessage( 2, $message);
	}
	
	/**
	 *	Saves a Success Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteSuccess( $message, $arg1 = NULL, $arg2 = NULL )
	{
		$message	= $this->setIn( $message, $arg1, $arg2 );
		$this->noteMessage( 3, $message);
	}
	
	/**
	 *	Indicates wheteher an Error or a Failure has been reported.
	 *	@access		public
	 *	@return		bool
	 */
	public function gotError()
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$messages	= (array) $session->get( $this->keyMessages );
		foreach( $messages as $message )
			if( $message['type'] < 2 )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Inserts arguments into a Message.
	 *	@access		protected
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		string
	 */
	protected function setIn( $message, $arg1, $arg2 )
	{
		if( $arg2 !== NULL )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)\{\S+\}(.*)@si", "$1".$arg1."$2".$arg2."$3", $message );
		else if( $arg1 !== NULL )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)@si", "$1###".$arg1."###$2", $message );
//		$message		= preg_replace( "@\{\S+\}@i", "", $message );
		$message		= str_replace( "###", "", $message );
		return $message;
	}
}
?>
