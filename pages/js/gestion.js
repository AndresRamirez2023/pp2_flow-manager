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
