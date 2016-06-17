<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="left" valign="top"><h3>Login</h3></td>
    </tr>
    <tr>
      <td align="right" valign="top">Username</td>
      <td><input name="Username" type="text" class="Input"></td>
    </tr>
    <tr>
      <td align="right">Password</td>
      <td><input name="Password" type="password" class="Input"></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name="Submit" type="submit" value="Login" class="Button3"></td>
    </tr>
  </table>
</form>

<?php
     require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
//$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

if(isset($_POST['Submit'])){
    /*$channel = $connection->channel();
    $channel->queue_declare('userData', false, false, false, false);
    $password = $_POST['Password'];
    $username = $_POST['Username'];
    $message = 'LOGIN,'. $username .','. $password;
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'messages');
    echo " [x] Sent 'Hello World!'\n";
    $channel->close();
    /**/
    echo "entered";
    $connection2 = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    echo "1";
    $channelR-> $connection2->channel();
    echo "2";
    $channelR->queue_declare('return', false, false, false, false);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
    $callback = function($msgR) {
        echo " [x] Received ", $msgR->body, "\n";
    };
    $channelR->basic_consume('return', '', false, true, false, false, $callback);
    while(count($channelR->callbacks)) {
        $channelR->wait();
        echo "waiting";
    }
    $channelR->close();
    $connection2->close();

}

?>