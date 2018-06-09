<?php
include_once 'lib.php';
View::start('Mensajes enviados');
View::navigation();

$actualUser=User::getLoggedUser();
$idActualUser=$actualUser['id'];

$db=DB::get();
$res=$db->prepare("SELECT hora,remitente,mensaje from mensajes WHERE destinatario == '$idActualUser'");
$res->execute();
if ($res) {
        $count=0;
        $first=true;
        $res->setFetchMode(PDO::FETCH_NAMED);
        
        foreach ($res as $user){
            foreach ($user as $value) {
                switch ($count) {
                    case 0:
                        echo "<td>Hora:</td>";
                        $dt=new DateTime("@$value");
                        $value=$dt->format('H:i:s');
                        break;
                    case 1:
                        echo "<td>Destinatario:</td>";  
                        $nombre=getNombreDestinatario($value);
                        $value=$nombre;
                        break;
                    case 2:
                        echo "<td>Mensaje:</td>";  
                        $count=-1;
                        break;
                }
            
            echo "<tr>$value</tr>"; 
            echo "<br><br>";
            $count++;
        }
    }
}



function getNombreDestinatario ($id) {
    $db=DB::get();
    $res=$db->prepare("SELECT nombre from usuarios WHERE id == '$id'");
    $res->execute();
    if ($res) {  
       foreach ($res as $user){
           foreach($user as $nombreUsuario)
                return $nombreUsuario;
        }
    }
}


View::end();