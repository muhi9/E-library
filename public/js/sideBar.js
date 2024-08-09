document.addEventListener("DOMContentLoaded", function (event) {

    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // show navbar
                nav.classList.toggle('show')
                // change icon
                toggle.classList.toggle('bx-x')
                // add padding to body
                bodypd.classList.toggle('body-pd')
                // add padding to header
                headerpd.classList.toggle('body-pd')
            })
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')
    let routeData = document.getElementById('string-example-container').getAttribute('data-example-string')
    for (const elm of linkColor) {
        elm.classList.remove('active')
        if (elm.getAttribute("class").split(' ')[1] == routeData.split("_")[0]) {
            elm.classList.add('active')
        }
    }
    // function colorLink() {
    //     if (linkColor) {
    //         linkColor.forEach(l => l.classList.remove('active'))
    //         console.log(l.getAttribute("class").split(' ')[1]);
    //         if (l.getAttribute("class").split(' ')[1] == routeData) {
    //             this.classList.add('active')
    //         }
    //     }
    // }
    // linkColor.forEach(l => l.addEventListener('click', colorLink))

});