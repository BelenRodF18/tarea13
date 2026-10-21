const id = Number(new URLSearchParams(location.search).get('id'));

function mostrarInfo(a) {
    document.getElementById('titulo').textContent = `${a.nombre_marca} ${a.modelo}`;
    const filas = [
        ['ID', a.id_vehiculo],
        ['Marca', a.nombre_marca],
        ['País de origen', a.pais_origen ?? '-'],
        ['Modelo', a.modelo],
        ['Año', a.anio ?? '-'],
        ['Precio de lista', Util.dinero(a.precio)],
        ['Estado', a.estado ?? '-'],
        ['Situación', a.fecha_venta ? 'Vendido' : 'Disponible']
    ];
    if (a.fecha_venta) {
        filas.push(['Fecha de venta', a.fecha_venta],
                   ['Monto de la venta', Util.dinero(a.monto_total)],
                   ['Cliente', a.cliente ?? '-'],
                   ['Vendedor', a.vendedor ?? '-']);
    }
    document.getElementById('info').innerHTML =
        filas.map(([k, v]) => `<dt>${k}</dt><dd>${Util.esc(String(v))}</dd>`).join('');
}

function llenarFormulario(a) {
    document.getElementById('modelo').value = a.modelo;
    document.getElementById('anio').value = a.anio ?? '';
    document.getElementById('precio').value = a.precio ?? '';
    document.getElementById('estado').value = a.estado ?? '';
}

async function cargarAuto() {
    const r = await API.request('/autos/' + id);
    if (r.status !== 'ok') {
        document.getElementById('info').innerHTML = '<dt>Error</dt><dd>' + Util.esc(r.message || 'No se pudo cargar el auto') + '</dd>';
        document.getElementById('form-auto').style.display = 'none';
        Util.mensaje('error', r.message || 'No se pudo cargar el auto');
        return false;
    }
    mostrarInfo(r.data);
    llenarFormulario(r.data);
    await Util.cargarMarcas(document.getElementById('marca'), r.data.id_marca);
    return true;
}

document.getElementById('form-auto').addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = Util.leerFormulario();
    if (!datos) return;

    const r = await API.request('/autos/' + id, 'PUT', datos);
    if (r.status === 'ok') {
        await cargarAuto();                       // refresca la info mostrada
        Util.mensaje('ok', r.message);            // y confirma el cambio
    } else {
        Util.mensaje('error', r.message || 'No se pudo guardar');
    }
});

if (!id) {
    Util.mensaje('error', 'Falta el id del auto en la URL.');
    document.getElementById('form-auto').style.display = 'none';
} else {
    cargarAuto();
}
