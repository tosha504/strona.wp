const fixedMenu = () => {
    const nav = document.querySelector('#wrapper-navbar');

    if (!nav) {
        return;
    }

    const mobileBreakpoint = window.matchMedia('(max-width: 991.98px)');
    const scrollOffset = 10;

    let lastScrollY = window.scrollY;
    let ticking = false;

    const updateMenuState = () => {
        const currentScrollY = window.scrollY;
        const isMobile = mobileBreakpoint.matches;

        if (currentScrollY > 0) {
            nav.classList.add('scrollDown');
        } else {
            nav.classList.remove('scrollDown');
        }

        if (!isMobile) {
            nav.classList.remove('is-nav-hidden', 'is-nav-visible');
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        if (currentScrollY <= 0) {
            nav.classList.remove('is-nav-hidden');
            nav.classList.add('is-nav-visible');
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        const scrollDelta = currentScrollY - lastScrollY;

        if (Math.abs(scrollDelta) < scrollOffset) {
            ticking = false;
            return;
        }

        if (scrollDelta > 0) {
            nav.classList.add('is-nav-hidden');
            nav.classList.remove('is-nav-visible');
        } else {
            nav.classList.remove('is-nav-hidden');
            nav.classList.add('is-nav-visible');
        }

        lastScrollY = currentScrollY;
        ticking = false;
    };

    const onScroll = () => {
        if (!ticking) {
            window.requestAnimationFrame(updateMenuState);
            ticking = true;
        }
    };

    const onBreakpointChange = () => {
        lastScrollY = window.scrollY;
        updateMenuState();
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    mobileBreakpoint.addEventListener('change', onBreakpointChange);

    updateMenuState();
};

fixedMenu();