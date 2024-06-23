let url = window.location.href.split('?')[1].split('=')[1];
if (url.length > 0) {
    console.log(document.querySelector('.input-search'));
    document.querySelector('.input-search').focus()
}