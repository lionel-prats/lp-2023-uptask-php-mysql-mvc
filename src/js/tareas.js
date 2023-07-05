// IIFE -> Inmediately Invoke Function Expression (VIDEO 615)
(function() {

    obtenerTareas(); // consumir /api/tareas (VIDEO 630)
    // boton para mostrar la Ventana Modal para agregar una tarea 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    async function obtenerTareas(){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url)
            const resultado = await respuesta.json()
            const {tareas} = resultado
            mostrarTareas(tareas)
        } catch (error) {
            console.log(error);
        }
    }
    function mostrarTareas(tareas){
        console.log("mostrando", tareas);
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
                const tarea = document.querySelector('#tarea').value.trim();
                if(tarea === '') {
                    const legendFormCrearTarea = document.querySelector('.formulario legend');
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', legendFormCrearTarea);
                    return;
                }
                agregarTarea(tarea);
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
        proyecto = obtenerProyecto();

        const datos = new FormData(); // objeto nativo de JS para enviar datos al servidor (VIDEO 517)
        datos.append('nombre', tarea);
        datos.append('proyectoId', proyecto);

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
                setTimeout(() => {
                    modal.remove();
                }, 3000);
            }
            
        } catch (error) {
            console.log(error);
        }
    }
    function obtenerProyecto(){
        // window.location nos proporciona informacion acerca de la url de la vista que se esta renderizando (VIDEO 626)
        // const proyecto -> objeto JS cuyas propiedades son los parametros de la query de una peticion GET (VIDEO 626)
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
})();