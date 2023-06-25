<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nuevo Password</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if ($mostrarFormulario): ?>
            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Password</label>
                    <input 
                        type="password"
                        id="password"
                        placeholder="Tu Password"
                        name="password"
                    >
                </div>
                <div class="campo">
                    <label for="password2">Repetir password</label>
                    <input 
                        type="password"
                        id="password2"
                        placeholder="Reingresa tu Password"
                        name="password2"
                    >
                </div>
                <input type="submit" value="Guardar Password" class="boton">
            </form>
        <?php endif; ?>
        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
            <a href="/crear">Crear Cuenta</a>
        </div>
    </div><!-- .contenedor-sm -->
</div>