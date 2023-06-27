// IIFE -> Inmediately Invoke Function Expression (VIDEO 615)
(function() {
    // boton para mostrar el modal de agregar tarea 
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        console.log("desde mostrarFormulario");
    }
})();
