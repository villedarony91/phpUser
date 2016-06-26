<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="center" valign="top"><h3>Modificacion de usuario</h3></td>
    </tr>
     <tr>
           <td align="right" valign="top">Nombre de usuario a modificar</td>
     <td><input name="ToDelete" type="text" class="Input"></td>
     </tr>
     <tr>
     <td colspan="2" align="left" valign="top"><h3>Nuevos Datos</h4></td>
     </tr>
     <td align="right" valign="top">Nuevo nombre:</td>
     <td><input name="newUser" type="text" class="Input"></td>
     <td align="right" valign="top">Nueva Contresena:</td>
     <td><input name="newPass" type="text" class="Input"></td>
     <td><input name="Modify" type="submit" value="Modificar" class="Button3"></td>
  </table>
<?php
          require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

     if(isset($_POST['Modify'])){
         sendMod();
     }

     function sendMod(){
         $newUser = $_POST['newUser'];
         $newPassw = $_POST['newPass'];
         $toDelete = $_POST['ToDelete'];
         $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
         $channel = $connection->channel();
         $channel->queue_declare('hello', false, false, false, false);
         $message = 'MODUSR,' . $newUser . ',' . $newPassw . ',' . $toDelete;
         echo "$message";
         $msg = new AMQPMessage($message);
         $channel->basic_publish($msg, '', 'hello');
         $channel->close();
     }
?>
