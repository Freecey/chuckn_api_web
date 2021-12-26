import { axios } from 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js';

function onClickStar(event){
    if (event.target.classList.contains('fa-star'))
    {
        console.log(event.target)
        event.preventDefault();

        const url = event.target.parentElement.href;
        const spanCount = this.querySelector('span.js-count-rating');
        const itag = this.querySelectorAll('i.fa-star');
        const spanMsg = this.querySelector('span.rating-msg');

        axios.post(url).then(function(response){
            if (response.data.status === 'add') {
                spanCount.textContent = '('+response.data.nmbOfRate+')';
                spanMsg.textContent = response.data.message;
                for ( i = 1 ; i <= 5 ; i++ ) {
                    if ( i <= response.data.ratingScore ) {
                        itag[i-1].style.color  = 'goldenrod';
                    } else {
                        itag[i-1].style.color  = 'white';
                    }
                }
                spanMsg.textContent = response.data.message;
                spanMsg.style.color = 'aquamarine'
                setTimeout(function(){ spanMsg.textContent = '';spanMsg.style.color = ''; }, 5000);
            } else {
                spanMsg.textContent = response.data.message;
                spanMsg.style.color = 'coral'
                setTimeout(function(){ spanMsg.textContent = '';spanMsg.style.color = ''; }, 5000);
            }
        });
    }
}

document.querySelectorAll('div.js-rating').forEach(function(link){
    link.addEventListener('click', onClickStar)
})