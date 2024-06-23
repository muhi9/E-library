//------------------Total Price----------------------//
let allPrice = document.querySelectorAll('#price')
let total = 0
for(let p of allPrice){
    total += +p.textContent.split("лв")[0]
}

document.getElementById('totalPrice').textContent = total + " лв"

//----------------------delete cat----------------------//
let deleteBtn = document.querySelectorAll("a.text-muted")
for (elm of deleteBtn){
    elm.addEventListener('click', close)
}
function close(e){
     e.target.parentElement.parentElement.parentElement.remove();
     document.querySelectorAll(".my-4")[1].remove()
}