// Cambiar entre aviso y mensaje
document.addEventListener('DOMContentLoaded', function () {
  const envioAviso = document.getElementById('envioAviso');
  const envioDocumento = document.getElementById('envioDocumento');
  const seccionAviso = document.getElementById('seccion-aviso');
  const seccionDocumento = document.getElementById('seccion-documento');

  envioAviso.addEventListener('click', function () {
    seccionAviso.classList.remove('d-none');
    seccionDocumento.classList.add('d-none');
  });

  envioDocumento.addEventListener('click', function () {
    seccionAviso.classList.add('d-none');
    seccionDocumento.classList.remove('d-none');
  });
});

// Filtrar departamentos
document.addEventListener('DOMContentLoaded', function () {
  let tipoUsuarioSelect = document.getElementById('tipoDeUsuario');
  let departamentoSelect = document.getElementById('Departamento');

  function filtrarDepartamentos() {
    let tipoUsuario = tipoUsuarioSelect.value;

    for (let option of departamentoSelect.options) {
      let tieneDirector = option.getAttribute('data-director');
      let tipoDepto = option.getAttribute('data-tipo');

      if (
        (tipoUsuario === 'RRHH' && tipoDepto !== 'Recursos Humanos') ||
        (tipoUsuario === 'Directivo' && tieneDirector) ||
        (tipoUsuario === '' && option.value !== '')
      ) {
        option.style.display = 'none';
      } else {
        option.style.display = 'block';
      }
    }

    if (
      departamentoSelect.selectedIndex !== -1 &&
      departamentoSelect.options[departamentoSelect.selectedIndex].style
        .display === 'none'
    ) {
      departamentoSelect.selectedIndex = 0;
    }
  }

  filtrarDepartamentos();

  tipoUsuarioSelect.addEventListener('change', filtrarDepartamentos);
});

// Buscador de usuarios y modal de borrado
document.addEventListener('DOMContentLoaded', function () {
  let buscador = document.getElementById('buscador');
  let tablaResultados = document.getElementById('resultados');
  let dniAEliminar = null;

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
              <a href="gestion.php?dni=${usuario.dni}" class="btn btn-sm btn-primary">Editar</a>
              <button class="btn btn-sm btn-danger confirmar-borrar-usuario" data-dni="${usuario.dni}">Borrar</button>
            </td>
          `;

          tablaResultados.appendChild(fila);
        });

        // Agregar eventos a los botones de borrar
        document.querySelectorAll('.confirmar-borrar-usuario').forEach((boton) => {
          boton.addEventListener('click', function () {
            dniAEliminar = this.getAttribute('data-dni');
            document.getElementById('dniUsuarioBorrar').textContent = dniAEliminar;
            let deleteUsuarioModal = new bootstrap.Modal(document.getElementById('deleteUsuarioModal'));
            deleteUsuarioModal.show();
          });
        });
      })
      .catch((error) => console.error('Error al buscar usuario:', error));
  });

  // Confirmar eliminación
  document.getElementById('btnConfirmarBorrar').addEventListener('click', function () {
    if (dniAEliminar) {
      window.location.href = `../php/Borrar_Usuario.php?dni=${dniAEliminar}`;
    }
  });
});

