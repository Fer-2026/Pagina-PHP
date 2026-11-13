document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('formBusqueda');
  const input = document.getElementById('buscarCurso');
  const botonLimpiar = document.getElementById('limpiarBusqueda');
  const estado = document.getElementById('estadoCursos');
  const resultados = document.getElementById('resultadosCursos');

  if (!formulario || !input || !botonLimpiar || !estado || !resultados) {
    return;
  }

  const endpoint = formulario.dataset.endpoint;
  const inscripcionUrl = formulario.dataset.inscripcion || '';

  let temporizador = null;
  let controlador = null;

  const actualizarEstado = (texto, tono = 'muted') => {
    estado.textContent = texto;
    estado.className = `note mb-0 text-${tono}`;
  };

  const mostrarCargando = () => {
    resultados.setAttribute('aria-busy', 'true');
    resultados.innerHTML = '';

    const columna = document.createElement('div');
    columna.className = 'col-12';

    const cargando = document.createElement('div');
    cargando.className = 'course-loading';
    cargando.setAttribute('role', 'status');

    const spinner = document.createElement('span');
    spinner.className = 'spinner-border spinner-border-sm me-2';
    spinner.setAttribute('aria-hidden', 'true');

    cargando.append(spinner, document.createTextNode('Cargando cursos...'));
    columna.appendChild(cargando);
    resultados.appendChild(columna);
  };

  const mostrarError = (mensaje) => {
    resultados.setAttribute('aria-busy', 'false');
    resultados.innerHTML = '';

    const columna = document.createElement('div');
    columna.className = 'col-12';

    const alerta = document.createElement('div');
    alerta.className = 'alert alert-danger mb-0';
    alerta.setAttribute('role', 'alert');
    alerta.textContent = mensaje;

    columna.appendChild(alerta);
    resultados.appendChild(columna);
    actualizarEstado('No se pudieron cargar los cursos.', 'danger');
  };

  const construirTarjeta = (curso) => {
    const columna = document.createElement('div');
    columna.className = 'col-md-6';

    const tarjeta = document.createElement('article');
    tarjeta.className = 'card course-card h-100';
    tarjeta.dataset.cursoId = String(curso.id);

    const cuerpo = document.createElement('div');
    cuerpo.className = 'card-body p-4 d-flex flex-column';

    const cupos = document.createElement('span');
    cupos.className = 'badge text-bg-success align-self-start mb-2';
    cupos.textContent = `${curso.cupos} cupos`;

    const titulo = document.createElement('h3');
    titulo.className = 'h5';
    titulo.textContent = curso.titulo;

    const descripcion = document.createElement('p');
    descripcion.className = 'mb-0 flex-grow-1';
    descripcion.textContent = curso.descripcion;

    cuerpo.append(cupos, titulo, descripcion);

    if (inscripcionUrl !== '') {
      const enlace = document.createElement('a');
      enlace.className = 'btn btn-sm btn-outline-success mt-3 align-self-start';
      enlace.href = inscripcionUrl;
      enlace.textContent = 'Consultar inscripción';
      cuerpo.appendChild(enlace);
    }

    tarjeta.appendChild(cuerpo);
    columna.appendChild(tarjeta);

    return columna;
  };

  const renderizarCursos = (cursos, consulta) => {
    resultados.setAttribute('aria-busy', 'false');
    resultados.innerHTML = '';

    if (!Array.isArray(cursos) || cursos.length === 0) {
      const columna = document.createElement('div');
      columna.className = 'col-12';

      const aviso = document.createElement('div');
      aviso.className = 'alert alert-info mb-0';
      aviso.setAttribute('role', 'status');
      aviso.textContent = consulta === ''
        ? 'Todavía no hay cursos cargados.'
        : `No se encontraron cursos para “${consulta}”. Probá con otra palabra.`;

      columna.appendChild(aviso);
      resultados.appendChild(columna);
      actualizarEstado(
        consulta === '' ? 'Sin cursos disponibles.' : `0 resultados para “${consulta}”.`,
        'warning'
      );
      return;
    }

    cursos.forEach((curso) => {
      resultados.appendChild(construirTarjeta(curso));
    });

    if (consulta === '') {
      actualizarEstado(
        `${cursos.length} ${cursos.length === 1 ? 'curso disponible' : 'cursos disponibles'}.`,
        'muted'
      );
    } else {
      actualizarEstado(
        `${cursos.length} ${cursos.length === 1 ? 'curso encontrado' : 'cursos encontrados'} para “${consulta}”.`,
        'success'
      );
    }
  };

  const cargarCursos = async (consulta = '') => {
    if (controlador) {
      controlador.abort();
    }
    controlador = new AbortController();

    mostrarCargando();
    actualizarEstado('Buscando cursos...', 'muted');

    try {
      const url = new URL(endpoint, window.location.origin);
      url.searchParams.set('q', consulta);

      const respuesta = await fetch(url.toString(), {
        headers: { Accept: 'application/json' },
        signal: controlador.signal,
      });

      const tipoContenido = respuesta.headers.get('content-type') || '';

      if (!respuesta.ok) {
        let mensaje = `Error HTTP ${respuesta.status}.`;
        try {
          const error = await respuesta.json();
          if (error && error.message) {
            mensaje = error.message;
          }
        } catch (errorJson) {
          // Si la respuesta no es JSON, se conserva el mensaje genérico.
        }
        throw new Error(mensaje);
      }

      if (!tipoContenido.includes('application/json')) {
        throw new Error('La respuesta del servidor no es JSON.');
      }

      const datos = await respuesta.json();

      if (!datos || datos.status !== 'success') {
        throw new Error(datos && datos.message ? datos.message : 'Respuesta inválida del servidor.');
      }

      renderizarCursos(datos.data, consulta);
    } catch (error) {
      if (error.name === 'AbortError') {
        return;
      }

      console.error('Error al buscar cursos:', error);
      mostrarError('No se pudieron cargar los cursos. Revisá tu conexión e intentá nuevamente.');
    }
  };

  input.addEventListener('input', () => {
    window.clearTimeout(temporizador);
    const consulta = input.value.trim();
    temporizador = window.setTimeout(() => cargarCursos(consulta), 300);
  });

  formulario.addEventListener('submit', (evento) => {
    evento.preventDefault();
    window.clearTimeout(temporizador);
    cargarCursos(input.value.trim());
  });

  botonLimpiar.addEventListener('click', () => {
    window.clearTimeout(temporizador);
    input.value = '';
    input.focus();
    cargarCursos('');
  });

  cargarCursos('');
});
