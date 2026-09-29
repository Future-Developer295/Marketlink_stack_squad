
    document.addEventListener("DOMContentLoaded", () => {
      const basket = document.getElementById("basketWrapper");
      const loadingText = document.getElementById("loadingText");
      const loaderOverlay = document.getElementById("loaderOverlay");
      const websiteContent = document.getElementById("website-content");

      // Loader markup only exists on some pages - do nothing (and never throw) elsewhere.
      if (!basket || !loaderOverlay) return;

      const produceItems = [
        document.getElementById("prod1"),
        document.getElementById("prod2"),
        document.getElementById("prod3"),
        document.getElementById("prod4")
      ].filter(Boolean);

      basket.classList.add("pop-in");

      let dropDelay = 450;
      produceItems.forEach((item, index) => {
        setTimeout(() => {
          item.classList.add("drop");
        }, dropDelay + (index * 200));
      });

      const allItemsDroppedTime = dropDelay + (produceItems.length * 200) + 200;

      setTimeout(() => {
        basket.classList.remove("pop-in");
        basket.classList.add("shake-full");
      }, allItemsDroppedTime);

      setTimeout(() => {
        basket.classList.remove("shake-full");
        basket.classList.add("drive-across");
        if (loadingText) loadingText.classList.add("fade-out");
      }, allItemsDroppedTime + 550);

      setTimeout(() => {
        loaderOverlay.classList.add("fade-out");
        if (websiteContent) websiteContent.classList.add("visible");
      }, allItemsDroppedTime + 1450);
    });
  