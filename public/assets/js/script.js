
(function () {
  var nav = document.querySelector(".nav");
  if (nav) {
    var onScroll = function () {
      if (window.scrollY > 8) {
        nav.classList.add("nav--scrolled");
      } else {
        nav.classList.remove("nav--scrolled");
      }
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  /* ---------- From / To swap button ---------- */
  var swapBtn = document.querySelector(".swap-btn");
  var fromInput = document.getElementById("from");
  var toInput = document.getElementById("to");

  function swapFields() {
    if (!fromInput || !toInput) return;
    var temp = fromInput.value;
    fromInput.value = toInput.value;
    toInput.value = temp;
    fromInput.focus();
  }

  if (swapBtn) {
    swapBtn.addEventListener("click", swapFields);
    // role="button" elements need explicit keyboard activation
    swapBtn.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        swapFields();
      }
    });
  }

  /* ---------- Search form ---------- */
  var searchForm = document.querySelector(".search-card");

  function showFormMessage(text, isError) {
    var existing = searchForm.querySelector(".form-message");
    if (existing) existing.remove();

    var msg = document.createElement("p");
    msg.className = "form-message" + (isError ? " form-message--error" : "");
    msg.setAttribute("role", "status");
    msg.textContent = text;
    searchForm.appendChild(msg);
  }

  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault();

      var from = fromInput ? fromInput.value.trim() : "";
      var to = toInput ? toInput.value.trim() : "";

      if (!from || !to) {
        showFormMessage("Add both a departure and destination city to search.", true);
        (from ? toInput : fromInput).focus();
        return;
      }

      if (from.toLowerCase() === to.toLowerCase()) {
        showFormMessage("Departure and destination can't be the same place.", true);
        toInput.focus();
        return;
      }

      // No backend is connected yet — this is where a fetch() call to
      // GET /api/v1/flights/search will go once the API is live.
      // For now, confirm the search was understood so the form doesn't
      // feel broken to anyone testing the page.
      showFormMessage(
        "Searching " + from + " \u2192 " + to + " \u2014 flight results will appear here once search is connected.",
        false
      );
    });
  }

  /* ---------- Smooth-scroll for in-page anchor links ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener("click", function (e) {
      var targetId = link.getAttribute("href");
      if (targetId.length <= 1) return; // bare "#" links (logo, etc.)
      var target = document.querySelector(targetId);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });
})();