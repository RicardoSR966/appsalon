let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion();
    tabs();
    botonesPaginador(); //AGREGA O QUITA LOS BOTONES DEL PAGINADOR
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //CONSULTA API EN EL BACKEND EN PHP

    idCliente();
    nombreCliente();
    seleccionarFecha();
    seleccionarHora();

}

function mostrarSeccion() {

    //OCULTAR SECCION QUE TENGA MOSTRAR
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //SELECIONAR LA SECCION CON EL PASO. La variable es el numero de paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //QUITA LA CLASE ACTUAL AL TAB
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //RESALTA EL TAB ACTUAL
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => { //recorremos con un arrow function
        boton.addEventListener('click', function(e) {
            //            console.log(e);
            paso = (parseInt(e.target.dataset.paso));
            mostrarSeccion();
            botonesPaginador(); //AGREGA O QUITA LOS BOTONES DEL PAGINADOR


        });
    })
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else if (paso === 2) {
        paginaSiguiente.classList.remove('ocultar');
        paginaAnterior.classList.remove('ocultar');
    }
    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        console.log(paso);
        if (paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
    })
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {

        if (paso >= pasoFinal) return;
        paso++;

        botonesPaginador();
    })
}

async function consultarAPI() {
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita; //EXTRALLENDO LOS SERVICIOS DEL OBJETO DE CITAS
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); //IDENTIFICAR AL ELEMENTO QUE SE LE DA CLICK

    //COMPROBAR SI YA ESTA AGREGADO O QUITARLO
    if (servicios.some(agregado => agregado.id === id)) { //el agregado es lo que esta en memoria entonces cuando de click en un azul marcara que ya esta agregado
        //ELIMINAR
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');

    } else {
        cita.servicios = [...servicios, servicio]; //TOMO UNA COPIA DEL ARREGLO Y LO AGREGO EL NUEVO SERVICIO
        divServicio.classList.add('seleccionado');
    }

    //console.log(servicio);
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {

        const dia = new Date(e.target.value).getUTCDay();

        if ([6, 0].includes(dia)) {

            e.target.value = '';
            //MOSTRAR ALERTA
            mostrarAlerta('Fines de semana no permitido', 'error', '.formulario');
        } else {

            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 17) {
            e.target.value = '';
            mostrarAlerta('Horario de 10am a 6pm', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    //PREVIENE QUE SE GENERE MAS DE UNA ALERTA
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    };
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    if (desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 2000);
    }

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    while (resumen.firstChild) {

        resumen.removeChild(resumen.firstChild);
    }
    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos para completar cita', 'error', '.contenido-resumen', false);
        return;
    }

    //formatear resumen
    const { nombre, fecha, hora, servicios } = cita;

    //HEADING PARA SERVICIOS EN RESUMEN
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //ITERANDO Y MOSTRANDO LOS SERVICIOS
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });
    //HEADIG PARA CITA EN RESUMEN
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //FORMATEAR FECHA EN ESPAÑOL
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const anio = fechaObj.getFullYear();

    const fechaUtc = new Date(Date.UTC(anio, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUtc.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora}`;

    //BOTON PARA CREAR CITA
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservaCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

async function reservaCita() {
    const { nombre, fecha, hora, servicios, id } = cita;
    const idServicios = servicios.map(servicio => servicio.id);
    const datos = new FormData();

    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    try {
        const url = '/api/citas';
            //cuando la funcion es asincorona
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos //es el cuerpo de la peticion que se va a amandar, de esta forma identifica los FORMdata y los envia como parte de la peticion POST
        });

        const resultado = await respuesta.json();

        if (resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Cita creada correctamente!',
                button: 'OK'
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al guardar la cita!'
        })
    }

    //console.log([...datos]);
}