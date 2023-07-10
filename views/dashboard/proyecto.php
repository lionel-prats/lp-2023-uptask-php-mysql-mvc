<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<div class="contenedor-sm">
    <div class="contenedor-nueva-tarea">
        <button
            type="button"
            class="agregar-tarea"
            id="agregar-tarea"
        >&#43; Nueva Tarea</button>
    </div>
    <ul id="listado-tareas" class="listado-tareas">
        <!-- 
        * Ejemplo de los <li> correspondientes a cada tarea (VIDEO 634)
        * el <ul> se completa desde tareas.js, donde se hace un fetch al servidor para traer los datos, y se inyectan al HTML con scripting (VIDEO 634)
        <li data-tarea-id="11" class="tarea">
            <p>Jumbo</p>
            <div class="opciones">
                <button class="estado-tarea pendiente" data-estado-tarea="0">Pendiente</button>
                <button class="eliminar-tarea" data-id-tarea="11">Eliminar</button>
            </div>
        </li> 
        -->
    </ul>
</div>
<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
<?php 
    $script = '
        <script src="build/js/tareas.js"></script>
    ';
?>