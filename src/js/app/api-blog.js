const changeCats = () =>{
    const navCats = document.querySelectorAll('.switchCategory__list--item--link');
    const result = document.querySelector('.site-main__posts');
    
    for (let item of navCats) {
        
        item.addEventListener('click', function(e){
            e.preventDefault();
            if(item.classList.contains('active')) return;
            
            let data = item.dataset.id;
            if(data === null || data === undefined) return;
            apiChangeCats(tnl.ajaxurl, 'api_blog_switch_category', '&data-id=' + data, result);
            
            for (let item of navCats) {
                item.classList.remove('active');
            }

            item.classList.add('active');
            
        });
        
    }
}

const apiChangeCats = (url, action, data, result) => {
    let request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            blogResult.innerHTML = request.responseText;
        } else {
            alert('error');
        }
    };
    request.onloadstart = function(){
        result.querySelector('.blogLoader').classList.add('active');
        result.querySelector('#blogResult').classList.add('loading');

    }   
    request.onloadend = function(){
        result.querySelector('#blogResult').classList.remove('loading');
        result.querySelector('.blogLoader').classList.remove('active');
    }
    request.onerror = function() {
        alert('error-conect');
    };
    request.send('action=' + action + data);
}

if(document.body.classList.contains('blog')){
    changeCats();
}