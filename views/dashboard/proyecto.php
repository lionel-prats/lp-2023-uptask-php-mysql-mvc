<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<div class="contenedor-sm">
    <div class="contenedor-nueva-tarea">
        <button
            type="button"
            class="agregar-tarea"
            id="agregar-tarea"
        >&#43; Nueva Tarea</button>
    </div>

    <div id="filtros" class="filtros">
        <div class="filtros-inputs">
            <h2>Filtros</h2>
            <div class="campo">
                <label for="todas">Todas</label>
                <input 
                    type="radio"
                    id="todas"
                    name="filtro"
                    value=""
                    checked
                >
            </div>
            <div class="campo">
                <label for="completadas">Completadas</label>
                <input 
                    type="radio"
                    id="completadas"
                    name="filtro"
                    value="1"
                >
            </div>
            <div class="campo">
                <label for="pendientes">Pendientes</label>
                <input 
                    type="radio"
                    id="pendientes"
                    name="filtro"
                    value="0"
                >
            </div>
        </div>
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
    $link = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>';
    $script = '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tareas.js"></script>
    ';
?>