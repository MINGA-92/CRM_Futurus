function enviarInformacion(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'AgenteCall.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionVisitas(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'AgenteVisitas.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionLegalizador(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'Legalizacion.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionUsuario(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'ActualizarUsuarios.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionLegalizacionFinal(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'LegalizacionFinal.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionHomeOffice(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'Frankenstein.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function enviarInformacionSupervisor(codigoExpediente) {
    var form = document.createElement('form');
    form.style.visibility = 'hidden';
    form.method = 'POST';
    form.action = 'FormularioActualizarSupervisor.php';
    var input = document.createElement('input');
    input.name = 'codigoExpediente';
    input.value = codigoExpediente;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
