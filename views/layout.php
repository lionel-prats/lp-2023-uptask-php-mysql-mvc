<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPTask | <?=  $titulo ?? ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Open+Sans&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>

    <?php echo $contenido; ?>
    <?php echo $script ?? ''; ?>

    <?php 
        /* echo '
            <div class="modal">
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
            </div>
        '; */
    ?>

</body>
</html>