// src/js/app/stepList.js

import { slideToggle } from "./slide.js";

const STEP_LIST_SELECTOR = ".dropSteps__list";
const STEP_ITEM_SELECTOR = ".dropSteps__list__item";
const STEP_TRIGGER_SELECTOR = ".dropSteps__list__item--title";
const STEP_TEXT_SELECTOR = ".dropSteps__list__item--text";

const initStepList = () => {
    const containers = document.querySelectorAll(STEP_LIST_SELECTOR);

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {
        if (container.dataset.stepListInitialized === "1") {
            return;
        }

        container.dataset.stepListInitialized = "1";

        container.addEventListener("click", (event) => {
            const trigger = event.target.closest(STEP_TRIGGER_SELECTOR);

            if (!trigger || !container.contains(trigger)) {
                return;
            }

            event.preventDefault();

            const item = trigger.closest(STEP_ITEM_SELECTOR);
            const text = item?.querySelector(STEP_TEXT_SELECTOR);

            if (!item || !text) {
                return;
            }

            const willOpen = window.getComputedStyle(text).display === "none";

            item.classList.toggle("active", willOpen);
            trigger.setAttribute("aria-expanded", String(willOpen));

            slideToggle(text, 300);
        });
    });
};

initStepList();