async function cargarAutos() {
    const cuerpo = document.getElementById('tabla-autos');
    const r = await API.request('/autos');

    if (r.status !== 'ok') {
        cuerpo.innerHTML = '<tr><td colspan="6" class="vacio">No se pudieron cargar los autos.</td></tr>';
        Util.mensaje('error', r.message || 'Error al cargar los autos');
        return;
    }
    if (r.data.length === 0) {
        cuerpo.innerHTML = '<tr><td colspan="6" class="vacio">Todavía no hay autos registrados.</td></tr>';
        return;
    }

    cuerpo.innerHTML = r.data.map(a => `
        <tr class="fila" data-id="${a.id_vehiculo}">
            <td>${Util.esc(a.nombre_marca)}</td>
            <td><a href="auto.html?id=${a.id_vehiculo}">${Util.esc(a.modelo)}</a></td>
            <td class="ocultar-movil">${a.anio ?? '-'}</td>
            <td class="numero">${Util.dinero(a.precio)}</td>
            <td class="ocultar-movil"><span class="etiqueta">${Util.esc(a.estado)}</span></td>
            <td><span class="etiqueta ${Number(a.vendido) ? 'vendido' : 'disponible'}">${Number(a.vendido) ? 'Vendido' : 'Disponible'}</span></td>
        </tr>`).join('');

    // Click en cualquier parte de la fila -> pagina del auto
    cuerpo.querySelectorAll('tr.fila').forEach(fila => {
        fila.addEventListener('click', () => { location.href = 'auto.html?id=' + fila.dataset.id; });
    });
}

// Mensaje que deja agregar.html / auto.html al redirigir
const aviso = sessionStorage.getItem('aviso');
if (aviso) {
    sessionStorage.removeItem('aviso');
    Util.mensaje('ok', aviso);
}
cargarAutos();
