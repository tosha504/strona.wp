// src/js/app/slide-menu.js

import { slideToggle } from "./slide.js";

const OVERLAY_SELECTOR = ".overlay--fixedMenu";
const MENU_SELECTOR = ".fixedMenu";
const TOGGLER_SELECTOR = ".fixedMenu__toggler";
const SUBMENU_ITEM_SELECTOR = ".submenu--toggle";
const SUBMENU_LINK_SELECTOR = ".submenu--toggle > a";

const setMenuState = (isOpen, menu, overlay) => {
    menu?.classList.toggle("active", isOpen);
    overlay?.classList.toggle("active", isOpen);
    document.documentElement.classList.toggle("fixed-menu-open", isOpen);
};

const initSlideMenu = () => {
    const menu = document.querySelector(MENU_SELECTOR);
    const overlay = document.querySelector(OVERLAY_SELECTOR);
    const togglers = document.querySelectorAll(`${OVERLAY_SELECTOR}, ${TOGGLER_SELECTOR}`);

    if (!menu || !overlay || !togglers.length) {
        return;
    }

    if (menu.dataset.slideMenuInitialized === "1") {
        return;
    }

    menu.dataset.slideMenuInitialized = "1";

    togglers.forEach((trigger) => {
        trigger.addEventListener("click", (event) => {
            event.preventDefault();

            const isOpen = !menu.classList.contains("active");
            setMenuState(isOpen, menu, overlay);
        });
    });

    menu.addEventListener("click", (event) => {
        const link = event.target.closest(SUBMENU_LINK_SELECTOR);

        if (!link || !menu.contains(link)) {
            return;
        }

        const item = link.closest(SUBMENU_ITEM_SELECTOR);
        const submenu =
            item?.querySelector(":scope > .sub-menu") ??
            item?.querySelector(".sub-menu");

        // Jeżeli nie ma submenu, zostawiamy normalną nawigację.
        if (!submenu) {
            return;
        }

        event.preventDefault();

        const willOpen = window.getComputedStyle(submenu).display === "none";

        link.classList.toggle("active", willOpen);
        link.setAttribute("aria-expanded", String(willOpen));

        slideToggle(submenu, 300);
    });

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape") {
            return;
        }

        setMenuState(false, menu, overlay);
    });
};

initSlideMenu();