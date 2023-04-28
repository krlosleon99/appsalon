<h1 class="nombre-pagina">Reestablece tu password</h1>
<p class="descripcion-pagina">Coloca una contraseña de al menos 6 caracteres para continuar.</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password:</label>
            <input 
                type="password"
                id="password"
                name="password"
                placeholder="Coloca tu nuevo password"
            >
    </div>
    <input type="submit" value="Guardar nuevo password" class="boton">
</form> 

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>