document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: 'bootstrap5',
    initialView: 'dayGridMonth',
    events: [
      {
        title: 'Daily Equipo 1',
        start: '2024-10-07',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-08',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-09',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-10',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-04',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-08',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-11',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-14',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-15',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Daily equipo 1',
        start: '2024-10-16',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-22',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      {
        title: 'Weekly equipo 2',
        start: '2024-10-23',
        backgroundColor: '#add8e6', // Azul claro para reuniones
        borderColor: '#add8e6',
        textColor: 'black',
      },
      // Agrega más eventos aquí
    ],
  });

  calendar.render();
});
