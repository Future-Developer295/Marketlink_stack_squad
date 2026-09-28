

<div class="mlchat" id="mlChat" data-base="{{ url('/') }}" data-endpoint="">

    <button class="mlchat-fab" type="button" aria-expanded="false" aria-controls="mlChatPanel" aria-label="Open chat assistant">
        <i class="fa-solid fa-comment-dots mlchat-ic-open" aria-hidden="true"></i>
        <i class="fa-solid fa-xmark mlchat-ic-close" aria-hidden="true"></i>
        <span class="mlchat-fab-tip" aria-hidden="true">Need help?</span>
    </button>

    <section class="mlchat-panel" id="mlChatPanel" role="dialog" aria-label="MarketLink assistant" hidden>
        <header class="mlchat-head">
            <span class="mlchat-avatar" aria-hidden="true"><i class="fa-solid fa-seedling"></i></span>
            <div>
                <strong>Market assistant</strong>
                <small><span class="mlchat-dot" aria-hidden="true"></span> Here to help</small>
            </div>
            <button type="button" class="mlchat-close" aria-label="Close chat"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            <span class="mlchat-mark" aria-hidden="true">✳</span>
        </header>

        <div class="mlchat-log" role="log" aria-live="polite" aria-label="Conversation" tabindex="0"></div>

        <div class="mlchat-chips" aria-label="Suggested questions"></div>

        <form class="mlchat-form" autocomplete="off">
            <label class="mlchat-sr" for="mlChatInput">Type your question</label>
            <input type="text" id="mlChatInput" placeholder="Ask about markets, pickup, farmers…" maxlength="300">
            <button type="submit" aria-label="Send message"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i></button>
        </form>

        <p class="mlchat-note">Pickup only · Pay at the stall</p>
    </section>
</div>
