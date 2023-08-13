<div class="barra-mobile"> <!-- barra dispositivos moviles  -->
    <h1>UpTask</h1>
    <div class="menu">
        <img src="build/img/menu.svg" alt="imagen menu" id="mobile-menu">
    </div>
</div>
<div class="barra"> <!-- barra a partir de tablet -->
    <p>Hola <span><?= $_SESSION["nombre"]?></span></p>
    <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
</div>