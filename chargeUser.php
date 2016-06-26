<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <tr>
      <td colspan="2" align="left" valign="top"><h3>Administracion de usuario</h3></td>
    </tr>
    <tr>
      <td align="center" valign="top">Ingresa Ruta de Archivo</td>
      <td><input name="Path" type="text" class="Input"></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name="Submit" type="submit" value="Cargar" class="Button3"></td>
    </tr>
     <tr>
      <td colspan="2" align="left" valign="top"><h3>Graficar Usuarios</h3></td>
     <td><input name="Graph" type="submit" value="Graficar" class="Button3"></td>
     <td><input name="Show" type="submit" value="Mostrar Grafica" class="Button3"></td>
    </tr>
     <tr>
     <td colspan="2" align="left" valign="top"><h3>Eliminar Usuarios</h3></td>
     <td><input name="Delete" type="submit" value="Eliminar" class="Button3"></td>
     </tr>
     <td colspan="2" align="left" valign="top"><h3>Agregar Usuarios</h3></td>
     <td><input name="newUser" type="text" class="Input"></td>
     <td><input name="newPass" type="text" class="Input"></td>
     <td><input name="Add" type="submit" value="Agregar" class="Button3"></td>
     <tr>
     <td colspan="2" align="left" valign="top"><h3>Modificar Usuarios</h3></td>
     <td><input name="Modify" type="submit" value="Modificar" class="Button3"></td>
     </tr>
  </table>
     </form>
<form enctype="multipart/form-data" action="chargeUser.php" method="POST">
     SUBIR ARCHIVO: <input name="fichero_usuario" type="file" />
    <input type="submit" value="Enviar fichero" name="send" />
</form>
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if(isset($_POST['Delete'])){
    header("Location: deleteUser.php");
}

if(isset($_POST['Modify'])){
    header("Location: modUser.php");
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
        echo "¡Posible ataque de subida de ficheros!\n";
        echo '<pre>';
        print_r($_FILES);
        print "</pre>";
    }

}

if(isset($_POST['Add'])){
    $newUser = $_POST['newUser'];
    $newPassw = $_POST['newPass'];
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'NEWUSR,' . $newUser . ',' . $newPassw;
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    $channel->close();
}


if(isset($_POST['Graph'])){
    send();    
}

if(isset($_POST['Show'])){
    showImage();
}

function showImage(){
    header("Location:showGraph.html");
}

function sendFile($FILE_NAME){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $allFile = "UFILE,";
    $handle = fopen($FILE_NAME, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $allFile .= $line . ',*,';
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

function send(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $message = 'GRAPH';
    echo "$message";
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'hello');
    $channel->close();
}

function receive(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('msg', false, false, false, false);
    $callback = function($msg) {
        if($msg->body == 'READY'){
            echo "ready";
            showImage();
        }else{
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

