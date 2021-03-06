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
         <tr>
      <td> </td>
      <td><input name="Get" type="submit" value="return" class="Button3"></td>
    </tr>
  </table>
</form>

<?php
     require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if(isset($_POST['Submit'])){
    echo "pressed";
    send();    
}

if(isset($_POST['Get'])){
    receive();
}

function send(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $password = $_POST['Password'];
    $username = $_POST['Username'];
    $message = 'LOGIN,'. $username .','. $password;
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Hello World!'\n";
    $channel->close();    
}

function receive(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('msg', false, false, false, false);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
    $callback = function($msg) {
        if($msg->body == 'ADMIN'){
            header("Location:chargeUser.php");
        }
        if($msg->body == 'USER'){
            header("Location:chargeLayer.php");
        }
        else{
            echo "fallo de autenticacion";
        }
        $connection->close();
        exit;
    };
    $channel->basic_consume('msg', '', false, true, false, false, $callback);
    while(count($channel->callbacks)) {
        $channel->wait();
        }
    $connection->close();
}
?>