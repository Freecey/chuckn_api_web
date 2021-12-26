function ajaxReq() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert("Browser does not support XMLHTTP.");
        return false;
    }
}

function onClickStar(event){
    if (event.target.classList.contains('fa-star'))
    {
        event.preventDefault();

        const url = event.target.parentElement.href;
        const spanCount = this.querySelector('span.js-count-rating');
        const itag = this.querySelectorAll('i.fa-star');
        const spanMsg = this.querySelector('span.rating-msg');

        let xmlhttp = ajaxReq();
        xmlhttp.open("POST", url, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(null);

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                try {
                    let response = JSON.parse(xmlhttp.responseText);

                    if (response.status === 'add') {
                        spanCount.textContent = '('+response.nmbOfRate+')';
                        spanMsg.textContent = response.message;
                        for ( i = 1 ; i <= 5 ; i++ ) {
                            if ( i <= response.ratingScore ) {
                                itag[i-1].style.color  = 'goldenrod';
                            } else {
                                itag[i-1].style.color  = 'white';
                            }
                        }
                        spanMsg.textContent = response.message;
                        spanMsg.style.color = 'aquamarine'
                        setTimeout(function(){ spanMsg.textContent = '';spanMsg.style.color = ''; }, 5000);
                    } else {
                        spanMsg.textContent = response.message;
                        spanMsg.style.color = 'coral'
                        setTimeout(function(){ spanMsg.textContent = '';spanMsg.style.color = ''; }, 5000);
                    }

                } catch (error) {
                    throw Error;
                }
            }
        }
    }
}

document.querySelectorAll('div.js-rating').forEach(function(link){
    link.addEventListener('click', onClickStar)
})