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
      let tieneDirector = option.getAttribute('data-director') === 'true';
      let tipoDepto = option.getAttribute('data-tipo');

      if (
        (tipoUsuario === 'RRHH' && tipoDepto !== 'Recursos Humanos') ||
        (tipoUsuario === 'Empleado' && tipoDepto === 'Recursos Humanos') ||
        (tipoUsuario === 'Directivo' && tieneDirector) ||
        (tipoUsuario === '' && option.value !== '')
      ) {
        option.style.display = 'none';
      } else {
        option.style.display = 'block';
      }
    }

    let selectedOption =
      departamentoSelect.options[departamentoSelect.selectedIndex];
    if (selectedOption && selectedOption.style.display === 'none') {
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

  function asignarEventosBorrar() {
    document.querySelectorAll('.confirmar-borrar').forEach((boton) => {
      boton.addEventListener('click', function () {
        let nombreBorrar = this.getAttribute('data-nombre');
        let tipoBorrar = this.getAttribute('data-tipo');

        document.getElementById('tipoBorrar').textContent = tipoBorrar;
        document.getElementById('nombreBorrar').textContent =
          tipoBorrar == 'departamento'
            ? nombreBorrar.split('_', 2)[1]
            : nombreBorrar;

        let deleteModal = new bootstrap.Modal(
          document.getElementById('deleteModal')
        );
        deleteModal.show();

        document.getElementById('btnConfirmarBorrar').onclick = function () {
          let url =
            tipoBorrar === 'usuario'
              ? `../php/Borrar_Usuario.php?dni=${nombreBorrar}`
              : `../php/Borrar_Departamento.php?nombre=${nombreBorrar}`;

          window.location.href = url;
        };
      });
    });
  }

  // Buscar usuarios dinámicamente
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
                          <button class="btn btn-sm btn-danger confirmar-borrar" data-tipo="usuario" data-nombre="${usuario.dni}">
                              Borrar
                          </button>
                      </td>
                  `;

          tablaResultados.appendChild(fila);
        });
        asignarEventosBorrar();
      })
      .catch((error) => console.error('Error al buscar usuario:', error));
  });
  asignarEventosBorrar();
});
