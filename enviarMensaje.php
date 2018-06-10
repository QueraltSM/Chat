<?php
include_once 'lib.php';
View::start('Enviar mensajes');
View::navigation();

$actualUser=User::getLoggedUser();
$idActualUser=$actualUser['id'];
$destinatario=$_GET['destinatario'];

if ((isset($_POST["mensaje"]))) {
    $idDestinatario=getIdDestinatario($destinatario);
    
    $db=DB::get();
    $crear=new PDO("sqlite:./datos.db");
    $crear->beginTransaction();
    
    $hora=time();
    $mensaje=$_POST["mensaje"];
    
    $res=DB::execute_sql("INSERT INTO mensajes (remitente,destinatario,hora,mensaje) VALUES (?,?,?,?)", 
        array($idActualUser,$idDestinatario,$hora,$mensaje));

    if ($res) {
        $crear->commit();
        echo "Mensaje ha sido enviado :)";
    } else {
        echo "No se ha enviado el mensaje :(";
    }
    
} else {    
    echo '<form method ="post">';
    echo "<textarea name='mensaje' placeholder='Escribe tu mensajen a $destinatario'></textarea>";
    echo '<button type="submit">Enviar mensaje</button>';
    echo "</form>";
}

function getIdDestinatario($nombre){
    $db=DB::get();
    $res=$db->prepare("SELECT id from usuarios WHERE nombre == '$nombre'");
    $res->execute();
    if ($res) {
        $res->setFetchMode(PDO::FETCH_NAMED);
        foreach ($res as $user){
            foreach ($user as $value) {
                return $value;
            }
        }
    } 
}
View::end();