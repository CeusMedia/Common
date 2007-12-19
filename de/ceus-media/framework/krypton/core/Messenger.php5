<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.adt.TimeConverter' );
/**
 *	Message Output Handler within a Session.
 *	@package		framework.krypton.core
 *	@uses			Core_Registry
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
/**
 *	Message Output Handler within a Session.
 *	@package		framework.krypton.core
 *	@uses			Core_Registry
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
class Framework_Krypton_Core_Messenger
{
	/**	@var	Registry	$registry			Registry for Objects */
	protected $registry;
	/**	@var	array		$classes			CSS Classes of Message Types */
	protected $classes	= array(
		'0'	=> 'failure',
		'1'	=> 'error',
		'2'	=> 'notice',
		'3'	=> 'success',
		);

	/**	@var	string		$key_headings		Key of Headings within Session */
	protected $key_headings	= "";
	/**	@var	array		$classes			Key of Messages within Session */
	protected $key_messages	= "";
	/**	@var	array		$classes			Separator of Headings */
	protected $heading_separator	= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key_messages		Key of Messages within Session
	 *	@return		void
	 */
	public function __construct( $key_messages = "messenger_messages" )
	{
		$this->key_headings			= "messenger_headings";
		$this->key_messages			= $key_messages;
		$this->key_fields			= "messenger_fields";
		$this->heading_separator	= " / ";
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
		$headings	= $session->get( $this->key_headings );
		if( !is_array( $headings ) )
			$headings	= array();
		$headings[]	= $heading;
		$session->set( $this->key_headings, $headings );
	}
	
	/**
	 *	Build Headings for Message Block.
	 *	@access		public
	 *	@return		string
	 */
	public function buildHeadings()
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$headings	= $session->get( $this->key_headings );
		$heading	= implode( $this->heading_separator, $headings );
		return $heading;
	}

	/**
	 *	Builds Output for each Message on the Message Stack.
	 *	@access		public
	 *	@return		string
	 */
	public function buildMessages( $format_time = false, $auto_clear = true )
	{
		$config		= Framework_Krypton_Core_Registry::getStatic( 'config' );
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$tc			= new TimeConverter;
		$messages	= (array) $session->get( $this->key_messages );
		$fields		= (array) $session->get( $this->key_fields );
		$list		= "";
		if( count( $messages ) )
		{
			$list	= array();
			foreach( $messages as $message )
			{
				$time	= $message['timestamp'] ? "[".$tc->convertToHuman( $message['timestamp'], $config['layout']['format_timestamp'] )."] " : "";
				$class	= $this->classes[$message['type']];
				$list[] = "<div class='".$class."'><span class='info'>".$time."</span><span class='message'>".$message['message']."</span></div>";
			}
			$list	= "<div class='messageList'>".implode( "\n", $list )."</div>";
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
	public function clear()
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$session->set( $this->key_headings, array() );
		$session->set( $this->key_messages, array() );
	}
	
	/**
	 *	Saves a Error Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteError( $message, $arg1 = false, $arg2 = false )
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
	public function noteFailure( $message, $arg1 = false, $arg2 = false )
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
		$messages	= (array) $session->get( $this->key_messages );
		$messages[]	= array( "message" => $message, "type" => $type, "timestamp" => time() );
		$session->set( $this->key_messages, $messages );
	}
	
	/**
	 *	Saves a Notice Message on the Message Stack.
	 *	@access		public
	 *	@param		string		$message		Message to display
	 *	@param		string		$arg1			Argument to be set into Message
	 *	@param		string		$arg2			Argument to be set into Message
	 *	@return		void
	 */
	public function noteNotice( $message, $arg1 = false, $arg2 = false )
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
	public function noteSuccess( $message, $arg1 = false, $arg2 = false )
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
		$messages	= (array) $session->get( $this->key_messages );
		foreach( $messages as $message )
			if( $message['type'] < 2 )
				return true;
		return false;
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
		if( $arg2 )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)\{\S+\}(.*)@si", "$1".$arg1."$2".$arg2."$3", $message );
		else if( $arg1 )
			$message	= preg_replace( "@(.*)\{\S+\}(.*)@si", "$1###".$arg1."###$2", $message );
//		$message		= preg_replace( "@\{\S+\}@i", "", $message );
		$message		= str_replace( "###", "", $message );
		return $message;
	}
}
?>
