(function () {
    const activa = document.body.dataset.pagina;
    const links = [
        { id: 'listado', href: 'index.html', texto: 'Listado de autos' },
        { id: 'agregar', href: 'agregar.html', texto: 'Agregar auto' }
    ];
    document.getElementById('navbar').innerHTML =
        '<nav class="navbar"><a class="marca" href="index.html">🚗 Automotora</a>' +
        links.map(l => `<a class="link ${l.id === activa ? 'activo' : ''}" href="${l.href}">${l.texto}</a>`).join('') +
        '</nav>';
})();
