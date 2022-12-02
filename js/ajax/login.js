$("#login").submit(function(e) {
    var usuario = $("#username").val();
    var password = $("#password").val();
    e.preventDefault();
    $.ajax({
        url: "php/login.php",
        type: "POST",
        data: {
            usuario: usuario,
            password: password
        }
    }).done(function(data) {
        resultado = String(data);
        if (resultado == '1') {
            window.location = "php/direccionamiento.php";
        } else if (resultado == '0') {
            alert("Usuario y/o contraseña incorrectos");
        } else {
            alert('Error en la validacion de los datos');
            console.log(data);           
        }
    })
});