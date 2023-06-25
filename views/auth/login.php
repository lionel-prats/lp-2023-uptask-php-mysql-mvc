<div class="contenedor login">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if($mostrarFormulario): ?>
            <form class="formulario" method="POST" novalidate>
                <div class="campo">
                    <label for="email">Email</label>
                    <input 
                        type="email"
                        id="email"
                        placeholder="Tu Email"
                        name="email"
                        value="<?= $usuario->email ?? ""; ?>"
                    >
                </div>
                <div class="campo">
                    <label for="password">Password</label>
                    <input 
                        type="password"
                        id="password"
                        placeholder="Tu Password"
                        name="password"
                    >
                </div>
                <input type="submit" value="Iniciar Sesión" class="boton">
            </form>
        <?php endif; ?>
        <div class="acciones">
            <a href="/crear">Crear Cuenta</a>
            <a href="/olvide">Olvidé mi Password</a>
        </div>
    </div><!-- .contenedor-sm -->
</div>