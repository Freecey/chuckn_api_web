const xhttp = new XMLHttpRequest();
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
// FOR RATING
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

// FOR REPORT

function onClickBtnModal(event){
    let report_url = location.protocol + '//' + location.host + '/' + event.target.id.split('_')[0] + '/' + event.target.id.split('_')[1];
    let divModal = document.querySelector('#modal_body_'+event.target.id.split('_')[1]);
    divModal.innerHTML = '<div class="spinner-border text-info" role="status">\n' +
        '  <span class="visually-hidden">Loading...</span>\n' +
        '</div>';
    let xmlhttp = ajaxReq();
    xmlhttp.open("GET", report_url, true);
    xmlhttp.send(null);
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            try {
                let response = xmlhttp.responseText;
                // console.log(response)
                divModal.innerHTML = response;
            } catch (error) {
                throw Error;
            }
        }
    }
    function onClickBtnSubmitModal(event){
        event.preventDefault();
        let report_url_post = location.protocol + '//' + location.host + '/report/post/' + event.target.id.split('_')[1];
        let params = 'reason='+document.querySelector('#report_reason').value+'&token='+document.querySelector('#report__token'+event.target.id.split('_')[1]).value;
        xmlhttp.open("POST", report_url_post, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(params);
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                try {
                    let response = xmlhttp.responseText;
                    divModal.innerHTML = response;
                    btn_report_submit.remove();
                } catch (error) {
                    throw Error;
                    btn_report_submit.remove();
                }
            }
        }
    }


    let btn_report_submit = document.querySelector('#js-report-submit_' + event.target.id.split('_')[1]);
    if (btn_report_submit != null) {
        btn_report_submit.addEventListener('click', onClickBtnSubmitModal)
    }
}

document.querySelectorAll('button.js-btn-report').forEach(function(link){
    link.addEventListener('click', onClickBtnModal)
})