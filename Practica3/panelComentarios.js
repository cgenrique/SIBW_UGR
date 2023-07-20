const mysql = require('mysql');

const connection = mysql.createConnection({
  host: 'mysql',
  user: 'usuario',
  password: 'usuario',
  database: 'SIBW'
});

connection.connect(function(err) {
  if (err) {
    console.error('Error al conectar a la base de datos: ' + err.stack);
    return;
  }

  console.log('Conectado a la base de datos con el ID: ' + connection.threadId);
});


/** Variables: botones, panel y campos del formulario*/ 
const botonComentarios = document.getElementById('boton-comentarios');
const panelComentarios = document.querySelector('.panel-comentarios');
const formComentarios = panelComentarios.querySelector('form');
const inputNombre = formComentarios.querySelector('#nombre');   //Nombre 
const inputEmail = formComentarios.querySelector('#email');    //Email         
const inputComentario = formComentarios.querySelector('#comentario'); //Texto del comentario
const listaComentarios = panelComentarios.querySelector('.comment-list'); //Lista de los comentarios

// Definimos las palabras prohibidas
//const palabrasProhibidas = ['puta', 'idiota', 'mierda', 'joder', 'cabron', 'coño'];
const palabrasProhibidas = [];

connection.query('SELECT palabra FROM palabras_prohibidas', function(err, results, fields) {
  if (err) {
    console.error('Error al obtener las palabras prohibidas: ' + err.stack);
    return;
  }

  results.forEach(function(result) {
    palabrasProhibidas.push(result.palabra);
  });
});



/**Al pulsar el botón, el panel pasa a estado "abierto" y es visible*/
botonComentarios.addEventListener('click', function() {
  panelComentarios.classList.toggle('abierto');
});

/**Función para validar el formato del email. Debe contener un @ y un dominio después de un punto . */
function validarEmail(email) {
    const emailAValidar = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailAValidar.test(email)
}

/**Función para validar los campos nombre, email y el comentario del formulario*/
function validarForm() {

    let errores = []; //Lista de posibles errores


    if(inputNombre.value.trim() === ''){
        errores.push("El cambo \"Nombre\" es obligatorio"); //Campo "nombre" vacío
    }

    if(inputEmail.value.trim() === ''){
        errores.push("El cambo \"Email\" es obligatorio");  //Campo "email" vacío

    } else if (!validarEmail(inputEmail.value)) {
        errores.push("El formato del campo \"Email\" es incorrecto"); //Campo "email" relleno pero de formato incorrecto
    }

    if(inputComentario.value.trim() === ''){
        errores.push("El cambo \"Comentario\" es obligatorio"); //Texto del comentario vacío
    }

    //Si hay al menos un error, este se muestra en una alerta
    if(errores.length > 0){
        alert(errores.join('\n'));
        return false;
    }

    return true;
}

/**Función para filtrar las palabras prohibidas en el campo de comentario*/
function censurarPalabrasProhibidas() {
    const textarea = inputComentario.value; //Valor del comentario
    let textoFiltrado = textarea;

    //Se comprueba la palabra según se escribe. Con \b delimitamos la palabra
    //y así solo censuramos las palabras prohibidas si están sueltas y no como parte de otra palabra
    //P.ejemplo: Si está censurado "puta" se censura así **** pero no se censuraría "diputación"
    for (let i = 0; i < palabrasProhibidas.length; i++) {
        const regex = new RegExp("\\b" + palabrasProhibidas[i] + "\\b", "gi");
        const palabraCensurada = '*'.repeat(palabrasProhibidas[i].length);
        textoFiltrado = textoFiltrado.replace(regex, palabraCensurada);
    }

    inputComentario.value = textoFiltrado;
}

// Agregamos el event listener para llamar a la función censurarPalabrasProhibidas() en cada cambio en el campo de comentario
inputComentario.addEventListener('input', censurarPalabrasProhibidas);


/**Cuando se pulsa el botón enviar */
formComentarios.addEventListener('submit', function(event) {
    event.preventDefault();

    if(!validarForm()){ //Se comprueban los valores del formulario
        return;
    }


    const nombre = inputNombre.value;
    const email = inputEmail.value;
    const comentario = inputComentario.value;

    //La fecha tiene el formato dd/mm/aaaa, hh:mm
    const fecha = new Date().toLocaleString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    const nuevoComentario = document.createElement('li');
    const comentarioHTML = `
        <div class="comment-info">
            <span class="comment-author">${nombre}</span>
        </div>

        <li class="comment">
            <div class="comment-text">${comentario}</div>
            <div class="comment-date">${fecha}</div>
        </li>
    `;
    nuevoComentario.innerHTML = comentarioHTML;
    listaComentarios.appendChild(nuevoComentario);
    inputNombre.value = '';
    inputEmail.value = '';
    inputComentario.value = '';
});