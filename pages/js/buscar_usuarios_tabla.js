document.addEventListener('DOMContentLoaded', function () {
  let buscador = document.getElementById('buscador');
  let tablaResultados = document.getElementById('resultados');

  buscador.addEventListener('keyup', function () {
    let termino = buscador.value.trim();
    tablaResultados.innerHTML = '';

    if (termino.length < 3) return;

    fetch(`../php/Buscar_Usuarios.php?q=${encodeURIComponent(termino)}`)
      .then((response) => response.json())
      .then((data) => {
        tablaResultados.innerHTML = '';

        if (data.length === 0) {
          tablaResultados.innerHTML =
            '<tr><td colspan="4" class="text-center">No se encontraron resultados</td></tr>';
          return;
        }

        data.forEach((usuario) => {
          let fila = document.createElement('tr');

          fila.innerHTML = `
            <td>${usuario.dni}</td>
            <td>${usuario.nombre}</td>
            <td>${usuario.email}</td>
            <td>
              <a href="editarUsuario.php?dni=${usuario.dni}" class="btn btn-sm btn-primary">Editar</a>
              <a href="borrarUsuario.php?dni=${usuario.dni}" class="btn btn-sm btn-danger">Borrar</a>
            </td>
          `;

          tablaResultados.appendChild(fila);
        });
      })
      .catch((error) => console.error('Error al buscar usuario:', error));
  });
});
