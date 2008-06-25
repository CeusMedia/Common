<?php
include("xmpp.php");
$conn = new XMPP('talk.google.com', 5222, 'username', 'password', 'xmpphp', 'gmail.com', $printlog=False, $loglevel=LOGGING_INFO);
$conn->use_encyption = True; # the default, change to false if you don't have SSL extensions
$conn->connect();
$conn->processUntil('session_start');
$conn->message('someguy@someserver.net', 'This is a test message!');
$conn->disconnect();

?>
