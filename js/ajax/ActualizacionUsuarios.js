
$(document).ready(function () {
  //$("#sec_campanas").hide();
  //$("#sec_tipopermiso").hide();
  //$("#sec_cargo").hide();
  $("#sec_grupo").hide();
  $("#sec_subgrupo").hide();
  //$("#new-group").hide();

  document.getElementById('Grupo').setAttribute('required', 'required');

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
    $("#sec_campanas").show();
  });
});

//Consulta campaña
$('#Campana').change(function () {
  $("#sec_tipopermiso").show();
});

//Consulta campaña
$('#TipoPermiso').change(function () {
  $("#sec_cargo").show();
});


//Consulta de grupos 
$('#Cargo').change(function () {

  var cliente = $("#Cliente").val();
  var campana = $("#Campana").val();
  var cargo = $("#Cargo").val();

  event.preventDefault();

  if (cargo == 'AgenteVisitas') {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana }
    }).done(function (data) {
      resultado = String(data);
      $("#new-group").hide();
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#Grupo").append(resultado);

      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').removeAttribute('required');
      document.getElementById('Grupo').setAttribute('required', 'required');

      $("#sec_grupo").show();
      $("#sec_subgrupo").hide();
    });
  } else if (cargo == 'Supervisor') {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana }
    }).done(function (data) {
      resultado = String(data);
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden value="Supervisor">Supervisor</option>');
      $("#sec_grupo").hide();
      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').setAttribute('required', 'required');
      document.getElementById('Grupo').removeAttribute('value');

      $("#SubGrupo").append('<option selected hidden disabled>Selecciona una Grupo</option>');
      $("#SubGrupo").append(resultado);
      $("#SubGrupo").append('<option value="Crear grupo Nuevo">Crear grupo Nuevo</option>');
      $("#sec_subgrupo").show();
    });

  } else if (cargo == 'AgenteLegalizador') {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana }
    }).done(function (data) {
      resultado = String(data);
      $("#new-group").hide();
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#Grupo").append(resultado);

      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').removeAttribute('required');
      document.getElementById('Grupo').setAttribute('required', 'required');

      $("#sec_grupo").show();
      $("#sec_subgrupo").hide();

    });

  } else if (cargo == 'AgenteCall') {
    $.ajax({
      url: 'consulta_grupos_x_agentes.php',
      type: 'GET',
      cache: 'false',
      data: { cliente: cliente, campana: campana }
    }).done(function (data) {
      resultado = String(data);
      $("#new-group").hide();
      $("#Grupo").text('');
      $("#Grupo").append('<option selected hidden disabled>Selecciona un Grupo</option>');
      $("#Grupo").append(resultado);

      $("#SubGrupo").text('');

      document.getElementById('SubGrupo').removeAttribute('required');
      document.getElementById('Grupo').setAttribute('required', 'required');

      $("#sec_grupo").show();
      $("#sec_subgrupo").hide();

    });

  }else{
    $("#Grupo").show();
    $("#Grupo").append('Sin Grupo');
  }
})

//Opcion de nuevo grupo
$('#SubGrupo').change(function () {
  var sub_grupo = $("#SubGrupo").val();
  if (sub_grupo == "Crear grupo Nuevo") {
    $("#new-group").show();
  } else {
    $("#nuevo_grupo").val('');
    $("#new-group").hide();
  }
});

//Captura de datos del usuario a registrar
$("#creacion_de_usuarios").submit(function () {
  capturar_datos();
})

//Capturando datos
function capturar_datos() {
  var primer_nombre = $("#cre_cnombre").val();
  primer_nombre = primer_nombre.toUpperCase();
  primer_nombre = primer_nombre.trim();
  var segundo_nombre = $("#cre_cnombre2").val();
  segundo_nombre = segundo_nombre.toUpperCase();
  segundo_nombre = segundo_nombre.trim();
  var primer_apellido = $("#cre_capellido").val();
  primer_apellido = primer_apellido.toUpperCase();
  primer_apellido = primer_apellido.trim();
  var segundo_apellido = $("#cre_capellido2").val();
  segundo_apellido = segundo_apellido.toUpperCase();
  segundo_apellido = segundo_apellido.trim();
  var identificacion = $("#cre_cdocumento").val();
  identificacion = identificacion.trim();
  var usuario = $("#cre_cusuario").val();
  usuario = usuario.toUpperCase();
  usuario = usuario.trim();
  var contrasena = $("#per_ccontrasena").val();
  contrasena = contrasena.trim();
  var login = $("#login").val();
  login = login.trim();
  var cliente = $("#cliente").val();
  cliente = cliente.toUpperCase();
  cliente = cliente.trim();
  var campana = $("#campana").val();
  campana = campana.toUpperCase();
  campana = campana.trim();
  var tipo_permiso = $("#per_nnivel").val();
  tipo_permiso = tipo_permiso.trim();
  var cargo = $("#cargo").val();
  cargo = cargo.trim();

  var grupo = null;
  var sub_grupo = null;
  var nevo_grupo = null;

  if (cargo == "Agente") {
    grupo = $("#grupo").val();
    grupo = grupo.toUpperCase();
    grupo = grupo.trim();
  } else if (cargo == "Legalizador") {
    grupo = $("#grupo").val();
    grupo = grupo.toUpperCase();
    grupo = grupo.trim();
  } else {
    sub_grupo = $("#sub_grupo").val();
    sub_grupo = sub_grupo.toUpperCase();
    sub_grupo = sub_grupo.trim();

    grupo = cargo;

    validacion = document.getElementById('new-group').getAttribute('style');
    if (validacion == 'display: none;') {
    } else {
      nevo_grupo = $("#nuevo_grupo").val();
      nevo_grupo = nevo_grupo.toUpperCase();
      nevo_grupo = nevo_grupo.trim();

      grupo = cargo;
      sub_grupo = nevo_grupo;
    }
  }

  var autorizacion_registro = $("#autorizacion_registro").val();
  autorizacion_registro = autorizacion_registro.trim();

  $.ajax({
    url: 'registro_usuario.php',
    type: 'GET',
    cache: 'false',
    data: { primer_nombre: primer_nombre, segundo_nombre: segundo_nombre, primer_apellido: primer_apellido, segundo_apellido: segundo_apellido, identificacion: identificacion, usuario: usuario, contrasena: contrasena, login: login, cliente: cliente, campana: campana, tipo_permiso: tipo_permiso, cargo: cargo, grupo: grupo, sub_grupo: sub_grupo, autorizacion_registro: autorizacion_registro }
  }).done(function (data) {
    resultado = String(data);
    if (resultado == '0') {
      alert('El usuario: ' + usuario + ' ya se encuentra registrado en el sistema!');
    } else if (resultado == '1') {
      alert('Registro de Usuario exitoso');
      window.location.href = 'RegistroUsuarios.php';
    } else {
      alert('Error en la plataforma ' + resultado);
      console.log(resultado);
    }
  })
}