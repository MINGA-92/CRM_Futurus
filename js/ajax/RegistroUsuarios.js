$(document).ready(function () {

  //Iniciador de todos los <select>   

  $("#ContSelCampana").hide();
  $("#ContSelTipoPermiso").hide();
  $("#ContSelCargo").hide();
  $("#ContSelGrupo").hide();
  $("#ContSelSubGrupo").hide();
  $("#ContNuevoGrupo").hide();

  document.getElementById('Grupo').setAttribute('required', 'required');

  $("#Campana").append('<option> Una opcion </option>');

});

//Consulta de campañas segun el cliente
$('#Cliente').change(function () {
  var opcion = $("#Cliente").val();
  event.preventDefault();
  $.ajax({
    url: 'consulta_campanas.php',
    type: 'GET',
    cache: 'false',
    data: { opcion: opcion }
  }).done(function (data) {
    resultado = String(data);

    $("#Campana").text('');
    $("#Campana").append('<option selected hidden disabled>Selecciona una consulta</option>');
    $("#Campana").append(resultado);
    $("#ContSelCampana").show();


  });
});

//Consulta campaña
$('#Campana').change(function () {
  $("#ContSelTipoPermiso").show();
});

//Consulta permiso
$('#TipoPermiso').change(function () {
  $("#ContSelCargo").show();
});


//Consulta de grupos 
$('#Cargo').change(function () {

  var cliente = $("#Cliente").val();
  var campana = $("#Campana").val();
  var cargo = $("#Cargo").val();

  event.preventDefault();

  if ((cargo == 'Agente') || (cargo == 'AgenteVisitas') || (cargo == 'AgenteLegalizador')) {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana, cargo: cargo }
    }).done(function (data) {
      resultado = String(data);
      $("#ContNuevoGrupo").hide();
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#Grupo").append(resultado);

      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').removeAttribute('required');
      document.getElementById('Grupo').setAttribute('required', 'required');

      $("#ContSelGrupo").show();
      $("#ContSelSubGrupo").hide();

    });
  } else if (cargo == 'Supervisor') {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana, cargo: cargo }
    }).done(function (data) {
      resultado = String(data);
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden value="Supervisor">Supervisor</option>');
      $("#ContSelGrupo").hide();
      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').setAttribute('required', 'required');
      document.getElementById('Grupo').removeAttribute('value');

      $("#SubGrupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#SubGrupo").append(resultado);
      $("#SubGrupo").append('<option value="Crear grupo Nuevo">Crear grupo Nuevo</option>');
      $("#ContSelSubGrupo").show();

    });
  } else {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana, cargo: cargo }
    }).done(function (data) {
      resultado = String(data);
      $("#Grupo").text('');
      $("#Grupo").append('<option selected value="' + cargo + '">' + cargo + '</option>');
      $("#ContSelGrupo").hide();

      $("#SubGrupo").text('');
      document.getElementById('SubGrupo').setAttribute('required', 'required');
      document.getElementById('Grupo').removeAttribute('value');

      $("#SubGrupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#SubGrupo").append(resultado);
      $("#SubGrupo").append('<option value="TODOS">TODOS</option>');
      $("#SubGrupo").append('<option value="Crear grupo Nuevo">Crear grupo Nuevo</option>');
      $("#ContSelSubGrupo").show();

    });
  }


});

//Opcion de nuevo grupo
$('#SubGrupo').change(function () {
  var SubGrupo = $("#SubGrupo").val();
  if (SubGrupo == "Crear grupo Nuevo") {
    $("#ContNuevoGrupo").show();
  } else {
    $("#NuevoGrupo").val('');
    $("#ContNuevoGrupo").hide();
  }


});


$('body').on('click', '#Guardar', function () {
  let form_data = new FormData();

  var Agente = $("#Agente").val();
  form_data.append('Agente', Agente);
  var Usuario = $("#Usuario").val().toUpperCase();
  form_data.append('Usuario', Usuario);
  var Contrasena = $("#Contrasena").val();
  form_data.append('Contrasena', Contrasena);
  var Identificacion = $("#Identificacion").val();
  form_data.append('Identificacion', Identificacion);
  var Login = $("#Login").val();
  form_data.append('Login', Login);
  var Nombre = $("#Nombre").val().toUpperCase();
  form_data.append('Nombre', Nombre);
  var Nombre2 = $("#Nombre2").val().toUpperCase();
  form_data.append('Nombre2', Nombre2);
  var Apellido = $("#Apellido").val().toUpperCase();
  form_data.append('Apellido', Apellido);
  var Apellido2 = $("#Apellido2").val().toUpperCase();
  form_data.append('Apellido2', Apellido2);
  var Cliente = $("#Cliente").val();
  form_data.append('Cliente', Cliente);
  var Campana = $("#Campana").val();
  form_data.append('Campana', Campana);
  var TipoPermiso = $("#TipoPermiso").val();
  form_data.append('TipoPermiso', TipoPermiso);
  var Cargo = $("#Cargo").val();
  form_data.append('Cargo', Cargo);
  var Grupo = $("#Grupo").val();
  form_data.append('Grupo', Grupo);
  var SubGrupo = $("#SubGrupo").val();
  form_data.append('SubGrupo', SubGrupo);
  var NuevoGrupo = $('#NuevoGrupo').val().toUpperCase();
  form_data.append('NuevoGrupo', NuevoGrupo);
  var AutorizacionRegistro = $('#AutorizacionRegistro').val();
  form_data.append('AutorizacionRegistro', AutorizacionRegistro);


  if ((Agente == null) || (Agente == "")) {
    alert("Se tiene que diligenciar todos los campos del formulario");
  } else if ((Usuario == null) || (Usuario == "")) {
    alert("Se Tiene Que Diligenciar El Campo Usuario");

  } else if ((Contrasena == null) || (Contrasena == "")) {
    alert("Se Tiene Que Diligenciar El Campo Contraseña");

  } else if ((Identificacion == null) || (Identificacion == "")) {
    alert("Se Tiene Que Diligenciar El Campo Identificacion");

  } else if ((Login == null) || (Login == "")) {
    alert("Se Tiene Que Diligenciar El Campo Login");

  } else if ((Nombre == null) || (Nombre == "")) {
    alert("Se Tiene Que Diligenciar El Campo Nombre");

  } else if ((Apellido == null) || (Apellido == "")) {
    alert("Se Tiene Que Diligenciar El Campo Apellido");

  } else if ((Apellido2 == null) || (Apellido2 == "")) {
    alert("Se Tiene Que Diligenciar El Campo Segundo Apellido");

  } else if ((Cliente == null) || (Cliente == "")) {
    alert("Se Tiene Que Diligenciar El Campo Cliente");

  } else if ((Campana == null) || (Campana == "")) {
    alert("Se Tiene Que Diligenciar El Campo Campaña");

  } else if ((TipoPermiso == null) || (TipoPermiso == "")) {
    alert("Se Tiene Que Diligenciar El Campo Tipo De Permiso");

  } else if ((Cargo == null) || (Cargo == "")) {
    alert("Se Tiene Que Diligenciar El Campo Cargo");

  } else if ((Cargo == 'Agente') || (Cargo == 'AgenteVisitas') || (Cargo == 'AgenteLegalizador')) {
    if ((Grupo == null) || (Grupo == "")) {
      alert("Se Tiene Que Diligenciar El Campo Grupo");
    } else {
      var SubGrupo = "N/A";
      form_data.append('SubGrupo', SubGrupo);

      $.ajax({
        url: "GuardarUsuario.php",
        dataType: "json",
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        success: function (php_response) {
          Respuesta = php_response.msg;
          if (Respuesta == "Ok") {
            alert("!Gestion Realizada Exitosamente!");
            window.location = "ListadoUsuarios.php";
          } else if (Respuesta == "El usuario ya existe") {
            alert("El usuario ya se encuentra registrado");
          } else if (Respuesta == "El documento ya existe") {
            alert("El documento ya se encuentra registrado");
          } else if (Respuesta == "Error") {
            alert("Error al guardar la informacion, por favor ponerse en contacto con el administrador");
            console.log(php_response.msg);
          }
        },
        error: function (php_response) {
          php_response = JSON.stringify(php_response);
          alert("Error en la comunicacion con el servidor!");
          console.log(php_response);
        }
      });

    }
  } else {
    if ((SubGrupo == null) || (SubGrupo == "")) {
      alert("Se Tiene Que Diligenciar El Campo SubGrupo");
    } else {
      var Grupo = "N/A";
      form_data.append('Grupo', Grupo);

      $.ajax({
        url: "GuardarUsuario.php",
        dataType: "json",
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        success: function (php_response) {
          Respuesta = php_response.msg;
          if (Respuesta == "Ok") {
            alert("!Gestion Realizada Exitosamente!");
            window.location = "RegistroUsuarios.php";
          } else if (Respuesta == "El usuario ya existe") {
            alert("El usuario ya se encuentra registrado");
          } else if (Respuesta == "El documento ya existe") {
            alert("El documento ya se encuentra registrado");
          } else if (Respuesta == "Error") {
            alert("Error al guardar la informacion, por favor ponerse en contacto con el administrador");
            console.log(php_response.msg);
          }
        },
        error: function (php_response) {
          php_response = JSON.stringify(php_response);
          alert("Error en la comunicacion con el servidor!");
          console.log(php_response);
        }
      });
    }
  }
});

