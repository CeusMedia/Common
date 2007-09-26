<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.Reference' );
import( 'de.ceus-media.adt.TimeConverter' );
/**
 *	Message Output Handler within a Session.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		OptionObject
 *	@uses			Reference
 *	@uses			TimeConverter
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.2
 */
/**
 *	Message Output Handler within a Session.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		OptionObject
 *	@uses			Reference
 *	@uses			TimeConverter
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.2
 */
class Messenger extends OptionObject
{
	/**	@var	Reference	$ref			Reference */
	var $ref;
	/**	@var	Reference	$classes			CSS Classes of Message Types */
	var $classes	= array(
		'0'	=> 'failure',
		'1'	=> 'error',
		'2'	=> 'notice',
		'3'	=> 'success',
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key_messages		Key of Messages within Session
	 *	@return		void
	 */
	public function __construct( $key_messages = "messenger_messages" )
	{
		parent::__construct();
		$this->setOption( 'key_headings', "messenger_headings" );
		$this->setOption( 'key_messages', $key_messages );
		$this->setOption( 'heading_separator', " / " );
		$this->ref		= new Reference;
	}

	/**
	 *	Adds a Heading Text to Message Block.
	 *	@access		public
	 *	@param		string		$heading			Text of Heading
	 *	@return		void
	 */
	function addHeading( $heading )
	{
		$session		=& $this->ref->get( 'session' );
		$headings	= $session->get( $this->getOption( 'key_headings' ) );
		if( !is_array( $headings ) )
			$headings	= array();
		$headings[]	= $heading;
		$session->set( $this->getOption( 'key_headings' ), $headings );
	}
	
	/**
	 *	Build Headings for Message Block.
	 *	@access		public
	 *	@return		string
	 */
	function buildHeadings()
	{
		$session		=& $this->ref->get( 'session' );
		$headings	= $session->get( $this->getOption( 'key_headings' ) );
		$heading		= implode( $this->getOption( 'heading_separator' ), $headings );
		return $heading;
	}

	/**
	 *	Builds Output for each Message on the Message Stack.
	 *	@access		public
	 *	@return		string
	 */
	function buildMessages( $format_time = false, $auto_clear = true )
	{
		$config	= $this->ref->get( 'config' );
		$session	=& $this->ref->get( 'session' );
		$tc		= new TimeConverter;
		$messages	= (array) $session->get( $this->getOption( 'key_messages' ) );
		$list	= "";
		if( count( $messages ) )
		{
			$list	= array();
			foreach( $messages as $message )
			{
				$time	= $message['timestamp'] ? "[".$tc->convertToHuman( $message['timestamp'], $config['layout']['format_timestamp'] )."] " : "";
				$class	= $this->classes[$message['type']];
				$list[] = "<div class='".$class."'><span class='info'>".$time."</span><span class='message'>".$message['message']."</span></div>";
			}
			$list	= "<div id='list'>".implode( "\n", $list )."</div>";
			if( $auto_clear )
				$this->clear();
		}
		return $list;
	}
	
	/**
	 *	Clears stack of Messages.
	 *	@access		public
	 *	@return		void
	 */
	function clear()
	{
		$session	=& $this->ref->get( 'session' );
		$session->set( $this->getOption( 'key_headings' ), array() );
		$session->set( $this->getOption( 'key_messages' ), array() );
	}

	/**
	 *	Saves a Error Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	function noteError( $message, $arg1 = false, $arg2 = false )
	{
		$message	= $this->_setIn( $message, $arg1, $arg2 );
		$this->_noteMessage( 1, $message);
	}

	/**
	 *	Saves a Failure Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	function noteFailure( $message, $arg1 = false, $arg2 = false )
	{
		$message	= $this->_setIn( $message, $arg1, $arg2 );
		$this->_noteMessage( 0, $message);
	}
	
	/**
	 *	Saves a Notice Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	function noteNotice( $message, $arg1 = false, $arg2 = false )
	{
		$message	= $this->_setIn( $message, $arg1, $arg2 );
		$this->_noteMessage( 2, $message);
	}
	
	/**
	 *	Saves a Success Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	function noteSuccess( $message, $arg1 = false, $arg2 = false )
	{
		$message	= $this->_setIn( $message, $arg1, $arg2 );
		$this->_noteMessage( 3, $message);
	}
	
	/**
	 *	Indicates wheteher an Error or a Failure has been reported.
	 *	@access		public
	 *	@return		bool
	 */
	function gotError()
	{
		foreach( $messages as $message )
			if( $message['type'] < 2 )
				return true;
		return false;
	}

	//  --  PRIVATE METHODS
	/**
	 *	Inserts arguments into a Message.
	 *	@access		private
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		string
	 */
	function _setIn( $message, $arg1, $arg2 )
	{
		if( $arg2 )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)\{\S+\}(.*)@si", "$1".$arg1."$2".$arg2."$3", $message );
		else if( $arg1 )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)@si", "$1###".$arg1."###$2", $message );
//		$message		= preg_replace( "@\{\S+\}@i", "", $message );
		$message		= str_replace( "###", "", $message );
		return $message;
	}
	
	/**
	 *	Saves a Message on the Message Stack.
	 *	@access		private
	 *	@param		int			$type			Message Type (0-Failure|1-Error|2-Notice|3-Success)
	 *	@param		string		$message		Message to display
	 *	@return		void
	 */
	function _noteMessage( $type, $message)
	{
		$session		=& $this->ref->get( 'session' );
		$messages	= (array) $session->get( $this->getOption( 'key_messages' ) );
		$messages[]	= array( "message" => $message, "type" => $type, "timestamp" => time() );
		$session->set( $this->getOption( 'key_messages' ), $messages );
	}
}
?>
