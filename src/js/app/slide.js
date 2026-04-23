// src/js/app/slide.js

const DEFAULT_DURATION = 300;

const isElement = (value) => value instanceof HTMLElement;

const isHidden = (element) => window.getComputedStyle(element).display === "none";

const isAnimating = (element) => element.dataset.slideAnimating === "1";

const setAnimating = (element, state) => {
    if (state) {
        element.dataset.slideAnimating = "1";
        return;
    }

    delete element.dataset.slideAnimating;
};

const cleanupSlideStyles = (element) => {
    element.style.removeProperty("height");
    element.style.removeProperty("overflow");
    element.style.removeProperty("transition-property");
    element.style.removeProperty("transition-duration");
    element.style.removeProperty("box-sizing");
    element.style.removeProperty("padding-top");
    element.style.removeProperty("padding-bottom");
    element.style.removeProperty("margin-top");
    element.style.removeProperty("margin-bottom");
};

export function slideUp(element, duration = DEFAULT_DURATION, callback) {
    if (!isElement(element) || isAnimating(element) || isHidden(element)) {
        return;
    }

    setAnimating(element, true);

    element.style.height = `${element.offsetHeight}px`;
    element.style.boxSizing = "border-box";
    element.style.overflow = "hidden";

    // force reflow
    element.offsetHeight;

    element.style.transitionProperty = "height, margin, padding";
    element.style.transitionDuration = `${duration}ms`;

    element.style.height = "0px";
    element.style.paddingTop = "0px";
    element.style.paddingBottom = "0px";
    element.style.marginTop = "0px";
    element.style.marginBottom = "0px";

    window.setTimeout(() => {
        element.style.display = "none";
        cleanupSlideStyles(element);
        setAnimating(element, false);

        if (typeof callback === "function") {
            callback();
        }
    }, duration);
}

export function slideDown(element, duration = DEFAULT_DURATION, callback) {
    if (!isElement(element) || isAnimating(element) || !isHidden(element)) {
        return;
    }

    setAnimating(element, true);

    element.style.removeProperty("display");

    let display = window.getComputedStyle(element).display;

    if (display === "none") {
        display = "block";
    }

    element.style.display = display;

    const height = element.offsetHeight;

    element.style.overflow = "hidden";
    element.style.boxSizing = "border-box";
    element.style.height = "0px";
    element.style.paddingTop = "0px";
    element.style.paddingBottom = "0px";
    element.style.marginTop = "0px";
    element.style.marginBottom = "0px";

    // force reflow
    element.offsetHeight;

    element.style.transitionProperty = "height, margin, padding";
    element.style.transitionDuration = `${duration}ms`;
    element.style.height = `${height}px`;

    element.style.removeProperty("padding-top");
    element.style.removeProperty("padding-bottom");
    element.style.removeProperty("margin-top");
    element.style.removeProperty("margin-bottom");

    window.setTimeout(() => {
        cleanupSlideStyles(element);
        setAnimating(element, false);

        if (typeof callback === "function") {
            callback();
        }
    }, duration);
}

export function slideToggle(element, duration = DEFAULT_DURATION, callback) {
    if (!isElement(element) || isAnimating(element)) {
        return;
    }

    if (isHidden(element)) {
        slideDown(element, duration, callback);
        return;
    }

    slideUp(element, duration, callback);
}