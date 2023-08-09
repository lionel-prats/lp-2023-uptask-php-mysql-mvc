// IIFE -> Inmediately Invoke Function Expression (VIDEO 615)
(function() {

    obtenerTareas(); // consumir /api/tareas (VIDEO 630)

    // array que va a tener el listado de tareas asociadas a un proyecto (cada tarea es un objeto que viene de la peticion a /api/tareas?id=xxx), y que se va a actualizar cada vez que el usuario agregue una nueva tarea (nos evitamos peticiones al SERVER) // VIDEO 637
    let tareas = []; // ver apartado del VIDEO 637 en 4-lp-2023-uptask-php-mysql-mvc.txt 

    // boton para mostrar la Ventana Modal para agregar una tarea 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    
    // quedo escuchando por un click en el boton de crear nueva tarea, en la vista de un proyecto
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);
    
    async function obtenerTareas(){
        try {
            const id = obtenerProyecto(); // token del proyecto, sacado de la URL de la vista
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url)
            const resultado = await respuesta.json()

            // const {tareas} = resultado
            tareas = resultado.tareas; // VIDEO 637}

            console.log(tareas);

            mostrarTareas()

        } catch (error) {
            console.log(error);
        }
    }
    // la funcion mostrarTareas renderiza las tareas de un proyecto en http://localhost:3000/proyecto?id=xxx
    function mostrarTareas(){
        limpiarTareas(); // vacío el <ul id="listado-tareas" class="listado-tareas">, contenedor de todas las tareas asociadas a un proyecto, para renderizarlas nuevamente con la posible actualizacion de una nueva tarea creada por el usuario
        if(tareas.length === 0){
            const contenedorTareas = document.querySelector("#listado-tareas")
            const textoNoTareas = document.createElement("LI")
            textoNoTareas.textContent = "No hay tareas"
            textoNoTareas.classList.add("no-tareas")
            contenedorTareas.appendChild(textoNoTareas)
            return
        }
        const estados = {
            0: "Pendiente",
            1: "Completa"
        }
        tareas.forEach( tarea => {
            // console.log(tarea);
            const contenedorTarea = document.createElement("LI")
            contenedorTarea.dataset.tareaId = tarea.id
            contenedorTarea.classList.add("tarea");

            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            // botones
            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea}) // le paso un objeto-copia identico al de la tarea iterada, para que al cambiar su estado en cambiarEstadoTarea(), no mute el array de tareas original (VIDEO 638)
            }
            
            // boton de eliminar tarea
            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar";
            
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            
            const listadoTareas = document.querySelector("#listado-tareas");
            listadoTareas.appendChild(contenedorTarea);
        })
    }
    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text"
                        id="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual"
                        name="tarea"
                    >
                </div>
                <div class="opciones">
                    <input 
                        type="submit"
                        class="submit-nueva-tarea"
                        value="Añadir Tarea"
                    >
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;
        document.querySelector('.dashboard').appendChild(modal);
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        // Modelo de concurrencia y loop de eventos en JS
        // https://developer.mozilla.org/es/docs/Web/JavaScript/Event_loop 
        // ver 4-lp-2023-uptask-php-mysql-mvc.txt -> VIDEO 618 
        modal.addEventListener('click', function(e){ // VIDEO 618
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
            function submitFormularioNuevaTarea(){
                const tarea = document.querySelector('#tarea').value.trim(); // value del input en el formulario modal de creacion de nueva tarea
                if(tarea === '') {
                    const legendFormCrearTarea = document.querySelector('.formulario legend');
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', legendFormCrearTarea);
                    return;
                }
                agregarTarea(tarea); // ejecutamos la funcion que hara un CREATE en tareas
            }
        })        
    }
    // muestra un mensaje en la interfaz (VIDEO 621)
    function mostrarAlerta(mensaje, tipo, referencia){
        const alertaPrevia = document.querySelector('.alerta')
        if(alertaPrevia)
            alertaPrevia.remove();
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        setTimeout(() => {
            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling)
        }, 30);
        // referencia.insertAdjacentElement('afterend', alerta);
        // referencia.parentElement.insertBefore(alerta, referencia);
    }
    // Fetch al servidor para agregar una tarea a la tabla tareas
    async function agregarTarea(tarea){
        // identificador del proyecto al cual le vamos a agrregar una tarea (tabla "proyectos", campo "url")
        proyecto = obtenerProyecto(); // esta funcion me retorna el token (esta en la URL de la vista del proyecto) en la DB del proyecto para el cual quiero agregar una tarea nueva

        const datos = new FormData(); // objeto nativo de JS para enviar datos al servidor (VIDEO 517)
        datos.append('nombre', tarea);
        datos.append('proyectoId', proyecto);

        /* 
        datos = {
            nombre: "el_nombre_que_el_usuario_le_dio_a_la_tarea"
            proyectoId = "42f4716f6313dbde3732c555198c7dd3"
        } 
        */

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            const legendFormCrearTarea = document.querySelector('.formulario legend');
            mostrarAlerta(resultado.mensaje, resultado.tipo, legendFormCrearTarea);
            
            if(resultado.tipo === 'exito'){
                document.querySelector('.submit-nueva-tarea').disabled = true;
                const formulario = document.querySelector('.formulario');
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    formulario.classList.add('cerrar');
                }, 2500);
                setTimeout(() => {
                    modal.remove();
                    // window.location.reload(); // metodo JS para recargar la pagina en la que nos encontramos (VIDEO 636)
                }, 3000);

                // agregar el objeto de la tarea creada, al global de tareas (VIDEO 637)
                const tareaObj = {
                    id: String(resultado.id), // convierto un number en string
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                // una forma de agregar un nuevo objeto al array de tareas (VIDEO 637)
                tareas = [...tareas, tareaObj];
                
                console.clear()
                console.log(tareas);
                
                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
        }
    }
    
    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado
        actualizarTarea(tarea)
    }

    function actualizarTarea(tarea) {
        console.log(tarea);
    }

    function obtenerProyecto(){
        // window.location nos proporciona informacion acerca de la url de la vista que se esta renderizando (VIDEO 626)
        const proyectoParams = new URLSearchParams(window.location.search);
        // proyecto -> objeto JS cuyas propiedades son los parametros de la query de una peticion GET (VIDEO 626)
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id; 
    }
    function limpiarTareas(){
        const listadoTareas = document.querySelector("#listado-tareas");
        // VIDEO 637 - de esta manera, es mas rapido limpiar de elementos hijo de <ul id="listado-tareas"> en el DOM, que usando innerHTML
        while(listadoTareas.firstChild)
            // listadoTareas.firstChild.remove();
            listadoTareas.removeChild(listadoTareas.firstChild);
        // listadoTareas.innerHTML = "";
    }
})();