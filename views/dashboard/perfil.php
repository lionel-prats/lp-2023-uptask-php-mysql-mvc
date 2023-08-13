<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/header-dashboard.php'; ?>
    <form action="" method="POST" class="formulario">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
                type="text"
                value="<?php echo $usuario["nombre"]; ?>"
                name="nombre"    
                placeholder="Tu nombre"
                id="nombre"
            >
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="email"
                value="<?php echo $usuario["email"]; ?>"
                name="email"    
                placeholder="Tu email"
                id="email"
            >
        </div>
        <input type="submit" value="Guardar cambios">
    </form>
</div>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>