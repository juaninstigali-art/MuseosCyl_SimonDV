// Gestiona añadir y eliminar favoritos con AJAX
function toggleFavorito(boton, museo) {

  const esFavorito = boton.classList.contains('es-favorito');
  const accion = esFavorito ? 'eliminar_favorito' : 'agregar_favorito';

  // Enviamos petición AJAX con informacion_adicional
  fetch('index.php?accion=' + accion, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({
          api_id: museo.identificador,
          nombre: museo.nombreentidad,
          localidad: museo.localidad,
          latitud: museo.posicion?.lat ?? null,
          longitud: museo.posicion?.lon ?? null,
          horario: museo.horario ?? museo.horarioapertura ?? null,
          url: museo.url ?? museo.web ?? null,
          informacion_adicional: museo.informacion_adicional ?? null  // NUEVO
      })
  })
  .then(respuesta => respuesta.json())
  .then(datos => {
      if (datos.ok) {
          if (esFavorito) {
              boton.classList.remove('es-favorito');
              boton.classList.add('no-favorito');
              boton.textContent = '☆';
          } else {
              boton.classList.remove('no-favorito');
              boton.classList.add('es-favorito');
              boton.textContent = '★';
          }
      } else {
          alert('No se pudo actualizar el favorito');
      }
  })
  .catch(() => {
      alert('Error al conectar con el servidor');
  });
}