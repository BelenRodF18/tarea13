const Util = {
    esc(texto) {
        const d = document.createElement('div');
        d.textContent = texto ?? '';
        return d.innerHTML;
    },
    dinero(valor) {
        return 'US$ ' + Number(valor).toLocaleString('es-UY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    mensaje(tipo, texto) {
        const caja = document.getElementById('mensaje');
        caja.className = 'mensaje ' + tipo;
        caja.textContent = texto;
        caja.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    },
    async cargarMarcas(select, seleccionada = '') {
        const r = await API.request('/marcas');
        if (r.status !== 'ok') {
            Util.mensaje('error', r.message || 'No se pudieron cargar las marcas');
            return;
        }
        select.innerHTML = '<option value="">-- Seleccionar marca --</option>' +
            r.data.map(m => `<option value="${m.id_marca}">${Util.esc(m.nombre_marca)}</option>`).join('');
        select.value = String(seleccionada);
    },
    leerFormulario() {
        const g = id => document.getElementById(id);
        const datos = {
            id_marca: Number(g('marca').value),
            modelo: g('modelo').value.trim(),
            anio: g('anio').value === '' ? '' : Number(g('anio').value),
            precio: g('precio').value === '' ? '' : Number(g('precio').value),
            estado: g('estado').value
        };
        const anioMax = new Date().getFullYear() + 1;
        let error = '';
        if (!datos.id_marca) error = 'Seleccioná una marca.';
        else if (!datos.modelo) error = 'El modelo es obligatorio.';
        else if (datos.anio === '' || !Number.isInteger(datos.anio) || datos.anio < 1886 || datos.anio > anioMax)
            error = `El año es obligatorio y debe estar entre 1886 y ${anioMax}.`;
        else if (datos.precio === '' || datos.precio < 0) error = 'El precio es obligatorio y no puede ser negativo.';
        else if (!datos.estado) error = 'Seleccioná el estado (Nuevo o Usado).';
        if (error) { Util.mensaje('error', error); return null; }
        return datos;
    }
};
