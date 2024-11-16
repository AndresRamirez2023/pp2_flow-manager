// Custom JS
// Mostrar/ocultar el menú al hacer clic en la imagen de perfil
document.getElementById('profileMenu').addEventListener('click', function () {
  const dropdown = document.getElementById('profileDropdown');
  dropdown.classList.toggle('show');
});

// Cerrar el menú si se hace clic fuera de la foto
window.addEventListener('click', function (event) {
  const dropdown = document.getElementById('profileDropdown');
  const profileMenu = document.getElementById('profileMenu');
  if (!profileMenu.contains(event.target)) {
    dropdown.classList.remove('show');
  }
});
