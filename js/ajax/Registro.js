$(document).ready(function () {
    //Funcion para mostrar la contraseña y ocultarla
    $("#mostrar").click(function () {
        var input = $("#ccontrasena").attr("type");
        if (input === "password") {
            $("#ccontrasena").attr("type", "text");
            $("#iconoContra").removeClass();
            $("#iconoContra").addClass("fal icon-lock-open");
        } else {
            $("#ccontrasena").attr("type", "password");
            $("#iconoContra").removeClass();
            $("#iconoContra").addClass("fal icon-lock");
        }
    })
})