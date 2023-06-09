<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>" /> <!-- Limpia cache CSS -->
    <meta charset="utf-8" />
    <!-- Importar toast de JS -->
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <title>Formulario de registro</title>
</head>

<body>
    <form method="POST" action="">
        <div class="container">
            <div class="containerF">
                <h2 class="formTitle"><em>Formulario de registro</em></h2>

                <label for="nombre" class="formLabel">Nombre <span><em>(requerido):</em></spam></label>
                <input type="text" name="nombre" maxlength="20" class="formInput" required/>

                <label for="primerApellido" class="formLabel">Primer apellido <span><em>(requerido):</em></spam></label>
                <input type="text" name="primerApellido" maxlength="20" class="formInput" required/>

                <label for="segundoApellido" class="formLabel">Segundo apellido <span><em>(requerido):</em></spam></label>
                <input type="text" name="segundoApellido" maxlength="20" class="formInput" required/>

                <label for="email" class="formLabel">Email <span><em>(requerido):</em></spam></label>
                <input type="email" name="email" class="formInput" maxlength="20"  required/>

                <label for="loginUsuario" class="formLabel">Usuario <span><em>(requerido):</em></spam></label>
                <input type="text" name="loginUsuario" maxlength="20" class="formInput" required/>

                <label for="pass" class="formLabel">Contraseña <span><em>(requerido):</em></spam></label>
                <input type="password" name="pass" class="formInput" minlength="4" maxlength="8" required/>

                <input type="submit" name="submit" class="formSubmit" value="LOGIN">
                <button type="button" name="consulta" class="formConsulta" id="consultar">CONSULTA</button>
            </div>

            <?php

            if($_POST) {
                $nombre = $_POST['nombre'];
                $primerApellido = $_POST['primerApellido'];
                $segundoApellido = $_POST['segundoApellido'];
                $email = $_POST['email'];
                $loginUsuario = $_POST['loginUsuario'];
                $pass = $_POST['pass'];

                $patternEmail = "'[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$'";
                $patternPass = "'^.{4,8}$'";

            // Conexion con PDO
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bbdd";    

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO usuarios (nombre, primerApellido, segundoApellido, email, loginUsuario, pass) VALUES ('$nombre', '$primerApellido', '$segundoApellido', '$email', '$loginUsuario', '$pass')"; 

            // Consulta para buscar el email en la bbdd
            $sql2 = "SELECT * FROM usuarios WHERE email = '$email'";
            $result = $conn->query($sql2);

            /* Si los campos estan introducidos correctamente, comprueba si el email existe en la BBDD. Si no existe, inserto los datos en la BBDD y se muesta el boton "consultar", que al hacerle clic, obtiene
            todos los datos para mostrarlos */
            if(preg_match($patternEmail, $email) && preg_match($patternPass, $pass) && $nombre !== '' && $primerApellido !== '' && $segundoApellido !== '' && $loginUsuario !== '') {
                if($result->num_rows > 0) {
                    echo '<script type="text/javascript">toastr.warning("El correo electrónico existe en la base de datos")</script>';
                } else {
                    if($conn->query($sql) === TRUE) {
                        echo '<script type="text/javascript">toastr.success("Registro completado con éxito")</script>';
                        $sql3 = "SELECT * FROM usuarios";
                        $result2 = $conn->query($sql3);
                        if($result2->num_rows > 0) {
                            echo '<div class="containerLista">';
                            echo '<ul id="lista">';
                            while($row = $result2->fetch_assoc()) {
                                echo "<li>" ."<u>Nombre:</u> ". $row['nombre'] . ", <u>primer apellido:</u> " . $row["primerApellido"] . ", <u>segundo apellido:</u> " . $row["segundoApellido"] . ", <u>email:</u> " . $row["email"] . ", <u>usuario:</u> " . $row["loginUsuario"] . "</li>";
                            }
                            echo "</ul>";
                            echo '</div>';
                            echo "<style> #consultar{visibility: visible}; </style>";
                        } else {
                            echo '<script type="text/javascript">toastr.error("No se encontraron datos")</script>';
                        }
                    } else {
                        echo '<script type="text/javascript">toastr.error("Datos erróneos")</script>';
                    }
                }
            } else {
                echo '<script type="text/javascript">toastr.error("Rellene correctamente los campos")</script>';
            }
            $conn->close();
            }
            ?>
            </div>
        </form>
<script src="index.js?1"></script>
</body>
</html>