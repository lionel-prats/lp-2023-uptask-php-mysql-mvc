// IIFE -> Inmediately Invoke Function Expression (VIDEO 615)
(function() {
    // boton para mostrar la Ventana Modal para agregar una tarea 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

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
                // const {id, fecha, hora, servicios} = cita;
                
                // const arrayIdServicios = servicios.map( servicio => servicio.id)
                
                const datos = new FormData(); // objeto nativo de JS para enviar datos al servidor (VIDEO 517)
                datos.append('nombre', tarea);
                datos.append('estado', 1);
                datos.append('proyectoId', 75);
            
                try {
                    const url = 'http://localhost:3000/api/tarea';
                    const respuesta = await fetch(url, {
                        method: 'POST',
                        body: datos
                    });
                    const resultado = await respuesta.json();
                    
                    console.log(resultado);

                } catch (error) {
                    console.log(error);
                }
            }
        })        
    }
})();