<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestalecer contraseña</p>
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
                        value = "<?= $email; ?>"
                    >
                </div>
                <input type="submit" value="Enviar" class="boton">
            </form>
        <?php endif; ?>
        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
            <a href="/crear">Crear Cuenta</a>
        </div>
    </div><!-- .contenedor-sm -->
</div>