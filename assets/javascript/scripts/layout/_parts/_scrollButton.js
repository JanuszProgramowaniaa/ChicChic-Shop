window.addEventListener('scroll', e => {
    let pageOffset = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;
    const scrollButton = document.querySelector('.scrollButton');
    pageOffset <= 300 ? scrollButton.classList.add('d-none') : scrollButton.classList.remove('d-none')
})
