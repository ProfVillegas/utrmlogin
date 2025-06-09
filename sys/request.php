<?php
// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type:application/json');

// Inicia la sesión PHP
session_start();

// Obtiene y decodifica los datos JSON recibidos en la petición (primer elemento del array)
$data = json_decode(file_get_contents("php://input"), true)[0];

/* Conexión a la base de datos MySQL */
$con = new mysqli('localhost', 'root', '', 'utrmlogin');
switch ($data['tb']) {
    case 'users': {
            switch ($data['op']) {
                case 'visible': {
                        // Consulta SQL para actualizar el campo 'visible' del usuario
                        $query = "UPDATE users SET visible=? WHERE id=?";

                        // Prepara la consulta SQL para evitar inyección de SQL
                        $stmt = $con->prepare($query);

                        // Invierte el valor de 'visible': si es 1 lo pone en 0, si es 0 lo pone en 1
                        $vs = ($data['vs']) ? 0 : 1;

                        // Vincula los parámetros a la consulta preparada (ambos son enteros)
                        $stmt->bind_param("ii", $vs, $data['id']);

                        // Ejecuta la consulta y verifica si fue exitosa
                        if ($stmt->execute()) {
                            // Si la actualización fue exitosa, retorna el id y el nuevo valor de 'visible'
                            $res = array('res' => '1', 'id' => $data['id'], 'vs' => $vs);
                        } else {
                            // Si hubo un error, retorna un mensaje de error
                            $res = array('res' => '2', 'msg' => 'Error: Registro no localizado.');
                        }
                        break;
                    }
                    case 'new':{
                        $query="insert into users(username,psw,visible,roll) values(?,?,?,?)";
                        $stmt=$con->prepare($query);
                        $stmt->bind_param('ssis',$data['username'],$data['psw'],$data['visible'],$data['roll']);
                        //Ejecutamos la consulta
                        if($stmt->execute()){
                            $res=array('res'=>'1','id'=>$data['username']);
                        }else {
                            // Si hubo un error, retorna un mensaje de error
                            $res = array('res' => '3', 'msg' => 'Error: No se pudo crear el usuario.');
                        }
                        break;
                    }
            }
        }
}
// Cierra la consulta y la conexión a la base de datos
$stmt->close();
$con->close();

// Devuelve la respuesta en formato JSON
echo json_encode($res);
