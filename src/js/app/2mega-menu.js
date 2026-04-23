const overlayMegaMenu = () => {

    const mouseTargets = document.querySelectorAll('.is-megamenu');
    const overlayMenu = document.querySelector('.overlay-megaMenu');
    
    const showOverlay = () => {
        overlayMenu.classList.add('active');
    }
    
    const hideOverlay = () => {
        overlayMenu.classList.remove('active');
    }
    
    for(let item of mouseTargets){
        item.onmouseover = showOverlay;
        item.onmouseleave = hideOverlay;
    }
    
}

overlayMegaMenu();