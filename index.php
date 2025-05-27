<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola Mundo</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        h1 {
            font-size: 4rem;
            text-transform: uppercase;
            text-align: center;
        }
        form{
            padding:30px;
            border-radius:10px;
            border:1px solid #333;
            display: block;
        }
        input{
            display:block;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <h1><?php echo "Hola Mundo"; ?></h1>
    <form action="index.php" method="get">
        <label for="nombre" class="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>
        <label for="passw">Contraseña</label>
        <input type="password" name="passw" id="passw" required>
        
        <input type="submit" value="Login">
    </form>
    <div class="result">
        <?php
        //Si usuario existe logeado
        if(isset($_SESSION['roll'])){
            switch($_SESSION['roll']){
                    case 'admin':{
                            header("location:sys/admin_home.php");
                        break;
                    }
                    case 'user':{
                            header("location:sys/user_home.php");
                        break;
                    }
                    case 'guest':{
                            header("location:sys/guest_home.php");
                        
                    }
                    default:{

                    }
                }

        }
        /*Consultar a la base de datos */

        $con = new mysqli('localhost','root','','utrmlogin');
         
        // connect_errno: Verifica si ocurrió un error al intentar conectar a la base de datos.
        // La propiedad 'connect_errno' pertenece al objeto de conexión mysqli.
        // Retorna un valor entero distinto de cero si hay un error de conexión, o cero si la conexión fue exitosa.
        // No recibe argumentos.
        // Se utiliza para manejar errores de conexión y tomar acciones apropiadas en caso de fallo.
        if($con->connect_errno){
            /**
             * connect_error: Muestra un mensaje de error si ocurre un problema al conectar con la base de datos.
             *
             * Posibles errores que puede regresar $con->connect_error:
             * - "Access denied for user": Usuario o contraseña incorrectos.
             * - "Unknown database": El nombre de la base de datos no existe.
             * - "Can't connect to MySQL server": El servidor MySQL no está disponible o la dirección es incorrecta.
             * - "Too many connections": El servidor ha alcanzado el límite de conexiones permitidas.
             * - "Connection refused": El servidor rechazó la conexión (puerto incorrecto o firewall).
             *
             * Para darles formato a los errores:
             * - Se utiliza una etiqueta <h4> con la clase 'error' para resaltar el mensaje.
             * - Se recomienda no mostrar detalles sensibles en producción, solo mensajes genéricos.
             * - Para un mejor manejo, se puede registrar el error en un archivo de log y mostrar un mensaje amigable al usuario.
             */
             echo "<h4 class='error'>MSQYL: Error ".$con->connect_error."</h4>";
             exit();
        } else{
            if(isset($_GET['nombre']) && isset($_GET['passw'])){
                $_SESSION['nombre']=$_GET['nombre'];
                
                $passw=$_GET['passw'];

                $query="select id, username, passw, roll from users
                where username = ? and passw = ?";
                // El método prepare de mysqli prepara una consulta SQL para su ejecución segura,
                // permitiendo el uso de parámetros en la consulta y evitando inyecciones SQL.
                $stmt=$con->prepare($query);


                //Vincular el primer parametro
                if($stmt){
                    $stmt->bind_param("ss",$_GET['nombre'],$_GET['passw']);
                    // El método bind_param recibe: 
                    // 1. Una cadena con los tipos de los parámetros 
                    // ('s' para string, 'i' para integer, 'd' para Flotantes, 'b' para Boleanos.)
                    // Si en este apartado colocamos 'ssi', debemos enviar 3 valores en el orden que
                    // los primeros 2 argumentos deben ser valores string y el ultimo debe ser integer.
                    // 2. Una variable para cada parámetro a enlazar, en el mismo orden que aparecen en la consulta
                    $stmt->execute();
                    $result=$stmt->get_result();

                    //var_dump($result->num_rows);

                    //Si no encuentra usuario válido
                    if($result->num_rows!=1){
                        header("location:index.php?errno=2");
                    } else {                    
                    // Distintos tipos de fetch que tiene mysqli:
                    // $result->fetch_assoc()    // Devuelve un array asociativo (clave = nombre de columna)
                    // $result->fetch_row()      // Devuelve un array numérico (índices 0, 1, 2, ...)
                    // $result->fetch_array()    // Devuelve un array tanto asociativo como numérico
                    // $result->fetch_object()   // Devuelve un objeto con propiedades iguales a los nombres de columna

                    //var_dump($result->fetch_assoc());
                    $user=$result->fetch_assoc();
                    echo $user['roll'];
                    $_SESSION['roll']=$user['roll'];
                    $_SESSION['id']=$user['id'];
                    header("location:sys/".$user['roll']."_home.php");
                    $result->free();

                    }
                }
            }
        }

        //Cerrar la conexión
        $con->close();
        /*
        $users[]=array('nombre'=>'jose','passw'=>'Changos','roll'=>'admin');
        $users[]=array('nombre'=>'ana','passw'=>'Clark','roll'=>'user');
        $users[]=array('nombre'=>'invitado','passw'=>'invitado','roll'=>'guest');

            if(isset($_GET['nombre']) && isset($_GET['passw'])){
                
                switch($_GET['roll']){
                    case 'admin':{
                        if($_GET['nombre']==$users[0]['nombre'] && $_GET['passw']==$users[0]['passw']){
                            $_SESSION['nombre']=$_GET['nombre'];
                            $_SESSION['passw']=$_GET['passw'];
                            $_SESSION['roll']=$_GET['roll'];
                            header("location:sys/admin_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    case 'user':{
                        if($_GET['nombre']==$users[1]['nombre'] && $_GET['passw']==$users[1]['passw']){
                            $_SESSION['nombre']=$_GET['nombre'];
                            $_SESSION['passw']=$_GET['passw'];
                            $_SESSION['roll']=$_GET['roll'];
                            header("location:sys/user_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    case 'guest':{
                        if($_GET['nombre']==$users[2]['nombre'] && $_GET['passw']==$users[2]['passw']){
                            $_SESSION['nombre']=$_GET['nombre'];
                            $_SESSION['passw']=$_GET['passw'];
                            $_SESSION['roll']=$_GET['roll'];
                            header("location:sys/guest_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    default:{
                        echo "Usuario Incorrecto";
                    }


                }                
            } */
        ?>
    </div>
    <?php
    /*CREATE TABLE users (
	 id INT PRIMARY KEY AUTO_INCREMENT,
	 username VARCHAR(50) UNIQUE NOT NULL,
	 passw VARCHAR(20) NOT NULL,
	 roll ENUM('admin','user','guest') NOT NULL 
    );*/

    /*INSERT INTO users(username,passw,roll) VALUES
    ('brayan','stich','admin'),
    ('tania','yagami','user'),
    ('invitado','invitado','guest');*/

    /*SELECT * from users;*/
        ?>
</body>
</html>