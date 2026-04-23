import Swiper from "swiper";
import { Pagination, A11y } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
const SELECTOR = ".js-services-carousel";

function initServicesCarousel(scope = document) {
  const sliders = scope.querySelectorAll(SELECTOR);

  if (!sliders.length) {
    return;
  }

  sliders.forEach((slider) => {
    if (slider.dataset.carouselInitialized === "true") {
      return;
    }

    slider.dataset.carouselInitialized = "true";

    const paginationEl = slider.querySelector(".servicesCarouselBlock__pagination");

    new Swiper(slider, {
      modules: [Pagination, A11y],
      slidesPerView: 1,
      spaceBetween: 20,
      speed: 600,
      watchOverflow: true,
      observer: true,
      observeParents: true,
      pagination: paginationEl
        ? {
          el: paginationEl,
          clickable: true,
        }
        : undefined,
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        992: {
          slidesPerView: 3,
          spaceBetween: 24,
        },
        1500: {
          slidesPerView: 4,
          spaceBetween: 28,
        },
      },
    });
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    initServicesCarousel();
  });
} else {
  initServicesCarousel();
}

if (window.acf) {
  window.acf.addAction("render_block_preview/type=services-carousel", ($el) => {
    initServicesCarousel($el[0] || $el);
  });
}