<?php
    session_start();
    /*Consultar a la base de datos */

        $con = new mysqli('localhost','root','','utrmlogin');
         
        // connect_errno: Verifica si ocurrió un error al intentar conectar a la base de datos.
        // La propiedad 'connect_errno' pertenece al objeto de conexión mysqli.
        // Retorna un valor entero distinto de cero si hay un error de conexión, o cero si la conexión fue exitosa.
        // No recibe argumentos.
        // Se utiliza para manejar errores de conexión y tomar acciones apropiadas en caso de fallo.
        if($con->connect_errno){

            
        } else {
            $query ="select * from users";
            $result=$con->query($query);            
            
            
        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Usuarios</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        .sidebar {
            background-color: #f8f9fa;
            height: 100vh;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4">
                    <h4 class="mb-4">Menú</h4>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                                Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link link-dark">
                                Categorías
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link link-dark">
                                Ventas
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link link-dark">
                                Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="nav-link link-dark">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2>Lista de Usuarios</h2>
                <button class="btn btn-primary mb-3">Agregar Usuario</button>
                
                <!-- Tabla de Usuarios -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Perfil</th>
                                <th>Visualizar</th>
                                <th>Nuevo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($row= $result->fetch_assoc()){
                            ?>
                            <tr>
                                <td><?php echo $row['id'];?></td>
                                <td><?php echo $row['username'];?></td>
                                <td><?php echo $row['roll'];?></td>
                                <td><a class="visible-on" href="#">on</a></td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Editar</button>
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementsByClass('visible-on').addEventListener('click',function(){
            alert();
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>