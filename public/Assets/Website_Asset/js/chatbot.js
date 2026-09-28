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

    /* ---------- Saved state (history survives page changes) ---------- */
    var KEY = 'mlchat_state_v1';
    var state = loadState();

    function loadState() {
        try {
            var s = JSON.parse(sessionStorage.getItem(KEY));
            if (s && Array.isArray(s.messages)) return s;
        } catch (e) {}
        return { open: false, greeted: false, messages: [] };
    }

    function saveState() {
        try {
            state.messages = state.messages.slice(-50);
            sessionStorage.setItem(KEY, JSON.stringify(state));
        } catch (e) {}
    }

    /* ---------- Built-in answers (edit freely) ----------
       Keys: lowercase, no punctuation (hyphens and apostrophes are stripped
       from the user's text before matching, so write 'pre order', not 'pre-order').
       Phrases with more words score higher than single words.
       Order no longer matters: the entry with the best score wins. */
    var KB = [
        /* --- Payment & cost --- */
        { keys: ['pay', 'payment', 'cash', 'card', 'price', 'prices', 'paise', 'qeemat', 'kitne', 'kitna', 'rate', 'online payment', 'easypaisa', 'jazzcash'],
          text: 'You pay in person at the farmer\'s stall when you pick up your order. MarketLink does not take online payments.',
          links: [['Pickup guide', '/pickup-guidelines']] },

        { keys: ['fee', 'fees', 'free', 'cost', 'charge', 'charges', 'commission', 'hidden charges'],
          text: 'Browsing and pre-ordering on MarketLink is free for customers. You only pay the farmer for your produce at pickup.' },

        /* --- Delivery --- */
        { keys: ['deliver', 'delivery', 'shipping', 'courier', 'home delivery', 'ghar', 'ghar tak', 'rider'],
          text: 'We don\'t deliver. MarketLink is pickup only: you pre-order online, then collect at the market stall.',
          links: [['Find a market', '/markets']] },

        /* --- How it works --- */
        { keys: ['how', 'work', 'works', 'order', 'pre order', 'preorder', 'start', 'kaise', 'kaam', 'shuru', 'process', 'steps'],
          text: 'It takes four steps: find your market, browse seasonal products, place a pre-order and choose a pickup window, then meet the farmer at the stall to collect and pay.',
          links: [['Find a market', '/markets'], ['Browse products', '/products']] },

        /* --- Pickup --- */
        { keys: ['pickup', 'pick up', 'collect', 'window', 'time', 'timing', 'timings', 'slot', 'kab', 'wapis', 'late', 'miss pickup', 'missed'],
          text: 'After you place a pre-order, choose an available pickup window. Head to the farmer\'s stall in that window, collect your produce and pay them directly.',
          links: [['Pickup guide', '/pickup-guidelines']] },

        /* --- Farmers --- */
        { keys: ['farmer', 'farmers', 'grower', 'growers', 'sell', 'seller', 'join', 'register', 'stall', 'onboard', 'kisan', 'zamindar', 'become a farmer', 'join as a farmer'],
          text: 'Farmers can sign up, share their products and weekly stock, and manage pre-orders around their market schedule. New farmers are usually verified within 24 hours.',
          links: [['Join as a farmer', '/register'], ['Meet the growers', '/farmers']] },

        { keys: ['verify', 'verified', 'verification', 'approval', 'approve', 'approved', 'pending'],
          text: 'New farmer accounts are reviewed by the team, usually within 24 hours. Once approved, you can list products and receive pre-orders.',
          links: [['Contact us', '/contact']] },

        { keys: ['add product', 'list product', 'upload product', 'update stock', 'manage stock', 'weekly stock'],
          text: 'Farmers can add products, set prices and update weekly stock from their dashboard. Customers see the latest stock before they order.',
          links: [['Join as a farmer', '/register']] },

        /* --- Markets --- */
        { keys: ['market', 'markets', 'where', 'location', 'locations', 'near', 'nearby', 'day', 'days', 'kahan', 'kahaan', 'mandi', 'bazaar', 'address', 'directions', 'map'],
          text: 'Browse markets by location and operating day, see directions and discover which growers take part.',
          links: [['Explore markets', '/markets']] },

        /* --- Products --- */
        { keys: ['product', 'products', 'harvest', 'vegetable', 'vegetables', 'fruit', 'fruits', 'stock', 'sabzi', 'phal', 'sabziyan', 'items', 'what can i buy'],
          text: 'You can see this week\'s harvest, compare prices and check stock before you plan your basket.',
          links: [['Browse products', '/products']] },

        { keys: ['season', 'seasonal', 'available', 'availability', 'out of stock', 'sold out', 'mojood', 'dastiyab'],
          text: 'Products change with the season and weekly harvest. Stock shown on a product page is updated by the farmer.',
          links: [['Browse products', '/products']] },

        { keys: ['organic', 'fresh', 'quality', 'pesticide', 'pesticides', 'chemical', 'chemicals', 'taaza', 'taza', 'khalis', 'spray'],
          text: 'Each farmer describes how they grow their produce on their profile. Check the grower page for details before ordering.',
          links: [['Meet the growers', '/farmers']] },

        { keys: ['quantity', 'minimum', 'maximum', 'kg', 'kilo', 'dozen', 'bulk', 'wholesale', 'limit'],
          text: 'Quantity limits are set by each farmer and shown on the product page. For bulk or wholesale needs, message the team and we\'ll help you connect with the grower.',
          links: [['Contact us', '/contact']] },

        /* --- Orders --- */
        { keys: ['cancel', 'cancellation', 'change order', 'edit order', 'modify', 'update order', 'order cancel', 'cancel order', 'badalna'],
          text: 'You can cancel or change a pre-order from your dashboard before the pickup window opens. After that, please contact the farmer or the team.',
          links: [['Contact us', '/contact']] },

        { keys: ['track', 'status', 'order status', 'my order', 'my orders', 'confirmation', 'confirmed', 'history', 'meri order'],
          text: 'You can see the status of all your pre-orders, pickup windows and past orders from your dashboard once you are logged in.',
          links: [['Login', '/login']] },

        /* --- Account --- */
        { keys: ['account', 'login', 'log in', 'sign in', 'sign up', 'signup', 'password', 'forgot', 'reset', 'profile', 'account banana'],
          text: 'Create a free account to place pre-orders and leave reviews. If you forgot your password, use the reset link on the login page.',
          links: [['Register', '/register'], ['Login', '/login']] },

        /* --- Reviews --- */
        { keys: ['review', 'reviews', 'rating', 'ratings', 'feedback', 'stars'],
          text: 'After a completed pickup you can leave a review from your dashboard. Published reviews appear on the grower\'s profile.',
          links: [['Meet the growers', '/farmers']] },

        /* --- Problems --- */
        { keys: ['refund', 'wrong', 'damaged', 'complaint', 'problem', 'issue', 'bad', 'rotten', 'masla', 'shikayat', 'wapas'],
          text: 'Sorry about that! Please talk to the farmer at the stall first, and if it is not resolved, send us a message with your order details.',
          links: [['Contact us', '/contact']] },

        /* --- Contact --- */
        { keys: ['contact', 'email', 'phone', 'call', 'team', 'help', 'support', 'human', 'number', 'rabta', 'madad', 'office hours'],
          text: 'You can reach the team Mon to Fri, 9 AM to 5 PM PKT at team@marketlink-project.example, or use the contact form.',
          links: [['Contact us', '/contact']] },

        /* --- About --- */
        { keys: ['about', 'what is marketlink', 'marketlink', 'who are you', 'kya hai', 'mission', 'purpose'],
          text: 'MarketLink connects local farmers and shoppers. You pre-order fresh seasonal produce online and collect it directly from the farmer at a local market stall.',
          links: [['Explore markets', '/markets'], ['Meet the growers', '/farmers']] },

        { keys: ['safe', 'safety', 'secure', 'security', 'privacy', 'data', 'trust', 'trusted'],
          text: 'We only ask for the details needed to manage your pre-orders. Farmers are verified by the team, and you always pay in person, so no card details are collected online.',
          links: [['Contact us', '/contact']] },

        { keys: ['language', 'urdu', 'english', 'roman urdu'],
          text: 'You can ask me in English or Roman Urdu. I\'ll do my best to help with markets, pickup, payments and more.' },

        /* --- Small talk --- */
        { keys: ['hello', 'hi', 'hey', 'salam', 'assalam', 'assalamualaikum', 'aoa', 'good morning', 'good evening'],
          text: 'Hello! Ask me about markets, pickup, payments or becoming a farmer.' },

        { keys: ['thanks', 'thank you', 'thankyou', 'shukriya', 'jazak allah', 'jazakallah', 'thx'],
          text: 'You\'re welcome! Ask me anything else about markets or pickup.' },

        { keys: ['bye', 'goodbye', 'allah hafiz', 'khuda hafiz', 'see you'],
          text: 'Goodbye! Happy shopping at your local market.' }
    ];

    var CHIPS = ['How does it work?', 'How do I pay?', 'Do you deliver?', 'Join as a farmer', 'Contact the team'];
    var FALLBACK = {
        text: 'I\'m not sure about that one yet. Try one of the topics below, or send a message to the team.',
        links: [['Contact us', '/contact']]
    };

    /* ---------- UI helpers ---------- */
    function scrollDown() { log.scrollTop = log.scrollHeight; }

    // Accepts both [label, url] arrays and {label, url} objects
    function normLinks(links) {
        return (links || []).map(function (l) {
            return Array.isArray(l) ? { label: l[0], url: l[1] } : l;
        });
    }

    function drawMsg(who, text, links) {
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
                a.href = /^https?:/.test(l.url) ? l.url : base + l.url;
                a.textContent = l.label + ' \u2197';
                box.appendChild(a);
            });
            el.appendChild(box);
        }
        log.appendChild(el);
        scrollDown();
        return el;
    }

    function addMsg(who, text, links) {
        links = normLinks(links);
        state.messages.push({ who: who, text: text, links: links });
        saveState();
        return drawMsg(who, text, links);
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

    // Suggestions hamesha neeche dikhte rahenge
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
    // Scored matching: whole words / phrases only, best score wins.
    function localReply(q) {
        var s = ' ' + q.toLowerCase().replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim() + ' ';
        var best = null, bestScore = 0;

        KB.forEach(function (entry) {
            var score = 0;
            entry.keys.forEach(function (k) {
                if (s.indexOf(' ' + k + ' ') !== -1) {
                    // whole word / phrase match (phrases get more weight)
                    score += k.split(' ').length;
                } else if (k.length > 3 && s.indexOf(' ' + k) !== -1) {
                    // simple word forms: "delivery" ~ "deliver", "farmers" ~ "farmer"
                    score += 0.5;
                }
            });
            if (score > bestScore) { bestScore = score; best = entry; }
        });

        return best || FALLBACK;
    }

    // Optional backend: POST { message } to data-endpoint, expect { reply, links?: [{label, url}, ...] }
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
    function open(restoring) {
        panel.hidden = false;
        fab.setAttribute('aria-expanded', 'true');
        fab.setAttribute('aria-label', 'Close chat assistant');
        state.open = true;
        if (!state.greeted) {
            state.greeted = true;
            addMsg('bot', 'Hi! I\'m the MarketLink assistant. What would you like to know?');
        }
        renderChips();
        saveState();
        scrollDown();
        if (!restoring) setTimeout(function () { input.focus(); }, 60);
    }

    function close() {
        panel.hidden = true;
        fab.setAttribute('aria-expanded', 'false');
        fab.setAttribute('aria-label', 'Open chat assistant');
        state.open = false;
        saveState();
        fab.focus();
    }

    /* ---------- Restore previous conversation on page load ---------- */
    state.messages.forEach(function (m) { drawMsg(m.who, m.text, m.links); });
    if (state.open) open(true);

    /* ---------- Mouse wheel / touch scroll sirf chat tak ---------- */
    ['wheel', 'touchmove'].forEach(function (evt) {
        log.addEventListener(evt, function (e) { e.stopPropagation(); }, { passive: true });
    });
    /* ---------- Suggestions bar: horizontal scroll (wheel, drag, touch) ---------- */
    (function setupChipsScroll() {
        var st = chipsBox.style;
        st.display = 'flex';
        st.flexWrap = 'nowrap';
        st.overflowX = 'auto';
        st.overflowY = 'hidden';
        st.whiteSpace = 'nowrap';
        st.touchAction = 'pan-x';            // touch swipe left/right
        st.webkitOverflowScrolling = 'touch';
        st.overscrollBehaviorX = 'contain';
        st.scrollbarWidth = 'none';          // hide scrollbar (Firefox)
        st.msOverflowStyle = 'none';
        st.cursor = 'grab';

        // hide scrollbar (Chrome / Safari)
        var css = document.createElement('style');
        css.textContent = '#mlChat .mlchat-chips::-webkit-scrollbar{display:none}' +
                          '#mlChat .mlchat-chips button{flex:0 0 auto;white-space:nowrap}';
        document.head.appendChild(css);

        // mouse wheel -> horizontal scroll
        chipsBox.addEventListener('wheel', function (e) {
            if (chipsBox.scrollWidth > chipsBox.clientWidth) {
                chipsBox.scrollLeft += (Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY);
                e.preventDefault();
            }
            e.stopPropagation();
        }, { passive: false });

        // mouse drag -> horizontal scroll
        var down = false, moved = false, startX = 0, startLeft = 0;
        chipsBox.addEventListener('mousedown', function (e) {
            down = true; moved = false;
            startX = e.pageX;
            startLeft = chipsBox.scrollLeft;
            chipsBox.style.cursor = 'grabbing';
        });
        window.addEventListener('mousemove', function (e) {
            if (!down) return;
            var dx = e.pageX - startX;
            if (Math.abs(dx) > 5) moved = true;
            if (moved) {
                chipsBox.scrollLeft = startLeft - dx;
                e.preventDefault();
            }
        });
        window.addEventListener('mouseup', function () {
            if (!down) return;
            down = false;
            chipsBox.style.cursor = 'grab';
        });
        // if the user dragged, don't trigger the chip click
        chipsBox.addEventListener('click', function (e) {
            if (moved) { e.stopPropagation(); e.preventDefault(); moved = false; }
        }, true);
    })();

    /* ---------- Events ---------- */
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