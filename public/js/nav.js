document.querySelector('.dropdown').addEventListener('click',()=>{
    const elt = document.querySelector('.dropdownContent');
    if(elt.style.display == 'block'){
        elt.style.display = 'none';
    }else{
        elt.style.display = 'block';
    }

})

