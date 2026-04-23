export const stickySidebar = () => {
    if (window.innerWidth < 991) return; 

    let sidebar = document.querySelector('.singlePost__sidebar');
    let heightWindow = window.innerHeight;

    if(sidebar){
        let heightElemnt = sidebar.offsetHeight + 130;
        if(heightWindow > heightElemnt){
            sidebar.classList.add('is-sticky');
        }
    }
}