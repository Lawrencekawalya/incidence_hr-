document.addEventListener("DOMContentLoaded", () => {
  const switcher = document.getElementById("themeSwitcher");

  console.log(localStorage.getItem("theme"));

  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)");

  function applyTheme(isDark, persist = true) {
    let body = document.body;
    let navBar = document.getElementById("navBar");
    let sideBar = document.getElementById("sideBar");

    if (persist) {
      localStorage.setItem("theme", isDark ? "dark" : "light");
    }

    body.classList.add(isDark ? "dark-mode" : "light-mode");
    body.classList.remove(!isDark ? "dark-mode" : "light-mode");

    navBar.classList.add(isDark ? "navbar-dark" : "navbar-light");
    navBar.classList.remove(!isDark ? "navbar-dark" : "navbar-light");

    sideBar.classList.add(
      isDark ? "sidebar-dark-primary" : "sidebar-light-primary"
    );
    sideBar.classList.remove(
      !isDark ? "sidebar-dark-primary" : "sidebar-light-primary"
    );

    if (switcher) {
      switcher.checked = isDark;
    }
  }

  function getInitialTheme() {
    const saved = localStorage.getItem("theme");
    if (saved) {
      return saved === "dark";
    }
    return prefersDark.matches; // fallback to system
  }

  // Apply initial theme
  applyTheme(getInitialTheme(), true);

  // User toggles the switch
  switcher?.addEventListener("change", (e) => {
    applyTheme(e.target.checked, true);
  });

  // If system theme changes, adopt it only if user hasn’t chosen manually
  // prefersDark.addEventListener("change", (e) => {
  //   if (!localStorage.getItem("theme")) {
  //     applyTheme(e.matches, false);
  //   }
  // });
});
