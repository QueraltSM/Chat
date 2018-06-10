<?php
include_once 'lib.php';
View::start('Listado de usuario');
View::navigation();

$actualUser=User::getLoggedUser();
$idActualUser=$actualUser['id'];

$db=DB::get();
$res=$db->prepare("SELECT nombre from usuarios WHERE id != '$idActualUser'");
$res->execute();
if ($res) {
        $first=true;
        $res->setFetchMode(PDO::FETCH_NAMED);
        foreach ($res as $user){
            foreach ($user as $value) {
                echo "<a href='enviarMensaje.php?destinatario=$value'>$value</a>";
            }
        }
}
View::end();