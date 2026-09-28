document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".ml-favorite-btn").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      btn.classList.toggle("is-active");
      var icon = btn.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-regular");
        icon.classList.toggle("fa-solid");
      }
    });
  });

  document.querySelectorAll("[data-quantity]").forEach(function (wrapper) {
    var input = wrapper.querySelector("input");
    var min = parseInt(wrapper.getAttribute("data-min") || "1", 10);
    var max = parseInt(wrapper.getAttribute("data-max") || "999", 10);
    wrapper.querySelectorAll("button").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var value = parseInt(input.value, 10) || min;
        if (btn.hasAttribute("data-decrement")) {
          value = Math.max(min, value - 1);
        } else {
          value = Math.min(max, value + 1);
        }
        input.value = value;
        input.dispatchEvent(new Event("change"));
        if (wrapper.hasAttribute("data-auto-submit")) {
          var form = wrapper.closest("form");
          if (form) {
            form.submit();
          }
        }
      });
    });
  });

  document.querySelectorAll(".ml-accordion-header").forEach(function (header) {
    header.addEventListener("click", function () {
      var item = header.closest(".ml-accordion-item");
      var wasOpen = item.classList.contains("is-open");
      item
        .closest(".ml-accordion")
        ?.querySelectorAll(".ml-accordion-item")
        .forEach(function (el) {
          el.classList.remove("is-open");
        });
      if (!wasOpen) {
        item.classList.add("is-open");
      }
    });
  });

  document.querySelectorAll("[data-thumbnail-target]").forEach(function (thumb) {
    thumb.addEventListener("click", function () {
      var targetSelector = thumb.getAttribute("data-thumbnail-target");
      var target = document.querySelector(targetSelector);
      if (target) {
        target.src = thumb.getAttribute("data-image") || thumb.src;
      }
      thumb
        .closest("[data-thumbnail-group]")
        ?.querySelectorAll("[data-thumbnail-target]")
        .forEach(function (el) {
          el.classList.remove("is-active");
        });
      thumb.classList.add("is-active");
    });
  });

  document.querySelectorAll("[data-filter-toggle]").forEach(function (toggle) {
    toggle.addEventListener("click", function () {
      var target = document.querySelector(toggle.getAttribute("data-filter-toggle"));
      if (target) {
        target.classList.toggle("d-none");
      }
    });
  });

  document.querySelectorAll("[data-passcount]").forEach(function (input) {
    input.addEventListener("input", function () {
      var counter = document.querySelector(input.getAttribute("data-passcount"));
      if (counter) {
        counter.textContent = input.value.length;
      }
    });
  });
});
