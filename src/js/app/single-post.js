import { progresbar } from './single-post/progresbar.js';
import { stickySidebar } from './single-post/stickySidebar.js';

if(document.body.classList.contains('single-post')){
    document.addEventListener('DOMContentLoaded', () => {
        progresbar();
        stickySidebar();
    });
}