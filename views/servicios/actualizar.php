<h2 class="nombre-pagina">Actualizar servicios</h2>
<p class="descripcion-pagina">Modifica los campos del servicio</p>

<?php 
    include_once __DIR__ . '/../templates/barra.php'; 
    include_once __DIR__ . '/../templates/alertas.php'; 
?>

<form class="formulario" method="POST">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" value="Actualizar" class="boton">
    
</form>