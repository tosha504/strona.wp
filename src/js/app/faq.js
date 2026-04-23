// src/js/app/faq.js

import { slideToggle } from "./slide.js";

const FAQ_CONTAINER_SELECTOR = ".blockFaq__faqs";
const FAQ_ITEM_SELECTOR = ".blockFaq__faqs__item";
const FAQ_TRIGGER_SELECTOR = ".blockFaq__faqs__item--que";
const FAQ_ANSWER_SELECTOR = ".blockFaq__faqs__item--answ";

const initFaq = () => {
    const containers = document.querySelectorAll(FAQ_CONTAINER_SELECTOR);

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {
        if (container.dataset.faqInitialized === "1") {
            return;
        }

        container.dataset.faqInitialized = "1";

        container.addEventListener("click", (event) => {
            const trigger = event.target.closest(FAQ_TRIGGER_SELECTOR);

            if (!trigger || !container.contains(trigger)) {
                return;
            }

            event.preventDefault();

            const item = trigger.closest(FAQ_ITEM_SELECTOR);
            const answer = item?.querySelector(FAQ_ANSWER_SELECTOR);

            if (!item || !answer) {
                return;
            }

            const willOpen = window.getComputedStyle(answer).display === "none";

            trigger.classList.toggle("active", willOpen);
            trigger.setAttribute("aria-expanded", String(willOpen));

            slideToggle(answer, 300);
        });
    });
};

initFaq();