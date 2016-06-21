<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="left" valign="top"><h3>Cargar Capas</h3></td>
    </tr>
    <tr>
      <td align="center" valign="top">Ingresa Ruta de Archivo</td>
      <td><input name="Path" type="text" class="Input"></td>
     <td align="center" valign="top">Ingresa Nombre capa</td>
     <td><input name="LayerName" type="text" class="Input"></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name="Submit" type="submit" value="Cargar" class="Button3"></td>
    </tr>
  </table>
</form>
<?php
     require_once __DIR__ . '/vendor/autoload.php';
include('gv.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if(isset($_POST['Submit'])){
    sendFile();    
}

     
function sendFile(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $layerName = $_POST['LayerName'];
    $allFile = 'LFILE,' . $layerName . ',' ;
    $handle = fopen("/home/rlopez/orto.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $allFile .= $line;
        }

        fclose($handle);
        echo $allFile;
    } else {
        echo "error abriendo archivo";
    }
             
    $msg = new AMQPMessage($allFile); 
    $channel->basic_publish($msg, '', 'hello');
    $chanel->close();
}
?>
