// Maneja el clic en los mensajes de la lista
document.querySelectorAll('.list-group-item').forEach((item) => {
  item.addEventListener('click', function () {
    const messageType = this.getAttribute('data-type');
    const messageTitle = this.getAttribute('data-title');
    const messageDate = this.getAttribute('data-date');
    const messageFile = this.getAttribute('data-file');

    // Mostrar el contenido del mensaje en la columna de detalles
    document.getElementById('message-title').textContent = messageTitle;
    document.getElementById('message-type').textContent =
      messageType.charAt(0).toUpperCase() + messageType.slice(1);
    document.getElementById('message-date').textContent = messageDate;

    if (messageType === 'documento') {
      document.getElementById('message-content').innerHTML =
        '<p>Este es un documento se encuentra pendiente de firma.</p>';
      document.getElementById('file-preview').src = messageFile; // Mostrar el archivo
      document.getElementById('file-preview').classList.remove('d-none');
      document.getElementById('sign-button').classList.remove('d-none');
    } else {
      document.getElementById('message-content').innerHTML =
        '<p>Se les informan las fechas de las siguientes evaluaciones de desempeño del año, las cuales serán durante todo el mes de noviembre, comuniquense con sus respectivos directivos.</p>';
      document.getElementById('file-preview').classList.add('d-none');
      document.getElementById('sign-button').classList.add('d-none');
    }
  });
});

// Abrir el modal de firma al hacer clic en "Firmar"
document.getElementById('sign-button').addEventListener('click', function () {
  var signModal = new bootstrap.Modal(document.getElementById('signModal'));
  signModal.show();
});
