<?php
session_start();
/*Consultar a la base de datos */

$con = new mysqli('localhost', 'root', '', 'utrmlogin');

// connect_errno: Verifica si ocurrió un error al intentar conectar a la base de datos.
// La propiedad 'connect_errno' pertenece al objeto de conexión mysqli.
// Retorna un valor entero distinto de cero si hay un error de conexión, o cero si la conexión fue exitosa.
// No recibe argumentos.
// Se utiliza para manejar errores de conexión y tomar acciones apropiadas en caso de fallo.
if ($con->connect_errno) {
} else {
    $query = "select id, username, roll, visible from users";
    $result = $con->query($query);
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
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userModal">Agregar Usuario</button>

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
                        <tbody id="table">
                            <?php
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['roll']; ?></td>
                                    <td><a class="visible-on" href="#" data-id="<?php echo $row['id']; ?>" data-vs="<?php echo $row['visible']; ?>">
                                            <?php echo ($row['visible'] ? 'on' : 'off'); ?>
                                        </a></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning save" data-bs-toggle="modal" data-bs-target="#modalAccion">Editar</button>
                                        <button class="btn btn-sm btn-danger remove" data-bs-toggle="modal" data-bs-target="#modalAccion">Eliminar</button>
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
        document.addEventListener('click', function(event) {
            event.preventDefault();
            if (event.target.matches('.visible-on')) {
                // Prepara un objeto JSON con los datos necesarios para una operación de visibilidad sobre un usuario.
                // El objeto contiene:
                //   'op'  : tipo de operación ('visible'),
                //   'id'  : identificador del usuario obtenido del atributo 'data-id' del elemento que disparó el evento,
                //   'tb'  : nombre de la tabla ('users'),
                //   'vs'  : valor de visibilidad obtenido del atributo 'data-vs'.
                // Este objeto se serializa como cadena JSON con la propiedad 'stringify' para su posterior envío, probablemente a través de una petición AJAX.
                data = JSON.stringify([{
                    'op': 'visible',
                    'id': event.target.dataset.id,
                    'tb': 'users',
                    'vs': event.target.dataset.vs
                }]);

                // Envía una solicitud AJAX utilizando los datos proporcionados y maneja la respuesta usando promesas.
                SendAjaxRequest(data).then(function(RespuestaAjax) {
                    console.log(RespuestaAjax);

                    /*
                     * Maneja la respuesta de la función SendAjaxRequest (AJAX).
                     * Si la respuesta es exitosa (res == 1) y el elemento activador tiene la clase 'visible-on',
                     * alterna el estado de visibilidad representado por el atributo 'data-vs' y actualiza el texto del elemento
                     * entre "on" y "off". Si la respuesta no es exitosa, muestra un mensaje de alerta con el mensaje recibido.
                     *
                     * @param {Object} RespuestaAjax - Objeto que contiene la respuesta de la petición AJAX.
                     * @param {Event} event - Evento que dispara la función, utilizado para identificar el elemento objetivo.
                     */
                    if (RespuestaAjax.res == 1) {

                        //alert(event.target.dataset.id);
                        if (event.target.dataset.vs == "1") {
                            event.target.dataset.vs = 0;
                            event.target.textContent = "off";
                        } else {
                            event.target.dataset.vs = 1;
                            event.target.textContent = "on";

                        }
                    } else {
                        alert(RespuestaAjax.msg);
                    }
                }).catch(function(error) {
                    alert('Error:' + error);
                });
            }

        });

        function SendAjaxRequest(data) {
            return fetch('request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: data
                })
                .then(response => response.json())
                .then(json => {
                    //alert(JSON.stringify(json));
                    return json;
                }).catch(error => {
                    alert('Error:' + error);
                });
        }

        function modalCrear(frm) {
            const data = {};
            form = document.getElementById(frm);
            new FormData(form).forEach((val, key) => {
                data[key] = val;
            });
            var modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));           
                
            SendAjaxRequest(data).then(function(RespuestaAjax) {
                console.log(RespuestaAjax);

                /*
                 * Maneja la respuesta de la función SendAjaxRequest (AJAX).
                 * Si la respuesta es exitosa (res == 1) y el elemento activador tiene la clase 'visible-on',
                 * alterna el estado de visibilidad representado por el atributo 'data-vs' y actualiza el texto del elemento
                 * entre "on" y "off". Si la respuesta no es exitosa, muestra un mensaje de alerta con el mensaje recibido.
                 *
                 * @param {Object} RespuestaAjax - Objeto que contiene la respuesta de la petición AJAX.
                 * @param {Event} event - Evento que dispara la función, utilizado para identificar el elemento objetivo.
                 */
                if (RespuestaAjax.res == 1) {
                    modal.hide();
                    location.reload();
                   
                } else {
                    alert(RespuestaAjax.msg);
                }
            }).catch(function(error) {
                alert('Error:' + error);
            });

           

        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Modal de Usuario -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Acción de Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <form id="frmUsers">
                        <label for="username" class="username">Nombre de usuario</label>
                        <input type="text" name="username" id="username"><br>
                        <label for="psw" class="psw">Password</label>
                        <input type="password" name="psw" id="psw"><br>
                        <label for="visible" class="visible">Visible</label>
                        <select name="visible" id="visible">
                            <option value="0" selected>Off</option>
                            <option value="1">On</option>
                        </select><br>
                        <label for="roll" class="roll">Perfil</label>
                        <select name="roll" id="roll">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                            <option value="guest">Guest</option>
                        </select>
                        <input type="hidden" name="op" value="new">
                        <input type="hidden" name="tb" value="users">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="modalActionBtn" onclick="modalCrear('frmUsers')">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación -->
    <div class="modal fade" id="modalAccion" tabindex="-1" aria-labelledby="modalAccionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAccionLabel">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de realizar esta acción?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>