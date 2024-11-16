document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: [
      {
        title: 'Daily equipo 1',
        start: '2024-10-07',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-08',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-09',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-10',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-15',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-08',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
      },
      {
        title: 'Licencia de Vacaciones',
        start: '2024-10-16',
        end: '2024-10-20',
        backgroundColor: '#ffd700', // Amarillo para licencias
        borderColor: '#ffd700',
      },
      {
        title: 'Licencia de Examen',
        start: '2024-10-28',
        end: '2024-10-30',
        backgroundColor: '#ffd700', // Amarillo para licencias
        borderColor: '#ffd700',
      },
      // Agrega más eventos aquí
    ],
  });

  calendar.render();
});
