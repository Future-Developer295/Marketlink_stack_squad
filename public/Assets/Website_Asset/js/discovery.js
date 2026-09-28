(() => {
    const wide = matchMedia('(min-width: 992px)');
    const filters = document.querySelector('.discovery-filters');
    const sync = () => { if (filters) filters.open = wide.matches; };
    sync();
    wide.addEventListener('change', sync);
})();
