document.addEventListener("DOMContentLoaded", () => {
  "use strict";

  const API_BASE_URL = "/api/v1/auth";
  
  // Core DOM Selections
  const nav = document.querySelector(".nav");
  const swapBtn = document.querySelector(".swap-btn");
  const fromInput = document.getElementById("from");
  const toInput = document.getElementById("to");
  const searchForm = document.querySelector(".search-card");
  const registerForm = document.getElementById("register-form");
  const loginForm = document.getElementById("login-form");

  /* ==========================================================================
     1. Global Dynamic UI Utilities
     ========================================================================== */

  // Navigation Bar Background Scroll Tracking
  if (nav) {
    const onScroll = () => {
      nav.classList.toggle("nav--scrolled", window.scrollY > 8);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  // Smooth-scroll Handler for in-page anchors
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const targetId = link.getAttribute("href");
      if (targetId.length <= 1) return; 
      const target = document.querySelector(targetId);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });

  // From / To Swap Field Engine
  function swapFields() {
    if (!fromInput || !toInput) return;
    const temp = fromInput.value;
    fromInput.value = toInput.value;
    toInput.value = temp;
    fromInput.focus();
  }

  if (swapBtn) {
    swapBtn.addEventListener("click", swapFields);
    swapBtn.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        swapFields();
      }
    });
  }

  /* ==========================================================================
     2. API Feedback & Structural Request Communication Layer
     ========================================================================== */

  /**
   * Appends or updates notice message boxes matching design token layout rules
   */
  function displayFeedback(formElement, messageText, isError = false) {
    if (!formElement) return;
    let messageBox = formElement.querySelector(".auth-feedback-msg, .form-message");

    if (!messageBox) {
      messageBox = document.createElement("div");
      messageBox.className = formElement.classList.contains("search-card") ? "form-message" : "auth-feedback-msg";
      const header = formElement.querySelector(".form-header");
      header ? header.after(messageBox) : formElement.prepend(messageBox);
    }

    messageBox.textContent = messageText;
    
    if (formElement.classList.contains("search-card")) {
      messageBox.classList.toggle("form-message--error", isError);
    } else {
      messageBox.classList.toggle("auth-feedback-msg--error", isError);
      messageBox.classList.toggle("auth-feedback-msg--success", !isError);
    }
  }

  /**
   * Unified asynchronous API network connection wrapper
   */
  async function makeRequest(endpoint, payload, formElement) {
    displayFeedback(formElement, "Connecting to server...", false);

    try {
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Security authorization validation exception.");
      }

      return data;

    } catch (error) {
      const fallbackError = "Connection lost. Please verify your network connection.";
      displayFeedback(formElement, error.message || fallbackError, true);
      return null;
    }
  }

  /* ==========================================================================
     3. Unified Form Feature Handlers
     ========================================================================== */

  // --- Flight Lookup Search Engine ---
  if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
      e.preventDefault();

      const from = fromInput ? fromInput.value.trim() : "";
      const to = toInput ? toInput.value.trim() : "";

      if (!from || !to) {
        displayFeedback(searchForm, "Add both a departure and destination city to search.", true);
        (from ? toInput : fromInput).focus();
        return;
      }

      if (from.toLowerCase() === to.toLowerCase()) {
        displayFeedback(searchForm, "Departure and destination can't be the same place.", true);
        toInput.focus();
        return;
      }

      displayFeedback(
        searchForm,
        `Searching ${from} → ${to} — flight results will appear here once search is connected.`,
        false
      );
    });
  }

  // --- Registration Logic Handler ---
  if (registerForm) {
    registerForm.removeAttribute("action");
    registerForm.removeAttribute("method");

    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(registerForm);
      const password = registerForm.querySelector("[name='Password']");
      const confirmPassword = registerForm.querySelector("[name='confirm_password']");

      if (password && password.value.length < 8) {
        displayFeedback(registerForm, "Security threshold mismatch: Password must be at least 8 characters.", true);
        password.focus();
        return;
      }

      if (password && confirmPassword && password.value !== confirmPassword.value) {
        displayFeedback(registerForm, "Passwords don't match. Please re-enter them.", true);
        confirmPassword.focus();
        return;
      }

      const payload = {
        username: formData.get("username")?.trim(),
        email: formData.get("email")?.trim(),
        password: password?.value
      };

      const result = await makeRequest("/register", payload, registerForm);
      
      if (result) {
        displayFeedback(registerForm, result.message || "Entry authenticated successfully.", false);
        setTimeout(() => {
          window.location.href = "login.html?registered=true";
        }, 1500);
      }
    });
  }

  // --- Login Logic Handler ---
  if (loginForm) {
    loginForm.removeAttribute("action");
    loginForm.removeAttribute("method");

    if (window.location.search.includes("registered=true")) {
      displayFeedback(loginForm, "Profile generated successfully. Sign in with your parameters below.", false);
    }

    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const emailInput = document.getElementById("login-email");
      const passwordInput = document.getElementById("login-password");

      if (!emailInput || !passwordInput) return;

      const payload = {
        email: emailInput.value.trim(),
        password: passwordInput.value
      };

      const result = await makeRequest("/login", payload, loginForm);

      if (result) {
        if (result.data && result.data.token) {
          localStorage.setItem("traidx_jwt", result.data.token);
        }
        
        displayFeedback(loginForm, "Access verification cleared. Routing to secure platform workspace...", false);
        setTimeout(() => {
          window.location.href = "dashboard.html";
        }, 1000);
      }
    });
  }

  // --- Password Recovery Module Flow ---
  let isEmailVerified = false;
  const cardRoot = document.getElementById('auth-card-root');
  const authStage = document.getElementById('auth-stage');
  const forgotForm = document.getElementById('forgot-form');
  const resetForm = document.getElementById('reset-form');
  
  const emailRecoveryInput = document.getElementById('recovery-email');
  const tokenInput = document.getElementById('recovery-token');
  const tokenContainer = document.getElementById('token-field-container');
  const submitBtn = document.getElementById('action-submit-btn');
  const instructions = document.getElementById('recovery-instructions');

  if (cardRoot) {
    setTimeout(() => { cardRoot.classList.remove('initial-zoom'); }, 50);
  }

  if (forgotForm) {
    forgotForm.addEventListener('submit', (e) => {
      e.preventDefault();

      if (!isEmailVerified) {
        emailRecoveryInput.setAttribute('readonly', 'true');
        emailRecoveryInput.style.opacity = '0.7';

        tokenContainer.classList.add('is-revealed');
        tokenInput.setAttribute('required', 'true');
        tokenInput.focus();

        if (instructions) {
          instructions.innerText = "A secure verification key has been routed to your track. Provide the token parameters to clear entry.";
        }
        submitBtn.innerText = "Verify Credentials →";
        
        isEmailVerified = true;
      } else {
        authStage.classList.add('slide-to-reset');
      }
    });
  }

  if (resetForm) {
    resetForm.addEventListener('submit', (e) => {
      e.preventDefault();
      window.location.href = 'login.html';
    });
  }
});