<?php
class View{
    public static function  start($title){
        $html = "<!DOCTYPE html>
<html>
<head>
<meta charset=\"utf-8\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"estilos.css\">
<script type=\"text/javascript\" src=\"scripts.js\"></script>
<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js\"></script>
<title>$title</title>
</head>
<body>";
        User::session_start();
        echo $html;
    }
    public static function navigation(){
        $user=User::getLoggedUser();
        echo '<nav>';
        if($user === false){
            echo '<a href="login.php">Login</a>';
        }else{
            echo '<a href="usuarios.php">Lista usuarios</a> ';
            echo '<a href="enviados.php">Mensajes enviados</a> ';
            echo '<a href="recibidos.php">Mensajes recibidos</a> ';
            echo '<a href="logout.php">Logout</a> ';
            echo 'Usuario: '.$user['nombre'];
        }
        echo '</nav>';
    }
    public static function end(){
        echo '</body>
</html>';
    }
}

class DB{
    private static $connection=null;
    public static function get(){
        if(self::$connection === null){
            self::$connection = $db = new PDO("sqlite:./datos.db");
            self::$connection->exec('PRAGMA foreign_keys = ON;');
            self::$connection->exec('PRAGMA encoding="UTF-8";');
        }
        return self::$connection;
    }
    
    public static function execute_sql($sql, $parms=null){
        try {
            $db=self::get();
            $ints=$db->prepare($sql);
            if ($ints->execute($parms)) {
                return $ints;
            }
        } catch (PDOException $e) {}
        return false;
    }
    
    
}
class User{
    public static function session_start(){
        if(session_status () === PHP_SESSION_NONE){
            session_name('PR4_ORD');
            session_start();
        }
    }
    public static function getLoggedUser(){ //Devuelve un array con los datos del cuenta o false
        self::session_start();
        if(!isset($_SESSION['user'])) return false;
        return $_SESSION['user'];
    }
    public static function login($cuenta,$pass){ //Devuelve verdadero o falso según
        self::session_start();
        $db=DB::get();
        $inst=$db->prepare('SELECT * FROM usuarios WHERE cuenta=? and clave=?');
        $inst->execute(array($cuenta,md5($pass)));
        $inst->setFetchMode(PDO::FETCH_NAMED);
        $res=$inst->fetchAll();
        if(count($res)==1){
            $_SESSION['user']=$res[0];
            return true;
        }
        return false;
    }
}
