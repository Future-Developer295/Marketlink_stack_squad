
    document.addEventListener("DOMContentLoaded", () => {
      const basket = document.getElementById("basketWrapper");
      const loadingText = document.getElementById("loadingText");
      const loaderOverlay = document.getElementById("loaderOverlay");
      const websiteContent = document.getElementById("website-content");

      const produceItems = [
        document.getElementById("prod1"),
        document.getElementById("prod2"),
        document.getElementById("prod3"),
        document.getElementById("prod4")
      ];

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
        loadingText.classList.add("fade-out");
      }, allItemsDroppedTime + 550);

      setTimeout(() => {
        loaderOverlay.classList.add("fade-out");
        websiteContent.classList.add("visible");
      }, allItemsDroppedTime + 1450);
    });
  