<?php

$alerta='';

session_start();
if(!empty($_SESSION['s_active'])){

    header("location: sistema/");
}

else{



    if(!empty($_POST)){

        if(empty($_POST['user']) || empty($_POST['pass'])){
            $alerta='ingrese su usuario y clave'; }
    else{

            require_once("conexion.php");

            $user = mysqli_real_escape_string($conection,$_POST['user']);
            $pass = md5(mysqli_real_escape_string($conection,$_POST['pass']));


    
            $query ="SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.idrol, r.rol
                            FROM usuario u
                            INNER JOIN rol r  
                            ON u.rol = r.idrol
                            WHERE  u.usuario = '$user' AND u.clave = '$pass' ";
            $queryconect = mysqli_query($conection,$query);
            mysqli_close($conection);
            $result = mysqli_num_rows($queryconect);



            if ($result>0){
                $data = mysqli_fetch_array($queryconect);
               
                $_SESSION['s_active']=true;
                $_SESSION['s_iduser']= $data['idusuario'];
                $_SESSION['s_nombre']= $data['nombre'];
                $_SESSION['s_email']= $data['correo'];
                $_SESSION['s_user']= $data['usuario'];
                $_SESSION['s_rol']= $data['idrol'];
                $_SESSION['rol_name']= $data['rol'];
                header("location: sistema/");
            
            }
            else{

            $alerta ='El usuario y/o contraseña son incorrectos'; 
                session_destroy();
            }

        }

    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
  

    
    <title>Login sistema de facturacion</title>
</head>
<body>
    
    <section class="contaniner">
    
    <form class="formulario" action="" method="post">
    <h3>Iniciar sesion</h3>
    
    <img class="candado" src="./img/candado.png" alt="">
    <input type="text" name="user" placeholder="Usuario...">
    <input type="password" name="pass" placeholder="Contraseña..."> 
    <div class="mensaje">   

      <?php echo isset($alerta) ? $alerta : ""; ?>
      </div>

    <input class="btn" type="submit" value="INGRESAR">
    </form>


    </section>
    
</body>
</html>