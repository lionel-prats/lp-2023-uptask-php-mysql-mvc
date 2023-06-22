<div class="contenedor">
    <h1>UPTask</h1>
    <p class="tagLine">Crea y Administra tus Proyectos</p>
</div>
<div class="contenedor-sm">
    <p class="descripcion-pagina">Iniciar Sesión</p>
    <form class="formulario" method="POST">
        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="email"
                id="email"
                placeholder="Tu Email"
                name="email"
            >
        </div>
        <div class="campo">
            <label for="password">Password</label>
            <input 
                type="password"
                id="password"
                placeholder="Tu Password"
                name="password"
            >
        </div>
        <input type="submit" value="Iniciar Sesión" class="boton">
    </form>
    <div class="acciones">
        <a href="/crear">Crear Cuenta</a>
        <a href="/olvide">Olvidé mi Password</a>
    </div>
</div><!-- .contenedor-sm -->