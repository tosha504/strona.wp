const DESKTOP_BREAKPOINT = 1200;

const selectors = {
  nav: "#main-nav",
  desktopItem: ".menu-item--mega",
  desktopPanel: ".awMegaMenu",
  desktopTrigger: ".js-mega-trigger",
  overlay: ".overlay-megaMenu",

  fixedMenu: ".fixedMenu",
  fixedMenuBody: ".fixedMenu__body",
  mobileItem: ".menu-item--mega",
  mobileTrigger: ".js-mega-mobile-open",
  mobilePanel: ".awMegaMenuMobile",
  mobileBack: ".js-mega-mobile-back",
  mobileBurgerToggle: ".fixedMenu__toggler",
  mobileOverlay: ".overlay--fixedMenu",
};

const mediaDesktop = window.matchMedia(`(min-width: ${DESKTOP_BREAKPOINT}px)`);

function initMegaMenu() {
  if (window.__TNL_MEGA_MENU_INITIALIZED__) {
    return;
  }

  window.__TNL_MEGA_MENU_INITIALIZED__ = true;

  const nav = document.querySelector(selectors.nav);
  const overlay = document.querySelector(selectors.overlay);
  const desktopItems = document.querySelectorAll(
    `${selectors.nav} ${selectors.desktopItem}`
  );

  const fixedMenu = document.querySelector(selectors.fixedMenu);
  const fixedMenuBody = document.querySelector(selectors.fixedMenuBody);

  if (nav && desktopItems.length) {
    initDesktopMegaMenu(nav, overlay, desktopItems);
  }

  if (fixedMenu && fixedMenuBody) {
    initMobileMegaMenu(fixedMenu, fixedMenuBody);
  }
}

function initDesktopMegaMenu(nav, overlay, desktopItems) {
  let activeItem = null;
  let activePanel = null;
  let activeTrigger = null;
  let closeTimeout = null;

  const clearCloseTimeout = () => {
    if (!closeTimeout) {
      return;
    }

    window.clearTimeout(closeTimeout);
    closeTimeout = null;
  };

  const setExpandedState = (item, isOpen) => {
    const trigger = item.querySelector(selectors.desktopTrigger);
    const panel = item.querySelector(selectors.desktopPanel);

    item.classList.toggle("is-open", isOpen);

    if (trigger) {
      trigger.setAttribute("aria-expanded", isOpen ? "true" : "false");
    }

    if (panel) {
      panel.setAttribute("aria-hidden", isOpen ? "false" : "true");
    }
  };

  const closeAll = () => {
    desktopItems.forEach((item) => setExpandedState(item, false));

    document.documentElement.classList.remove("has-mega-menu-open");

    if (overlay) {
      overlay.classList.remove("is-active");
      overlay.setAttribute("aria-hidden", "true");
    }

    activeItem = null;
    activePanel = null;
    activeTrigger = null;

    clearCloseTimeout();
  };

  const openItem = (item) => {
    if (!mediaDesktop.matches) {
      return;
    }

    if (activeItem === item) {
      clearCloseTimeout();
      return;
    }

    clearCloseTimeout();

    if (activeItem && activeItem !== item) {
      setExpandedState(activeItem, false);
    }

    setExpandedState(item, true);

    activeItem = item;
    activeTrigger = item.querySelector(selectors.desktopTrigger);
    activePanel = item.querySelector(selectors.desktopPanel);

    document.documentElement.classList.add("has-mega-menu-open");

    if (overlay) {
      overlay.classList.add("is-active");
      overlay.setAttribute("aria-hidden", "false");
    }
  };

  const scheduleClose = () => {
    clearCloseTimeout();
    closeTimeout = window.setTimeout(() => {
      closeAll();
    }, 180);
  };

  const isInsideCurrentMegaZone = (node) => {
    if (!node) {
      return false;
    }

    return Boolean(
      (activeItem && activeItem.contains(node)) ||
      (activePanel && activePanel.contains(node))
    );
  };

  desktopItems.forEach((item) => {
    const trigger = item.querySelector(selectors.desktopTrigger);
    const panel = item.querySelector(selectors.desktopPanel);

    const handleEnter = () => {
      openItem(item);
    };

    const handleLeave = (event) => {
      const nextTarget = event.relatedTarget;

      if (
        nextTarget &&
        (item.contains(nextTarget) || (panel && panel.contains(nextTarget)))
      ) {
        return;
      }

      scheduleClose();
    };
    console.log(item);

    item.addEventListener("mouseenter", handleEnter);
    item.addEventListener("mouseleave", handleLeave);
    item.addEventListener("focusin", handleEnter);

    if (trigger) {
      trigger.addEventListener("mouseenter", handleEnter);
      trigger.addEventListener("focusin", handleEnter);
    }

    if (panel) {
      panel.addEventListener("mouseenter", () => {
        clearCloseTimeout();
        openItem(item);
      });

      panel.addEventListener("mouseleave", (event) => {
        const nextTarget = event.relatedTarget;

        if (
          nextTarget &&
          (panel.contains(nextTarget) || item.contains(nextTarget))
        ) {
          return;
        }

        scheduleClose();
      });

      panel.addEventListener("focusin", () => {
        clearCloseTimeout();
        openItem(item);
      });

      panel.addEventListener("focusout", (event) => {
        const nextTarget = event.relatedTarget;

        if (
          nextTarget &&
          (panel.contains(nextTarget) || item.contains(nextTarget))
        ) {
          return;
        }

        scheduleClose();
      });
    }
  });

  nav.addEventListener("mouseenter", () => {
    clearCloseTimeout();
  });

  nav.addEventListener("mouseleave", (event) => {
    const nextTarget = event.relatedTarget;

    if (isInsideCurrentMegaZone(nextTarget)) {
      return;
    }

    scheduleClose();
  });

  if (overlay) {
    overlay.addEventListener("click", () => {
      closeAll();
    });
  }

  document.addEventListener("click", (event) => {
    if (!mediaDesktop.matches) {
      return;
    }

    if (isInsideCurrentMegaZone(event.target)) {
      return;
    }

    if (nav.contains(event.target)) {
      return;
    }

    closeAll();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeAll();
    }
  });

  const handleDesktopBreakpoint = () => {
    if (!mediaDesktop.matches) {
      closeAll();
    }
  };

  if (typeof mediaDesktop.addEventListener === "function") {
    mediaDesktop.addEventListener("change", handleDesktopBreakpoint);
  } else if (typeof mediaDesktop.addListener === "function") {
    mediaDesktop.addListener(handleDesktopBreakpoint);
  }
}

function initMobileMegaMenu(fixedMenu, fixedMenuBody) {
  let activePanel = null;
  let activeItem = null;
  let activeTrigger = null;

  const closeCurrentPanel = ({ restoreFocus = false } = {}) => {
    if (!activePanel || !activeItem) {
      return;
    }

    activePanel.classList.remove("is-open");
    activePanel.setAttribute("aria-hidden", "true");

    activeItem.classList.remove("is-mega-open");

    if (activeTrigger) {
      activeTrigger.setAttribute("aria-expanded", "false");
    }

    fixedMenuBody.classList.remove("has-active-panel");

    const triggerToFocus = activeTrigger;

    activePanel = null;
    activeItem = null;
    activeTrigger = null;

    if (restoreFocus && triggerToFocus) {
      triggerToFocus.focus();
    }
  };

  const openPanel = (trigger) => {
    const item = trigger.closest(selectors.mobileItem);
    const panelId = trigger.getAttribute("data-mega-panel");
    const panel = panelId
      ? fixedMenuBody.querySelector(`#${panelId}`)
      : null;

    if (!item || !panel) {
      return;
    }

    if (activePanel && activePanel !== panel) {
      closeCurrentPanel();
    }

    activePanel = panel;
    activeItem = item;
    activeTrigger = trigger;

    item.classList.add("is-mega-open");
    panel.classList.add("is-open");
    panel.setAttribute("aria-hidden", "false");
    trigger.setAttribute("aria-expanded", "true");
    fixedMenuBody.classList.add("has-active-panel");

    const backButton = panel.querySelector(selectors.mobileBack);

    if (backButton) {
      backButton.focus();
    } else {
      panel.focus();
    }
  };

  fixedMenuBody.addEventListener("click", (event) => {
    const openTrigger = event.target.closest(selectors.mobileTrigger);

    if (openTrigger && fixedMenuBody.contains(openTrigger)) {
      event.preventDefault();
      openPanel(openTrigger);
      return;
    }

    const backButton = event.target.closest(selectors.mobileBack);

    if (backButton && fixedMenuBody.contains(backButton)) {
      event.preventDefault();
      closeCurrentPanel({ restoreFocus: true });
    }
  });

  document
    .querySelectorAll(
      `${selectors.mobileBurgerToggle}, ${selectors.mobileOverlay}`
    )
    .forEach((element) => {
      element.addEventListener("click", () => {
        closeCurrentPanel();
      });
    });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeCurrentPanel({ restoreFocus: true });
    }
  });

  const handleBreakpointChange = () => {
    if (mediaDesktop.matches) {
      closeCurrentPanel();
    }
  };

  if (typeof mediaDesktop.addEventListener === "function") {
    mediaDesktop.addEventListener("change", handleBreakpointChange);
  } else if (typeof mediaDesktop.addListener === "function") {
    mediaDesktop.addListener(handleBreakpointChange);
  }
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initMegaMenu);
} else {
  initMegaMenu();
}