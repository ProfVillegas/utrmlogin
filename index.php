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
        <select name="profile" id="profile">
            <option value="admin">admin</option>
            <option value="user">user</option>
            <option value="guess">guess</option>
        </select>
        <input type="submit" value="Login">
    </form>
    <div class="result">
        <?php
        /*Consultar a la base de datos */
        $users[]=array('nombre'=>'jose','passw'=>'Changos','profile'=>'admin');
        $users[]=array('nombre'=>'ana','passw'=>'Clark','profile'=>'user');
        $users[]=array('nombre'=>'invitado','passw'=>'invitado','profile'=>'guess');

            if(isset($_GET['nombre']) && isset($_GET['passw'])){
                
                switch($_GET['profile']){
                    case 'admin':{
                        if($_GET['nombre']==$users[0]['nombre'] && $_GET['passw']==$users[0]['passw']){
                            header("location:sys/admin_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    case 'user':{
                        if($_GET['nombre']==$users[1]['nombre'] && $_GET['passw']==$users[1]['passw']){
                            header("location:sys/user_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    case 'guess':{
                        if($_GET['nombre']==$users[2]['nombre'] && $_GET['passw']==$users[2]['passw']){
                            header("location:sys/guess_home.php");
                        } else {
                            echo "Usuario Incorrecto";
                        }
                        break;
                    }
                    default:{

                    }


                }                
            } 
        ?>
    </div>
</body>
</html>