const myForm = document.getElementsByClassName('container')[1].childNodes[1];
myForm.addEventListener('submit', (event) => {
    event.preventDefault(); // prevent form submission

    //Validate for title
    const title = myForm.elements.book_title.value.trim();
    let titleError = '';
    const titleInput = document.getElementById('book_title');

    if (title === '') {
        titleInput.classList.remove('is-invalid');
        titleError = 'error'
        titleInput.classList.add('is-invalid');
    }

    //Validate for release year
    const year = myForm.elements.book_releaseYear.value.trim();
    let yearsError = '';
    const yearsInput = document.getElementById('book_releaseYear');

    if (year === '') {
        yearsInput.classList.remove('is-invalid');
        yearsError = 'error'
        yearsInput.classList.add('is-invalid');
    }

    //Validate for price
    const price = myForm.elements.book_price.value.trim();
    let priceError = '';
    const priceInput = document.getElementById('book_price');

    if (price === '') {
        priceInput.classList.remove('is-invalid');
        priceError = 'error'
        priceInput.classList.add('is-invalid');
    }

    //Validate for cover
    const cover = myForm.elements.book_cover.value.trim();
    let coverError = '';
    const coverInput = document.getElementById('book_cover');

    if (cover === '') {
        coverInput.classList.remove('is-invalid');
        coverError = 'error'
        coverInput.classList.add('is-invalid');
    }

    //Validate for pdf
    const book = myForm.elements.book_book.value.trim();
    const bookInput = document.getElementById('book_book');
    let bookError = '';
    if (book === '') {
        bookInput.classList.remove('is-invalid');
        bookError = 'error'
        bookInput.classList.add('is-invalid');
    } else {

    }

    //Validate for author
    let authors = document.getElementById('book_avtor')
    let oneCheckedAuthor = false;
    for (const checkbox of authors.querySelectorAll('input[type="checkbox"]')) {
        if (checkbox.checked) {
            oneCheckedAuthor = true;
            break;
        }
    }
    if (!oneCheckedAuthor) {
        let elm = authors.querySelector('#error')
        if ((elm != null)) {
            elm.remove()
        }
        authors.appendChild(addElm());
    }

    //Validate for category
    let categories = document.getElementById('book_categories')
    let oneCheckedCategory = false;
    for (const checkbox of categories.querySelectorAll('input[type="checkbox"]')) {
        if (checkbox.checked) {
            oneCheckedCategory = true;
            break;
        }
    }
    if (!oneCheckedCategory) {
        let elm = categories.querySelector('#error')
        if ((elm != null)) {
            elm.remove()
        }
        categories.appendChild(addElm());
    }

    //Submit the form if there are no errors
    if (coverError === '' && bookError === '') {
        myForm.submit();
    }

    function addElm() {
        let div = document.createElement('div');
        div.id = 'error'
        div.classList.add('invalid-feedback', 'd-block');
        div.textContent = 'This value should not be blank.'
        return div;
    }
});