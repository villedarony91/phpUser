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
     <td align="center" valign="top">Graficar arbol de capas</td>
      <td><input name="Graph" type="submit" value="Graficar" class="Button3"></td>
     <td align="center" valign="top">Eliminar Capa</td>
     <td><input name="Delete" type="submit" value="Ir a Eliminar" class="Button3"></td>
     <tr>
      <td align="center" valign="top">Graficar Matriz capa</td>
     <td><input name="matGraph" type="submit" value="Ir a graficar" class="Button3"></td>
     </tr>
  </table>
</form>
     <form enctype="multipart/form-data" action="chargeLayer.php" method="POST">
     SUBIR ARCHIVO: <input name="fichero_usuario" type="file" />
    <input type="submit" value="Enviar fichero" name="send" />
</form>
<?php
     require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if(isset($_POST['matGraph'])){
    header("Location:matGraph.php");
}


if(isset($_POST['Submit'])){
    sendFile($_POST['Path']);
}

if(isset($_POST['send'])){
    $dir_subida = '/var/www/html/uploads/';
    $fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name']);
    echo $fichero_subido;
    if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
        echo "El fichero es válido y se subió con éxito.\n";
    } else {
        echo '<pre>';
        print_r($_FILES);
        print "</pre>";
    }
}


if(isset($_POST['Delete'])){
    header("Location:deleteLayer.php");
}

if(isset($_POST['Graph'])){
    send();
    header("Location:showTreeGraph.html");
}

function send(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'TGRAPH';
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'GRAPH'\n";
    $channel->close();    
}

     
function sendFile($FILE_NAME){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $layerName = $_POST['LayerName'];
    $allFile = 'LFILE,' . $layerName . ',' ;
    $handle = fopen($FILE_NAME, "r");
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
