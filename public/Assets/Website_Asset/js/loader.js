document.documentElement.classList.add('loader-lock');

document.addEventListener('DOMContentLoaded', () => {
  const basket = document.getElementById('basketWrapper');
  const loadingText = document.getElementById('loadingText');
  const loaderOverlay = document.getElementById('loaderOverlay');

  const produceItems = [
    document.getElementById('prod1'),
    document.getElementById('prod2'),
    document.getElementById('prod3'),
    document.getElementById('prod4')
  ].filter(Boolean);

  if (!basket || !loadingText || !loaderOverlay) {
    document.documentElement.classList.remove('loader-lock');

    window.dispatchEvent(
      new CustomEvent('marketlink:loader-finished')
    );

    return;
  }

  basket.classList.add('pop-in');

  const dropDelay = 450;
  const itemDelay = 200;

  for (let index = 0; index < produceItems.length; index++) {
    setTimeout(() => {
      produceItems[index].classList.add('drop');
    }, dropDelay + (index * itemDelay));
  }

  const allItemsDroppedTime =
    dropDelay +
    (produceItems.length * itemDelay) +
    200;

  setTimeout(() => {
    basket.classList.remove('pop-in');
    basket.classList.add('shake-full');
  }, allItemsDroppedTime);

  setTimeout(() => {
    basket.classList.remove('shake-full');
    basket.classList.add('drive-across');
    loadingText.classList.add('fade-out');
  }, allItemsDroppedTime + 550);

  setTimeout(() => {
    loaderOverlay.classList.add('fade-out');

    document.documentElement.classList.remove('loader-lock');

    window.dispatchEvent(
      new CustomEvent('marketlink:loader-finished')
    );
  }, allItemsDroppedTime + 1450);
});