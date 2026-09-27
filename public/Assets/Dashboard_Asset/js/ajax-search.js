// Ajax-ifies every ".search-box" form on the dashboard so that
// searching and clearing never triggers a full page reload.
// It works by fetching the same GET route the form already points to,
// then swapping only the ".tbl-wrap" (table) portion of the page.
(function () {
    function getWrap() {
        return document.querySelector('.tbl-wrap');
    }

    function setLoading(isLoading) {
        var wrap = getWrap();
        if (wrap) {
            wrap.style.opacity = isLoading ? '0.5' : '';
            wrap.style.pointerEvents = isLoading ? 'none' : '';
        }
    }

    function swapContent(html, url) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');

        var newWrap = doc.querySelector('.tbl-wrap');
        var oldWrap = getWrap();

        if (newWrap && oldWrap) {
            oldWrap.innerHTML = newWrap.innerHTML;
        }

        // Keep the clear (x) button in sync without reloading.
        var newTools = doc.querySelector('.panel-tools .search-box');
        var oldForm = document.querySelector('.panel-tools .search-box');
        if (newTools && oldForm) {
            oldForm.innerHTML = newTools.innerHTML;
            bindClear(oldForm);
        }

        window.history.pushState({ ajaxSearch: true }, '', url);
    }

    function fetchAndSwap(url) {
        setLoading(true);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (res) {
                return res.text();
            })
            .then(function (html) {
                swapContent(html, url);
            })
            .catch(function () {
                // Fall back to a normal navigation if the ajax call fails.
                window.location.href = url;
            })
            .finally(function () {
                setLoading(false);
            });
    }

    function bindClear(scope) {
        var clearLink = (scope || document).querySelector('.search-clear');
        if (!clearLink || clearLink.dataset.ajaxBound) return;

        clearLink.dataset.ajaxBound = '1';
        clearLink.addEventListener('click', function (e) {
            e.preventDefault();
            fetchAndSwap(clearLink.getAttribute('href'));
        });
    }

    document.querySelectorAll('form.search-box').forEach(function (form) {
        if (form.method.toLowerCase() !== 'get') return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var params = new URLSearchParams(new FormData(form));
            var url = form.getAttribute('action') + '?' + params.toString();

            fetchAndSwap(url);
        });

        bindClear(form);
    });

    // Support browser back/forward after an ajax search.
    window.addEventListener('popstate', function () {
        fetchAndSwap(window.location.href);
    });
})();
