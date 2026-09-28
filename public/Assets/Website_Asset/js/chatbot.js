/* public/Assets/Website_Asset/js/chatbot.js */
(function () {
    var root = document.getElementById('mlChat');
    if (!root) return;

    var fab = root.querySelector('.mlchat-fab');
    var panel = root.querySelector('.mlchat-panel');
    var closeBtn = root.querySelector('.mlchat-close');
    var log = root.querySelector('.mlchat-log');
    var chipsBox = root.querySelector('.mlchat-chips');
    var form = root.querySelector('.mlchat-form');
    var input = root.querySelector('.mlchat-form input');
    var base = (root.dataset.base || '').replace(/\/$/, '');
    var endpoint = root.dataset.endpoint || '';
    var greeted = false;

    /* ---------- Built-in answers (edit freely) ---------- */
    var KB = [
        { keys: ['pay', 'payment', 'cash', 'card', 'price'],
          text: 'You pay in person at the farmer\'s stall when you pick up your order. MarketLink does not take online payments.',
          links: [['Pickup guide', '/pickup-guidelines']] },
        { keys: ['deliver', 'shipping', 'courier', 'home'],
          text: 'We don\'t deliver. MarketLink is pickup only: you pre-order online, then collect at the market stall.',
          links: [['Find a market', '/markets']] },
        { keys: ['how', 'work', 'order', 'pre-order', 'preorder', 'start'],
          text: 'It takes four steps: find your market, browse seasonal products, place a pre-order and choose a pickup window, then meet the farmer at the stall to collect and pay.',
          links: [['Find a market', '/markets'], ['Browse products', '/products']] },
        { keys: ['pickup', 'pick up', 'collect', 'window', 'time'],
          text: 'After you place a pre-order, choose an available pickup window. Head to the farmer\'s stall in that window, collect your produce and pay them directly.',
          links: [['Pickup guide', '/pickup-guidelines']] },
        { keys: ['farmer', 'grower', 'sell', 'join', 'register', 'stall', 'onboard'],
          text: 'Farmers can sign up, share their products and weekly stock, and manage pre-orders around their market schedule. New farmers are usually verified within 24 hours.',
          links: [['Join as a farmer', '/register'], ['Meet the growers', '/farmers']] },
        { keys: ['market', 'where', 'location', 'near', 'day'],
          text: 'Browse markets by location and operating day, see directions and discover which growers take part.',
          links: [['Explore markets', '/markets']] },
        { keys: ['product', 'harvest', 'vegetable', 'fruit', 'stock'],
          text: 'You can see this week\'s harvest, compare prices and check stock before you plan your basket.',
          links: [['Browse products', '/products']] },
        { keys: ['review', 'rating'],
          text: 'After a completed pickup you can leave a review from your dashboard. Published reviews appear on the grower\'s profile.',
          links: [['Meet the growers', '/farmers']] },
        { keys: ['contact', 'email', 'phone', 'call', 'team', 'help', 'support', 'human'],
          text: 'You can reach the team Mon to Fri, 9 AM to 5 PM PKT at team@marketlink-project.example, or use the contact form.',
          links: [['Contact us', '/contact']] },
        { keys: ['hello', 'hi', 'hey', 'salam', 'assalam'],
          text: 'Hello! Ask me about markets, pickup, payments or becoming a farmer.' }
    ];

    var CHIPS = ['How does it work?', 'How do I pay?', 'Do you deliver?', 'Join as a farmer', 'Contact the team'];
    var FALLBACK = {
        text: 'I\'m not sure about that one yet. Try one of the topics below, or send a message to the team.',
        links: [['Contact us', '/contact']]
    };

    /* ---------- UI helpers ---------- */
    function scrollDown() { log.scrollTop = log.scrollHeight; }

    function addMsg(who, text, links) {
        var el = document.createElement('div');
        el.className = 'mlchat-msg ' + (who === 'user' ? 'is-user' : 'is-bot');
        var p = document.createElement('span');
        p.textContent = text;
        el.appendChild(p);
        if (links && links.length) {
            var box = document.createElement('div');
            box.className = 'mlchat-links';
            links.forEach(function (l) {
                var a = document.createElement('a');
                a.href = /^https?:/.test(l[1]) ? l[1] : base + l[1];
                a.textContent = l[0] + ' \u2197';
                box.appendChild(a);
            });
            el.appendChild(box);
        }
        log.appendChild(el);
        scrollDown();
        return el;
    }

    function showTyping() {
        var el = document.createElement('div');
        el.className = 'mlchat-msg is-bot mlchat-typing';
        el.setAttribute('aria-label', 'Assistant is typing');
        el.innerHTML = '<span></span><span></span><span></span>';
        log.appendChild(el);
        scrollDown();
        return el;
    }

    function renderChips() {
        chipsBox.innerHTML = '';
        CHIPS.forEach(function (label) {
            var b = document.createElement('button');
            b.type = 'button';
            b.textContent = label;
            b.addEventListener('click', function () { send(label); });
            chipsBox.appendChild(b);
        });
    }

    /* ---------- Reply logic ---------- */
    function localReply(q) {
        var s = q.toLowerCase();
        for (var i = 0; i < KB.length; i++) {
            for (var j = 0; j < KB[i].keys.length; j++) {
                if (s.indexOf(KB[i].keys[j]) !== -1) return KB[i];
            }
        }
        return FALLBACK;
    }

    // Optional backend: POST { message } to data-endpoint, expect { reply, links?: [[label, url], ...] }
    function remoteReply(q) {
        var tokenEl = document.querySelector('meta[name="csrf-token"]');
        return fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': tokenEl ? tokenEl.content : ''
            },
            body: JSON.stringify({ message: q })
        }).then(function (r) {
            if (!r.ok) throw new Error('bad response');
            return r.json();
        }).then(function (d) {
            return { text: d.reply, links: d.links || [] };
        });
    }

    function send(text) {
        text = (text || '').trim();
        if (!text) return;
        addMsg('user', text);
        input.value = '';
        var typing = showTyping();
        var wait = new Promise(function (res) { setTimeout(res, 550); });
        var answer = endpoint
            ? remoteReply(text).catch(function () { return localReply(text); })
            : Promise.resolve(localReply(text));
        Promise.all([answer, wait]).then(function (out) {
            typing.remove();
            addMsg('bot', out[0].text, out[0].links);
        });
    }

    /* ---------- Open / close ---------- */
    function open() {
        panel.hidden = false;
        fab.setAttribute('aria-expanded', 'true');
        fab.setAttribute('aria-label', 'Close chat assistant');
        if (!greeted) {
            greeted = true;
            addMsg('bot', 'Hi! I\'m the MarketLink assistant. What would you like to know?');
            renderChips();
        }
        setTimeout(function () { input.focus(); }, 60);
    }

    function close() {
        panel.hidden = true;
        fab.setAttribute('aria-expanded', 'false');
        fab.setAttribute('aria-label', 'Open chat assistant');
        fab.focus();
    }

    fab.addEventListener('click', function () { panel.hidden ? open() : close(); });
    closeBtn.addEventListener('click', close);
    root.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !panel.hidden) close();
    });
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        send(input.value);
    });
})();
