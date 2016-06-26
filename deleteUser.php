<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="left" valign="top"><h3>Eliminar Usuario</h3></td>
    </tr>
     <tr>     
     </tr>
     <td align="center" valign="top">Eliminar Usuario</td>
     <td><input name="ToDelete" type="text" class="Input"></td>
     <td><input name="Del" type="submit" value="Eliminar" class="Button3"></td>
  </table>
</form>
<?php
     require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if(isset($_POST['Del'])){
    $toDelete = $_POST['ToDelete'];
    echo "borrar".$toDelete;
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'UDELETE,'. $toDelete;
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Message'\n";
    $channel->close();    

}

$data;
/*    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'UDATA';
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Message'\n";
    $channel->close();    
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('msg', false, false, false, false);
    $callback = function($msg) {
        $data = $msg->body;
        $arr = explode(';',$data);
?>
        <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
        <td align = "center">
        Capas Disponibles:<?php
        foreach($arr as $key => $arr) { ?>
      <option value="<?= $arr['name'] ?>"><?= $arr?></option>
  <?php
    } ?>
        </tr></td></table>
        <?php
        $connection->close();
    };
    $channel->basic_consume('msg', '', false, true, false, false, $callback);
    while(count($channel->callbacks)) {
        $channel->wait();
        }
        $connection->close();*/
if(isset($_POST['PedirData'])){
    send();
}
if(isset($_POST['Charge'])){
    receive();
    echo $data;
}



function send(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'TDATA';
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Message'\n";
    $channel->close();    
}

     
function receive(){


}

 
?>