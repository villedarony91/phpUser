<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="left" valign="top"><h3>Cargar Mapa</h3></td>
    </tr>
    <tr>
      <td align="center" valign="top">Ingresa Ruta de Archivo</td>
      <td><input name="Username" type="text" class="Input"></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name="Submit" type="submit" value="Login" class="Button3"></td>
    </tr>
  </table>
</form>
<?php
//     include 'chargeUser.php';
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
     if(isset($_POST['Submit'])){
         $fila = 1;
         echo "pressed";
         $handle = fopen("/home/rlopez/MOCK_DATA.csv", "r");
         echo "1";
         $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
         echo "2";
         $channel = $connection->channel();
         echo "3";
         $channel->queue_declare('hello', false, false, false, false);
         if ($handle) {
             while (($line = fgets($handle)) !== false) {
                 
                 echo "line\n";
                 $msg = new AMQPMessage($line);
                 $channel->basic_publish('UFILE,' . $msg .',' , '', 'hello');
                 //echo " [x] Sent 'Hello World!'\n";
               }
             fclose($handle);
             $chanel->close();
         } else {
             // error opening the file.
         } 
     }
     
?>

