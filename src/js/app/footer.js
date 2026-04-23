// src/js/app/footer.js

import { slideToggle, slideUp } from "./slide.js";

const FOOTER_NAV_SELECTOR = ".footer__navigation";
const FOOTER_TITLE_SELECTOR = ".footer__navigation--title";
const FOOTER_MENU_SELECTOR = ".menu";
const MOBILE_BREAKPOINT = 991;

const getFooterSectionElement = (titleElement) => {
    return titleElement.parentElement?.parentElement ?? titleElement.parentElement;
};

const getMenuSectionElement = (menuElement) => {
    return menuElement.parentElement?.parentElement ?? menuElement.parentElement;
};

const initFooterNav = () => {
    const navBlocks = document.querySelectorAll(FOOTER_NAV_SELECTOR);

    if (!navBlocks.length) {
        return;
    }

    navBlocks.forEach((nav) => {
        if (nav.dataset.footerNavInitialized === "1") {
            return;
        }

        nav.dataset.footerNavInitialized = "1";

        nav.addEventListener("click", (event) => {
            const trigger = event.target.closest(FOOTER_TITLE_SELECTOR);

            if (!trigger || !nav.contains(trigger)) {
                return;
            }

            if (window.innerWidth > MOBILE_BREAKPOINT) {
                return;
            }

            event.preventDefault();

            const currentMenu =
                trigger.parentElement?.querySelector(":scope > .menu") ??
                trigger.parentElement?.querySelector(FOOTER_MENU_SELECTOR);

            if (!currentMenu) {
                return;
            }

            const allMenus = [...nav.querySelectorAll(FOOTER_MENU_SELECTOR)];
            const otherMenus = allMenus.filter((menu) => menu !== currentMenu);
            const willOpen = window.getComputedStyle(currentMenu).display === "none";

            slideToggle(currentMenu, 300);
            getFooterSectionElement(trigger)?.classList.toggle("active", willOpen);
            trigger.setAttribute("aria-expanded", String(willOpen));

            otherMenus.forEach((menu) => {
                slideUp(menu, 300);

                const relatedTitle =
                    menu.parentElement?.querySelector(":scope > .footer__navigation--title") ??
                    menu.parentElement?.querySelector(FOOTER_TITLE_SELECTOR);

                relatedTitle?.setAttribute("aria-expanded", "false");
                getMenuSectionElement(menu)?.classList.remove("active");
            });
        });
    });
};

initFooterNav();