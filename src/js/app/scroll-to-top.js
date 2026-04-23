const goTop = () => {

    const btnTop = document.querySelector('#goTop');

    if (!btnTop) return;

    const scrollPosition = () => {
        window.addEventListener('scroll', () => {

            if (window.scrollY >= 300) {
                btnTop.classList.add('is-visible');
            } else {
                btnTop.classList.remove('is-visible');
            }

        });
    }

    const goToTop = () => {
        btnTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scroll({ top: 0, left: 0, behavior: 'smooth' });
        });
    }
    scrollPosition();
    goToTop();
}

goTop();