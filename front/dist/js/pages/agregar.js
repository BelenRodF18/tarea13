Util.cargarMarcas(document.getElementById('marca'));

document.getElementById('form-auto').addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = Util.leerFormulario();
    if (!datos) return;

    const r = await API.request('/autos', 'POST', datos);
    if (r.status === 'ok') {
        sessionStorage.setItem('aviso', `${r.message} (ID ${r.id}).`);
        location.href = 'index.html';             // el nuevo auto aparece en el listado
    } else {
        Util.mensaje('error', r.message || 'No se pudo guardar el auto');
    }
});
