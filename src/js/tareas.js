// IIFE -> Inmediately Invoke Function Expression (VIDEO 615)
(function() {

    obtenerTareas(); // consumir /api/tareas (VIDEO 630)

    // array que va a tener el listado de tareas asociadas a un proyecto (cada tarea es un objeto que viene de la peticion a /api/tareas?id=xxx), y que se va a actualizar cada vez que el usuario agregue una nueva tarea (nos evitamos peticiones al SERVER) // VIDEO 637
    let tareas = []; // ver apartado del VIDEO 637 en 4-lp-2023-uptask-php-mysql-mvc.txt 

    let filtradas = []

    // boton para mostrar la Ventana Modal para agregar una tarea 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    
    // una forma de quedar escuchando por un click en el boton de crear nueva tarea, en la vista de un proyecto, y en ese caso ejecutar la funcion mostrarFormulario (con esta sintaxis, al ejecutar mostrarFormulario le estamos pasando el event implicitamente como parametro) (esta opcion estuvo descomentada hasta el VIDEO 643. En el 644, el profesor comenta esta forma y escribe la de abajo, ya que es necesario no pasar nada implicitamente cuando se ejecute mostrarFormulario para poder reutilizar el form tanto para crear como para editar una tarea - ver video 644 si es necesario -)
    // nuevaTareaBtn.addEventListener('click', mostrarFormulario);
    
    // otra forma de hacer lo de arriba, con la diferencia que de esta manera no le estamos pasando nada implicitamente como parametro a mostrarFormulario (VIDEO 644)
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario()
    });

    // filtros de busqueda 
    const filtros = document.querySelectorAll("#filtros input[type='radio']");

    // escuchando por click en los filtros de tarea (VIDEO 649)
    filtros.forEach( radio => {
        radio.addEventListener("input", filtrarTareas) // cuando mandamos a ejecutar una funcion con esta sintaxis estamos pasandole implicitamente el evento (VIDEO 649)
    })

    function filtrarTareas(e){
        const filtro = e.target.value
        if(filtro !== ""){
            filtradas = tareas.filter( tarea => tarea.estado === filtro) 
        } else {
           filtradas = []
        }
        mostrarTareas()
    }
    
    async function obtenerTareas(){
        try {
            const id = obtenerProyecto(); // token del proyecto, sacado de la URL de la vista
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url)
            const resultado = await respuesta.json()

            // const {tareas} = resultado
            tareas = resultado.tareas; // VIDEO 637}

            //console.log(tareas);

            mostrarTareas()

        } catch (error) {
            console.log(error);
        }
    }
    // la funcion mostrarTareas renderiza las tareas de un proyecto en http://localhost:3000/proyecto?id=xxx
    function mostrarTareas(){
        limpiarTareas(); // vacío el <ul id="listado-tareas" class="listado-tareas">, contenedor de todas las tareas asociadas a un proyecto, para renderizarlas nuevamente con la posible actualizacion de una nueva tarea creada por el usuario

        totalPendientes() 
        totalCompletas() 

        const arrayTareas = filtradas.length ? filtradas : tareas
        //console.log(filtradas);

        if(arrayTareas.length === 0){
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
        arrayTareas.forEach( tarea => {
            // console.log(tarea);
            const contenedorTarea = document.createElement("LI")
            contenedorTarea.dataset.tareaId = tarea.id
            contenedorTarea.classList.add("tarea");

            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(editar = true, {... tarea}) // ejecutamos mostrarFormulario al momento del click sobre el nombre de una tarea, para que el usuario pueda editar el nombre (VIDEO 644)
            }

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            // botones
            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea}) // ante un click en el boton de estado de una tarea, ejecuto la funcion que hara un UPDATE en la DB; le paso un objeto-copia identico al de la tarea iterada durante el proceso de renderizacion de tareas, para que al cambiar su estado en cambiarEstadoTarea(), no mute el array de tareas original (VIDEO 638)
            }
            
            // boton de eliminar tarea
            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar";
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea}) 
            }
            
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            
            const listadoTareas = document.querySelector("#listado-tareas");
            listadoTareas.appendChild(contenedorTarea);
        })
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter( tarea => tarea.estado === "0")
        const pendientesRadio = document.querySelector("#pendientes")
        if(totalPendientes.length === 0){
            pendientesRadio.disabled = true
        } else {
            pendientesRadio.disabled = false
        }
    }
    function totalCompletas() {
        const totalCompletas = tareas.filter( tarea => tarea.estado === "1")
        const completasRadio = document.querySelector("#completadas")
        if(totalCompletas.length === 0){
            completasRadio.disabled = true
        } else {
            completasRadio.disabled = false
        }
    }

    function mostrarFormulario(editar = false, tarea = {}){
        
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${ editar ? 'Editar tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text"
                        id="tarea"
                        placeholder="${ editar ? 'Edita la tarea' : 'Añadir Tarea al Proyecto Actual' }"
                        name="tarea"
                        value="${ tarea.nombre ? tarea.nombre : '' }"
                    >
                </div>
                <div class="opciones">
                    <input 
                        type="submit"
                        class="submit-nueva-tarea"
                        value="${ editar ? 'Guardar Cambios' : 'Añadir Tarea' }"
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
            if(e.target.classList.contains('cerrar-modal')){ // click en el boton de cancelar, del form de crear tarea
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if(e.target.classList.contains('submit-nueva-tarea')){ // click en el submit del form de crear tarea

                const nombreTarea = document.querySelector('#tarea').value.trim(); // value del input en el formulario modal de creacion de nueva tarea
                if(nombreTarea === '') {
                    const legendFormCrearTarea = document.querySelector('.formulario legend');
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', legendFormCrearTarea);
                    return;
                }
                if(editar) {
                    tarea.nombre = nombreTarea
                    actualizarTarea(tarea)
                } else {
                    agregarTarea(nombreTarea)
                }
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
        // identificador del proyecto al cual le vamos a agregar una tarea (tabla "proyectos", campo "url")
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
                //console.log(tareas);
                
                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
        }
    }
    
    function cambiarEstadoTarea(tarea) { // recibe una copia identica del objeto-tarea iterado en la renderizacion de las tareas (mostrarTareas())
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado
        actualizarTarea(tarea, nuevoEstado)
    }

    async function actualizarTarea(tarea) { // UPDATE del estado de una tarea de un proyecto

        const {id, nombre, estado, proyectoId} = tarea
        const datos = new FormData(); 
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto()); // queryString "id" en /proyecto?id=xxx
        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            if(resultado.respuesta.tipo === "exito") {
                Swal.fire(
                    resultado.respuesta.mensaje, 
                    "", 
                    "success"
                )

                const modal = document.querySelector('.modal');
                if (modal) modal.remove()

                // bloque para actualizar el color y leyenda del boton de estado de una tarea
                tareas = tareas.map(tareaMemoria =>{
                    if(tareaMemoria.id === id){
                        tareaMemoria.nombre = nombre
                        tareaMemoria.estado = estado
                    }
                    return tareaMemoria
                })
                mostrarTareas()
                // fin bloque para actualizar el color y leyenda del boton de estado de una tarea

            }
            
        } catch (error) {
            console.log(error);
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: "¿Eliminar Tarea?",
            confirmButtonText: "Sí",
            showCancelButton: true,
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea)
            } 
        })
    }

    async function eliminarTarea(tarea) {
        const {id, nombre, estado} = tarea
        const datos = new FormData(); 
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto()); // queryString "id" en /proyecto?id=xxx

        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            // console.log(respuesta); // impresion por consola para verificar que el frontend se esta conectando correctamente con el backendd

            const resultado = await respuesta.json();

            console.log(resultado);
            // resultado = [{
            //     resultado: TRUE/FALSE,
            //     mensaje: "Eliminado correctamente"
            //     tipo: "exito"
            // }]
            
            if(resultado.resultado) {

                // ALTERNATIVA 1 DE ALERTA DE BORRADO EXITOSO
                // Swal.fire({
                //     title: resultado.mensaje,
                //     showClass: {
                //         popup: 'animate__animated animate__fadeInDown'
                //     },
                //     hideClass: {
                //         popup: 'animate__animated animate__fadeOutUp'
                //     }
                // })

                // ALTERNATIVA 2 DE ALERTA DE BORRADO EXITOSO
                // mostrarAlerta(
                //     resultado.mensaje,
                //     resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // )

                Swal.fire("Ok", resultado.mensaje, "success")

                // elimino del objeto en memoria la tarea eliminada fisicamente de la DB y actualizo el DOM (VIDEO 642) vvv
                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== id) 
                mostrarTareas()
            
            }
        } catch (error) {
            console.log(error)
        }
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