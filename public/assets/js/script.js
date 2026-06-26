
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
<<<<<<< HEAD
})();

/* ==========================================================================
   TraidX Form Handling & API Bridge Component
   Pure Vanilla JS — Handles secure native asynchronous submissions to endpoints
   ========================================================================== */

(function () {
  "use strict";

  // Configuration settings for your global API base route
  var API_BASE_URL = "/api/v1/auth";

  /**
   * Appends or updates standard user notice message boxes right above the form fields.
   * Utilizes your existing design color system states via styles.css rules.
   */
  function displayFeedbackMessage(formElement, messageText, isError) {
    // Look for an existing message container within this form
    var messageBox = formElement.querySelector(".auth-feedback-msg");
    
    if (!messageBox) {
      messageBox = document.createElement("div");
      messageBox.className = "auth-feedback-msg";
      // Insert right after the header information segment
      var formHeader = formElement.querySelector(".form-header");
      if (formHeader) {
        formHeader.parentNode.insertBefore(messageBox, formHeader.nextSibling);
      } else {
        formElement.insertBefore(messageBox, formElement.firstChild);
      }
    }

    // Set interactive visual states
    messageBox.textContent = messageText;
    if (isError) {
      messageBox.classList.add("auth-feedback-msg--error");
      messageBox.classList.remove("auth-feedback-msg--success");
    } else {
      messageBox.classList.add("auth-feedback-msg--success");
      messageBox.classList.remove("auth-feedback-msg--error");
    }
  }

  /**
   * Standardized asynchronous network POST handler
   */
  function submitAuthData(endpoint, dataObject, formElement, successCallback) {
    // Provide a localized immediate loading state to the user
    displayFeedbackMessage(formElement, "Connecting to security gate...", false);

    fetch(API_BASE_URL + endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(dataObject)
    })
    .then(function (response) {
      return response.json().then(function (json) {
        if (!response.ok) {
          // If server responds with HTTP 400/401/422 errors, reject the promise chain
          return Promise.reject(json);
        }
        return json;
      });
    })
    .then(function (successData) {
      displayFeedbackMessage(formElement, successData.message || "Entry authenticated successfully.", false);
      if (typeof successCallback === "function") {
        successCallback(successData);
      }
    })
    .catch(function (errorData) {
      // Capture structured message payloads or fall back to native strings
      var fallbackErr = "Connection lost. Please verify your terminal network connection.";
      var explicitErr = errorData && errorData.message ? errorData.message : fallbackErr;
      displayFeedbackMessage(formElement, explicitErr, true);
    });
  }

  /* ==========================================================================
     Event Bindings Initialization
     ========================================================================== */
  document.addEventListener("DOMContentLoaded", function () {
    
    // 1. LOGIN SUBMISSION HANDLING
    var loginForm = document.getElementById("login-form");
    if (loginForm) {
      loginForm.removeAttribute("action"); // Overwrite standard redirection
      loginForm.removeAttribute("method");

      loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        var emailInput = document.getElementById("login-email");
        var passwordInput = document.getElementById("login-password");

        if (!emailInput || !passwordInput) return;

        var payload = {
          email: emailInput.value.trim(),
          password: passwordInput.value
        };

        submitAuthData("/login", payload, loginForm, function (response) {
          // Save stateless JWT token value securely upon successful confirmation
          if (response.data && response.data.token) {
            localStorage.setItem("traidx_jwt", response.data.token);
          }
          // Safely redirect customer directly to their dashboard home path
          setTimeout(function () {
            window.location.href = "dashboard.html";
          }, 1000);
        });
      });
    }

    // 2. REGISTRATION SUBMISSION HANDLING
    var registerForm = document.getElementById("register-form");
    if (registerForm) {
      registerForm.removeAttribute("action");
      registerForm.removeAttribute("method");

      registerForm.addEventListener("submit", function (e) {
        e.preventDefault();

        var firstName = document.getElementById("reg-first-name");
        var lastName = document.getElementById("reg-last-name");
        var email = document.getElementById("reg-email");
        var password = document.getElementById("reg-password");

        if (!firstName || !lastName || !email || !password) return;

        // Perform basic input check before firing network requests
        if (password.value.length < 8) {
          displayFeedbackMessage(registerForm, "Security threshold mismatch: Password must be at least 8 characters.", true);
          password.focus();
          return;
        }

        var payload = {
          first_name: firstName.value.trim(),
          last_name: lastName.value.trim(),
          email: email.value.trim(),
          password: password.value
        };

        submitAuthData("/register", payload, registerForm, function () {
          // Send user smoothly to registration sign-in completion node
          setTimeout(function () {
            window.location.href = "login.html?registered=true";
          }, 1500);
        });
      });
    }

    if (loginForm && window.location.search.indexOf("registered=true") !== -1) {
      displayFeedbackMessage(loginForm, "Profile generated successfully. Sign in with your parameters below.", false);
    }

  });

})();


/**
 * TraidX System Flow - Password Recovery Module
 */
document.addEventListener('DOMContentLoaded', () => {
  let isEmailVerified = false;

  const cardRoot = document.getElementById('auth-card-root');
  const authStage = document.getElementById('auth-stage');
  const forgotForm = document.getElementById('forgot-form');
  const resetForm = document.getElementById('reset-form');
  
  const emailInput = document.getElementById('recovery-email');
  const tokenInput = document.getElementById('recovery-token');
  const tokenContainer = document.getElementById('token-field-container');
  const submitBtn = document.getElementById('action-submit-btn');
  const instructions = document.getElementById('recovery-instructions');

  // Trigger smooth structural scale entry
  if (cardRoot) {
    setTimeout(() => {
      cardRoot.classList.remove('initial-zoom');
    }, 50);
  }

  // Handle Stage 1 Form Submissions
  if (forgotForm) {
    forgotForm.addEventListener('submit', (e) => {
      e.preventDefault();

      if (!isEmailVerified) {
        // Phase 1: Lock Email & Expose Token Container
        emailInput.setAttribute('readonly', 'true');
        emailInput.style.opacity = '0.7';

        tokenContainer.classList.add('is-revealed');
        tokenInput.setAttribute('required', 'true');
        tokenInput.focus();

        instructions.innerText = "A secure verification key has been routed to your track. Provide the token parameters to clear entry.";
        submitBtn.innerText = "Verify Credentials →";
        
        isEmailVerified = true;
      } else {
        // Phase 2: Slide Stage to Reset Password Form
        authStage.classList.add('slide-to-reset');
      }
    });
  }

  // Handle Stage 2 Password Override Completion
  if (resetForm) {
    resetForm.addEventListener('submit', (e) => {
      e.preventDefault();
      // Route back to central terminal point safely
      window.location.href = 'login.html';
    });
  }
});
=======
})();
>>>>>>> 1758db6649466affca38dfcfa2f398ace1271eff
