document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registerForm");
  const message = document.getElementById("formMessage");

  if (!form || !message) {
    return;
  }

  const name = document.getElementById("re-name");
  const email = document.getElementById("re-email");
  const password = document.getElementById("re-pass");
  const confirm = document.getElementById("re-pass1");

  function showError(input, text) {
    message.className = "cup-message error";
    message.innerHTML = text;

    input.classList.add("input-error");
    input.focus();
  }

  function clearError(input) {
    input.classList.remove("input-error");

    message.innerHTML = "";
    message.className = "cup-message";
  }

  [name, email, password, confirm].forEach(function (input) {
    input.addEventListener("input", function () {
      clearError(input);
    });
  });

  form.addEventListener("submit", function (e) {
    message.innerHTML = "";
    message.className = "cup-message";

    [name, email, password, confirm].forEach(function (input) {
      input.classList.remove("input-error");
    });

    if (name.value.trim() === "") {
      e.preventDefault();
      showError(name, "Please enter your full name.");
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email.value.trim())) {
      e.preventDefault();
      showError(email, "Please enter a valid email address.");
      return;
    }

    if (password.value === "" || password.value.length < 8) {
      e.preventDefault();
      showError(password, "Password must be at least 8 characters long.");
      return;
    }

    if (password.value !== confirm.value) {
      e.preventDefault();
      showError(confirm, "Passwords do not match.");
      return;
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registerForm");
  const message = document.getElementById("formMessage");

  if (!form || !message) {
    return;
  }

  const name = document.getElementById("re-name");
  const email = document.getElementById("re-email");
  const password = document.getElementById("re-pass");
  const confirm = document.getElementById("re-pass1");

  function showError(input, text) {
    message.className = "cup-message error";
    message.innerHTML = text;

    input.classList.add("input-error");
    input.focus();
  }

  function clearError(input) {
    input.classList.remove("input-error");

    message.innerHTML = "";
    message.className = "cup-message";
  }

  [name, email, password, confirm].forEach(function (input) {
    input.addEventListener("input", function () {
      clearError(input);
    });
  });

  form.addEventListener("submit", function (e) {
    message.innerHTML = "";
    message.className = "cup-message";

    [name, email, password, confirm].forEach(function (input) {
      input.classList.remove("input-error");
    });

    if (name.value.trim() === "") {
      e.preventDefault();
      showError(name, "Please enter your full name.");
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email.value.trim())) {
      e.preventDefault();
      showError(email, "Please enter a valid email address.");
      return;
    }

    if (password.value === "" || password.value.length < 8) {
      e.preventDefault();
      showError(password, "Password must be at least 8 characters long.");
      return;
    }

    if (password.value !== confirm.value) {
      e.preventDefault();
      showError(confirm, "Passwords do not match.");
      return;
    }
  });
});
