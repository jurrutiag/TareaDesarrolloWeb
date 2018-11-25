
var regiones_ids = [0, 'Región de Tarapacá', 'Región de Antofagasta', 'Región de Atacama', 'Región de Coquimbo ', 'Región de Valparaíso', 'Región del Libertador Bernardo Ohiggins', 'Región del Maule', 'Región del Bío-Bío', 'Región de la Araucanía', 'Región de los Lagos', 'Región Aisén del General Carlos Ibáñez del Campo', 'Región de Magallanes y la Antártica Chilena', 'Región Metropolitana de Santiago ', 'Región de los Rios', 'Región Arica y Parinacota'];

var comuna_regiones_dict = {"1": [["10301", "Camiña"], ["10302", "Huara"], ["10303", "Pozo Almonte"], ["10304", "Iquique"], ["10305", "Pica"], ["10306", "Colchane"], ["10307", "Alto Hospicio"]], "2": [["20101", "Tocopilla"], ["20102", "Maria Elena"], ["20201", "Ollague"], ["20202", "Calama"], ["20203", "San Pedro Atacama"], ["20301", "Sierra Gorda"], ["20302", "Mejillones"], ["20303", "Antofagasta"], ["20304", "Taltal"]], "3": [["30101", "Diego de Almagro"], ["30102", "Chañaral"], ["30201", "Caldera"], ["30202", "Copiapo"], ["30203", "Tierra Amarilla"], ["30301", "Huasco"], ["30302", "Freirina"], ["30303", "Vallenar"], ["30304", "Alto del Carmen"]], "4": [["40101", "La Higuera"], ["40102", "La Serena"], ["40103", "Vicuña"], ["40104", "Paihuano"], ["40105", "Coquimbo"], ["40106", "Andacollo"], ["40201", "Rio Hurtado"], ["40202", "Ovalle"], ["40203", "Monte Patria"], ["40204", "Punitaqui"], ["40205", "Combarbala"], ["40301", "Mincha"], ["40302", "Illapel"], ["40303", "Salamanca"], ["40304", "Los Vilos"]], "5": [["50101", "Petorca"], ["50102", "Cabildo"], ["50103", "Papudo"], ["50104", "La Ligua"], ["50105", "Zapallar"], ["50201", "Putaendo"], ["50202", "Santa Maria"], ["50203", "San Felipe"], ["50204", "Pencahue"], ["50205", "Catemu"], ["50206", "Llay Llay"], ["50301", "Nogales"], ["50302", "La Calera"], ["50303", "Hijuelas"], ["50304", "La Cruz"], ["50305", "Quillota"], ["50306", "Olmue"], ["50307", "Limache"], ["50401", "Los Andes"], ["50402", "Rinconada"], ["50403", "Calle Larga"], ["50404", "San Esteban"], ["50501", "Puchuncavi"], ["50502", "Quintero"], ["50503", "Viña del Mar"], ["50504", "Villa Alemana"], ["50505", "Quilpue"], ["50506", "Valparaiso"], ["50507", "Juan Fernandez"], ["50508", "Casablanca"], ["50509", "Concon"], ["50601", "Isla de Pascua"], ["50701", "Algarrobo"], ["50702", "El Quisco"], ["50703", "El Tabo"], ["50704", "Cartagena"], ["50705", "San Antonio"], ["50706", "Santo Domingo"]], "6": [["60101", "Mostazal"], ["60102", "Codegua"], ["60103", "Graneros"], ["60104", "Machali"], ["60105", "Rancagua"], ["60106", "Olivar"], ["60107", "Doñihue"], ["60108", "Requinoa"], ["60109", "Coinco"], ["60110", "Coltauco"], ["60111", "Quinta Tilcoco"], ["60112", "Las Cabras"], ["60113", "Rengo"], ["60114", "Peumo"], ["60115", "Pichidegua"], ["60116", "Malloa"], ["60117", "San Vicente"], ["60201", "Navidad"], ["60202", "La Estrella"], ["60203", "Marchigue"], ["60204", "Pichilemu"], ["60205", "Litueche"], ["60206", "Paredones"], ["60301", "San Fernando"], ["60302", "Peralillo"], ["60303", "Placilla"], ["60304", "Chimbarongo"], ["60305", "Palmilla"], ["60306", "Nancagua"], ["60307", "Santa Cruz"], ["60308", "Pumanque"], ["60309", "Chepica"], ["60310", "Lolol"]], "7": [["70101", "Teno"], ["70102", "Romeral"], ["70103", "Rauco"], ["70104", "Curico"], ["70105", "Sagrada Familia"], ["70106", "Hualañe"], ["70107", "Vichuquen"], ["70108", "Molina"], ["70109", "Licanten"], ["70201", "Rio Claro"], ["70202", "Curepto"], ["70203", "Pelarco"], ["70204", "Talca"], ["70205", "Pencahue"], ["70206", "San Clemente"], ["70207", "Constitucion"], ["70208", "Maule"], ["70209", "Empedrado"], ["70210", "San Rafael"], ["70301", "San Javier"], ["70302", "Colbun"], ["70303", "Villa Alegre"], ["70304", "Yerbas Buenas"], ["70305", "Linares"], ["70306", "Longavi"], ["70307", "Retiro"], ["70308", "Parral"], ["70401", "Chanco"], ["70402", "Pelluhue"], ["70403", "Cauquenes"]], "8": [["80101", "Cobquecura"], ["80102", "Ñiquen"], ["80103", "San Fabian"], ["80104", "San Carlos"], ["80105", "Quirihue"], ["80106", "Ninhue"], ["80107", "Trehuaco"], ["80108", "San Nicolas"], ["80109", "Coihueco"], ["80110", "Chillan"], ["80111", "Portezuelo"], ["80112", "Pinto"], ["80113", "Coelemu"], ["80114", "Bulnes"], ["80115", "San Ignacio"], ["80116", "Ranquil"], ["80117", "Quillon"], ["80118", "El Carmen"], ["80119", "Pemuco"], ["80120", "Yungay"], ["80121", "Chillan Viejo"], ["80201", "Tome"], ["80202", "Florida"], ["80203", "Penco"], ["80204", "Talcahuano"], ["80205", "Concepcion"], ["80206", "Hualqui"], ["80207", "Coronel"], ["80208", "Lota"], ["80209", "Santa Juana"], ["80210", "Chiguayante"], ["80211", "San Pedro de la Paz"], ["80212", "Hualpen"], ["80301", "Cabrero"], ["80302", "Yumbel"], ["80303", "Tucapel"], ["80304", "Antuco"], ["80305", "San Rosendo"], ["80306", "Laja"], ["80307", "Quilleco"], ["80308", "Los Angeles"], ["80309", "Nacimiento"], ["80310", "Negrete"], ["80311", "Santa Barbara"], ["80312", "Quilaco"], ["80313", "Mulchen"], ["80314", "Alto Bio Bio"], ["80401", "Arauco"], ["80402", "Curanilahue"], ["80403", "Los Alamos"], ["80404", "Lebu"], ["80405", "Cañete"], ["80406", "Contulmo"], ["80407", "Tirua"]], "9": [["90101", "Renaico"], ["90102", "Angol"], ["90103", "Collipulli"], ["90104", "Los Sauces"], ["90105", "Puren"], ["90106", "Ercilla"], ["90107", "Lumaco"], ["90108", "Victoria"], ["90109", "Traiguen"], ["90110", "Curacautin"], ["90111", "Lonquimay"], ["90201", "Perquenco"], ["90202", "Galvarino"], ["90203", "Lautaro"], ["90204", "Vilcun"], ["90205", "Temuco"], ["90206", "Carahue"], ["90207", "Melipeuco"], ["90208", "Nueva Imperial"], ["90209", "Puerto Saavedra"], ["90210", "Cunco"], ["90211", "Freire"], ["90212", "Pitrufquen"], ["90213", "Teodoro Schmidt"], ["90214", "Gorbea"], ["90215", "Pucon"], ["90216", "Villarrica"], ["90217", "Tolten"], ["90218", "Curarrehue"], ["90219", "Loncoche"], ["90220", "Padre Las Casas"], ["90221", "Cholchol"]], "10": [["100201", "San Pablo"], ["100202", "San Juan"], ["100203", "Osorno"], ["100204", "Puyehue"], ["100205", "Rio Negro"], ["100206", "Purranque"], ["100207", "Puerto Octay"], ["100301", "Frutillar"], ["100302", "Fresia"], ["100303", "Llanquihue"], ["100304", "Puerto Varas"], ["100305", "Los Muermos"], ["100306", "Puerto Montt"], ["100307", "Maullin"], ["100308", "Calbuco"], ["100309", "Cochamo"], ["100401", "Ancud"], ["100402", "Quemchi"], ["100403", "Dalcahue"], ["100404", "Curaco de Velez"], ["100405", "Castro"], ["100406", "Chonchi"], ["100407", "Queilen"], ["100408", "Quellon"], ["100409", "Quinchao"], ["100410", "Puqueldon"], ["100501", "Chaiten"], ["100502", "Futaleufu"], ["100503", "Palena"], ["100504", "Hualaihue"]], "11": [["110101", "Guaitecas"], ["110102", "Cisnes"], ["110103", "Aysen"], ["110201", "Coyhaique"], ["110202", "Lago Verde"], ["110301", "Rio Iba?ez"], ["110302", "Chile Chico"], ["110401", "Cochrane"], ["110402", "Tortel"], ["110403", "O''Higins"]], "12": [["120101", "Torres del Paine"], ["120102", "Puerto Natales"], ["120201", "Laguna Blanca"], ["120202", "San Gregorio"], ["120203", "Rio Verde"], ["120204", "Punta Arenas"], ["120301", "Porvenir"], ["120302", "Primavera"], ["120303", "Timaukel"], ["120401", "Antartica"]], "13": [["130101", "Tiltil"], ["130102", "Colina"], ["130103", "Lampa"], ["130201", "Conchali"], ["130202", "Quilicura"], ["130203", "Renca"], ["130204", "Las Condes"], ["130205", "Pudahuel"], ["130206", "Quinta Normal"], ["130207", "Providencia"], ["130208", "Santiago"], ["130209", "La Reina"], ["130210", "Ñuñoa"], ["130211", "San Miguel"], ["130212", "Maipu"], ["130213", "La Cisterna"], ["130214", "La Florida"], ["130215", "La Granja"], ["130216", "Independencia"], ["130217", "Huechuraba"], ["130218", "Recoleta"], ["130219", "Vitacura"], ["130220", "Lo Barrenechea"], ["130221", "Macul"], ["130222", "Peñalolen"], ["130223", "San Joaquin"], ["130224", "La Pintana"], ["130225", "San Ramon"], ["130226", "El Bosque"], ["130227", "Pedro Aguirre Cerda"], ["130228", "Lo Espejo"], ["130229", "Estacion Central"], ["130230", "Cerrillos"], ["130231", "Lo Prado"], ["130232", "Cerro Navia"], ["130301", "San Jose de Maipo"], ["130302", "Puente Alto"], ["130303", "Pirque"], ["130401", "San Bernardo"], ["130402", "Calera de Tango"], ["130403", "Buin"], ["130404", "Paine"], ["130501", "Peñaflor"], ["130502", "Talagante"], ["130503", "El Monte"], ["130504", "Isla de Maipo"], ["130601", "Curacavi"], ["130602", "Maria Pinto"], ["130603", "Melipilla"], ["130604", "San Pedro"], ["130605", "Alhue"], ["130606", "Padre Hurtado"]], "14": [["100101", "Lanco"], ["100102", "Mariquina"], ["100103", "Panguipulli"], ["100104", "Mafil"], ["100105", "Valdivia"], ["100106", "Los Lagos"], ["100107", "Corral"], ["100108", "Paillaco"], ["100109", "Futrono"], ["100110", "Lago Ranco"], ["100111", "La Union"], ["100112", "Rio Bueno"]], "15": [["10101", "Gral. Lagos"], ["10102", "Putre"], ["10201", "Arica"], ["10202", "Camarones"]]};


var extensiones = ["jpg", "jpeg", "exif", "tiff", "bmp", "png", "ppm", "hdr", "bpg"];

var maxCaracteres = 250;


function onloadFunction() {
    var regionOrigen = document.getElementById("region-origen");
    var regionDestino = document.getElementById("region-destino");
    
    for(var i = 0; i < regiones_ids.length; i++) {
        var optOrigen = document.createElement("option");

        optOrigen.value = i + 1;
        optOrigen.textContent = regiones_ids[i + 1];

        var optDestino = document.createElement("option");

        optDestino.value = i + 1;
        optDestino.textContent = regiones_ids[i + 1];

        regionOrigen.appendChild(optOrigen);
        regionDestino.appendChild(optDestino);
    }
    regionOrigen.selectedIndex = 0;
    regionDestino.selectedIndex = 0;



    comunaOrigen();
    comunaDestino();

}

function clearComunas(region) {
    while(region.hasChildNodes()) {
        region.removeChild(region.lastChild);
    }
}

function comunaOrigen() {
    var regionOrigen = document.getElementById("region-origen");
    var comunaOrigen = document.getElementById("comuna-origen");

    clearComunas(comunaOrigen);

    for(var i = 0; i < comuna_regiones_dict[regionOrigen.value].length; i++) {
        var opt = document.createElement("option");

        opt.value = comuna_regiones_dict[regionOrigen.value][i][0];
        opt.textContent = comuna_regiones_dict[regionOrigen.value][i][1];

        comunaOrigen.appendChild(opt);
    }
}

function comunaDestino() {
    var regionDestino = document.getElementById("region-destino");
    var comunaDestino = document.getElementById("comuna-destino");

    clearComunas(comunaDestino);

    for(var i = 0; i < comuna_regiones_dict[regionDestino.value].length; i++) {
        var opt = document.createElement("option");

        opt.value = comuna_regiones_dict[regionDestino.value][i][0];
        opt.textContent = comuna_regiones_dict[regionDestino.value][i][1];

        comunaDestino.appendChild(opt);
    }
}


function masInfoViajes(n, loc='mas-info-viajes.php?id=') {
    location.href = loc + n.toString();

}

function loadMasInfoViajes() {
    var n = localStorage.getItem("n");
    var mainDiv = document.getElementById("main-div");
    for (var i = 0; i < mainDiv.childNodes.length; i++) {
        if (mainDiv.childNodes[i].className == "info") {
            var id = mainDiv.childNodes[i].id;
            mainDiv.childNodes[i].textContent = info_viajes[n][id];
        }
    }
}

function masInfoEncargos(n, loc="mas-info-encargos.php?id=") {
    location.href = loc + n.toString();
}

function changeSize() {
    var id = document.getElementById('foto-encargo');
    if (id.style.width == "800px") {
        id.style.width = "320px";
        id.style.height = "240px";
    } else {
        id.style.width = "800px";
        id.style.height = "600px";
    }
    updateWidth();
};

function loadMasInfoEncargos() {
    var n = localStorage.getItem("n");
    var mainDiv = document.getElementById("main-div");
    for (var i = 0; i < mainDiv.childNodes.length; i++) {
        if (mainDiv.childNodes[i].className == "info" || mainDiv.childNodes[i].className == "foto-info") {
            var id = mainDiv.childNodes[i].id;
            if (mainDiv.childNodes[i].className == "foto-info") {
                mainDiv.childNodes[i].src = "../fotos/" + info_encargos[n]["foto-encargo"];
                mainDiv.childNodes[i].alt = "Foto encargo " + n
                mainDiv.childNodes[i].onclick = function () {
                    if (this.style.width == "800px") {
                        this.style.width = "320px";
                        this.style.height = "240px";
                    } else {
                        this.style.width = "800px";
                        this.style.height = "600px";
                    }
                    updateWidth();
                };
            } else {
                mainDiv.childNodes[i].textContent = info_encargos[n][id];
            }
        }
    }

    updateWidth();
}

function updateWidth() {
    var mainDiv = document.getElementById("main-div");
    var foto = document.getElementById("foto-encargo");
    mainDiv.style.width = foto.width + "px";
}

function goBack(n) {
    history.go(-1);
}


function agregar_viaje_validacion() {
    var isValid = true;

    var ids = ["region-origen", "comuna-origen", "region-destino", "comuna-destino"];

    // validación regiones y comunas
    for (var i = 0; i < 4; i++) {
        
        isValid = validarSelect(ids[i], isValid);

    }


    // validación fecha

    var fechaIdaVal = document.getElementById("fecha-ida").value;

    if (!validarFecha(fechaIdaVal)) {
        isValid = false;
        validacion_error("Ingrese fecha válida", "fecha-ida", "fecha-ida-h");
    } else {
        validacion_correcto("fecha-ida", "fecha-ida-h");
    }
    
    var fechaRegresoVal = document.getElementById("fecha-regreso").value;

    if (fechaRegresoVal != 0 && !validarFecha(fechaRegresoVal)) {
        isValid = false;
        validacion_error("Ingrese fecha válida", "fecha-regreso", "fecha-regreso-h");
    } else {
        validacion_correcto("fecha-regreso", "fecha-regreso-h");
    }
    

    // validación espacio

    isValid = validarSelect("espacio-disponible", isValid);

    // validación kilos

    isValid = validarSelect("kilos-disponibles", isValid);

    // validación email

    isValid = validarEmail(isValid);

    // validación celular

    isValid = validarCelular(isValid);


    
    return isValid;
}


function agregar_encargo_validacion() {


    var isValid = true;

    // validación descripcion

    isValid = validarDescripcion(isValid);

    // validación espacio

    isValid = validarSelect("espacio-solicitado", isValid);

    // validación kilos

    isValid = validarSelect("kilos-solicitados", isValid);


    var ids = ["region-origen", "comuna-origen", "region-destino", "comuna-destino"];

    // validación regiones y comunas
    for (var i = 0; i < 4; i++) {
        
        isValid = validarSelect(ids[i], isValid);

    }

    // validación foto

    isValid = validarFoto(isValid);


    // validación email

    isValid = validarEmail(isValid);

    // validación celular

    isValid = validarCelular(isValid);


    
    return isValid;

}


function validarFecha(dat) {

    if (! /^(\d{2}\/\d{2}\/\d{4})/.test(dat)) {
        return false;
    }

    var diasMeses = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    var today = new Date();
    today.setHours(0);
    today.setMilliseconds(0);
    today.setMinutes(0);

    var sepDate = dat.split('/');
    var dia = parseInt(sepDate[0]);
    var mes = parseInt(sepDate[1]);
    var anio = parseInt(sepDate[2]);

    if (mes > 12 || mes <= 0 || dia <= 0 || dia > 31) {
        return false;
    }

    var datDay = new Date(anio, mes-1, dia);

    // anios bisiestos
    if ((anio % 4 == 0 && anio % 100 != 0) || anio % 400 == 0) {
        diasMeses[1] = 29;
    }

    // rangos
    if (datDay < today) {
        return false;
    }

    if (dia > diasMeses[mes - 1]) {
        return false;
    }

    return true;
}


function validarSelect(id, isValid) {
    var select = document.getElementById(id);
    if (select.selectedIndex <= -1) {
        isValid = false;
        validacion_error("Ingrese una opción", id, id+"-h");
    }  else {
        validacion_correcto(id, id+"-h");
    }
    return isValid
}

function validarEmail(isValid) {
    var mail = document.getElementById("email").value;

    if (!/(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/.test(mail)) {
        isValid = false;
        validacion_error("Ingrese un mail válido", "email", "email-h");
    }  else {
        validacion_correcto("email", "email-h");
    }
    return isValid
}

function validarCelular(isValid) {
    var celular = document.getElementById("celular").value;
    if (!/^\+56\d{1}\d{8}/.test(celular)) {
        isValid = false;
        validacion_error("Ingrese número de celular válido", "celular", "celular-h");
    }  else {
        validacion_correcto("celular", "celular-h");
    }
    return isValid
}


function validarDescripcion(isValid) {
    var descripcion = document.getElementById("descripcion").value;
    if (descripcion.length > maxCaracteres) {
        isValid = false;
        validacion_error("Ingrese descripción válida", "descripcion", "descripcion-h");
    }  else {
        validacion_correcto("descripcion", "descripcion-h");
    }
    return isValid
}

function validarFoto(isValid) {
    var foto = document.getElementById("foto-encargo").value;
    
    var splFoto = foto.split('.')
    if (foto.length <= 0 || !isin(splFoto[splFoto.length - 1].toLowerCase(), extensiones)) {
        isValid = false;
        validacion_error("Ingrese archivo válido", "foto-encargo", "foto-encargo-h");
    }  else {
        validacion_correcto("foto-encargo", "foto-encargo-h");
    }
    return isValid
}

function isin(a, l) {
    for (var i = 0; i < l.length; i ++) {
        if (a === l[i]) {
            return true;
        }
    }
    return false;
}

function updateDescripcion() {
    var descH = document.getElementById("descripcion-h");
    var desc = document.getElementById("descripcion");

    i = maxCaracteres - desc.value.length;
    descH.innerHTML = "Descripción Encargo (" + i + " caracteres restantes):";
}


function index() {
    location.href = "../index.php";
}

function validacion_error(mensaje, objId, tagId) {
    if (!document.getElementById(tagId).innerHTML.includes(mensaje)) {
        document.getElementById(tagId).innerHTML += " (" + mensaje + ")";
        document.getElementById(tagId).style.color = "red";
        if (!document.getElementById(objId).classList.contains("is-invalid")) {
            document.getElementById(objId).classList.add("is-invalid");
        }
    }
}

function validacion_correcto(objId, tagId) {
    document.getElementById(tagId).innerHTML = document.getElementById(tagId).innerHTML.split('(')[0];
    document.getElementById(tagId).style.color = "black";
    if (document.getElementById(objId).classList.contains("is-invalid")) {
        document.getElementById(objId).classList.remove("is-invalid");
    }
}