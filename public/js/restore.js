/** POPUP */
const myPopup = document.querySelector('.popup'),
	closeBtn = document.querySelector('.close-btn'),
	openPopup = document.querySelector('.openPopup')
;
openPopup.addEventListener('click',()=>{
	myPopup.classList.add('active');
});
closeBtn.addEventListener('click',()=>{
	myPopup.classList.remove('active');
});
const 
    qQ = document.querySelector('.qQ'),
    viderPanier = document.querySelector('.vider '),
    checkout = document.querySelector('.checkout '),
    price = document.querySelector('.price '),
    total = document.querySelectorAll('.total'),
    totals = document.querySelector('.totals')
    moveQ = document.querySelectorAll('.moveQ'),
    addQ = document.querySelectorAll('.addQ')
    deleteLine = document.querySelectorAll('.deleteLine')
;
function changeTotalPrice(price,qQ) {
    return qQ*price;
}
function setTotals(t) {  
    let tt = 0;
    t.forEach(t => {
        tt += parseInt(t.value);
    });  
    return tt;
}
//<!-- No route found for &quot;POST https://localhost:8000/api/panier/delete/&quot; (404 Not Found) -->
//<!-- No route found for &quot;POST https://localhost:8000/api/panier/+delete+%22/%22&quot; (404 Not Found) -->
const 
    BASE_URI_ADDQ = "http://localhost:8000/api/panier/add/",
    BASE_URI_MOVEQ = "http://localhost:8000/api/panier/remove/",
    BASE_URI_VIDER = "http://localhost:8000/api/panier/deleteAll/",
    BASE_URI_DELETE = "http://localhost:8000/api/panier/delete",
    BASE_URI_GETS = "http://localhost:8000/api/panier/get/",
    BASE_URI_CHECK0UT = "http://localhost:8000/api/panier/checkout/"
;
addQ.forEach(current => {
    current.addEventListener('click',(event)=>{
        let slug = event.target.getAttribute('data-add-slug');
        let element = document.getElementById(slug);
        let inputQ = element.previousElementSibling;
        let divQ = inputQ.parentElement;
        let td = divQ.parentElement;
        let prevTd = td.previousElementSibling;
        let prevTdInput = prevTd.children.item(0);
        let nextTd = td.nextElementSibling;
        let nextTdInput = nextTd.children.item(0);
        inputQ.value++;
        //Modification du total
        nextTdInput.value = prevTdInput.value*inputQ.value;
        totals.value = setTotals(total);

        /*
        try {
            addQuantity({slug}).then((data) => {
                console.log(data.json());
            });
        } catch (error) {
            console.log(`Produit_Session: ${error.message}`);
        }*/
    });
});
moveQ.forEach(current => {
	current.addEventListener('click',async(event)=>{   
        let slug = event.target.getAttribute('data-move-slug');
        let element = document.getElementsByClassName(slug)[0];
        let inputQ = element.nextElementSibling;
        let divQ = inputQ.parentElement;
        let td = divQ.parentElement;
        let prevTd = td.previousElementSibling;
        let prevTdInput = prevTd.children.item(0);
        let nextTd = td.nextElementSibling;
        let nextTdInput = nextTd.children.item(0);
        if(inputQ.value > 1 && inputQ.value !== 1)  {
            inputQ.value--;
        }else {
            inputQ.value = inputQ.value;
        }
        //Modification du total
        nextTdInput.value = prevTdInput.value*inputQ.value;
        totals.value = setTotals(total);
        //Appel du service de gestion du panier
        /*
        try {
	    		removeQuantity(slug).then((data) => {
	    			console.log(data.json());
	    	});
        } catch (error) {
            console.log(`Produit_Session: ${error.message}`);
        }*/
});
});
deleteLine.forEach(current => {
    current.addEventListener('click',(event)=>{
        let slug = event.target.getAttribute('data-slug');
        try {
            deleteProduit(slug).then(response => {
                //console.log(response.json());
            });
        } catch (error) {
            console.log(`Produit_Session: ${error.message}`);
        }
    });
});
viderPanier.addEventListener('click',async()=>{ });
checkout.addEventListener('click',async()=>{ });
async function addQuantity(slugData) {
    const options = {
        method: 'POST',
        body: JSON.stringify(slugData) ,
    };
    return await fetch(BASE_URI_ADDQ,options);
}
async function removeQuantity(slugData) {
    const options = {
        method: 'POST',
        mode: "cors",
        caches: "no-cache",
        credentials: "same-origin",
        headers: {
						'Access-Control-Allow-Origin': '*',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Headers': 'Origin, Content-Type, Accept',
            'Access-Control-Allow-Methods': 'GET, POST, PATH, PUT, DELETE, OPTIONS'
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
        body: JSON.stringify(slugData) ,
    };
    return await fetch(`${BASE_URI_MOVEQ}`,options);
}
async function deleteProduit(slugData) {
    var options = {
        method: 'POST',
    };
    //var req = new Request(BASE_URI_DELETE+slugData,options);
    /*
    function deleteData(item, url) {
        return fetch(url + '/' + item, {
          method: 'delete'
        })
        .then(response => response.json());
      }*/
    return await fetch(BASE_URI_DELETE+'/'+slugData,{
        method: 'delete'
    });
}
async function getProduits() {
    var options = {
        method: 'GET',
        mode: "no-cors",
        caches: "no-cache",
        credentials: "same-origin",
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Headers': 'Origin, Content-Type, Accept'
        },
        redirect: "follow",
        referrerPolicy: "same-origin",
    };
    var req = new Request(`${BASE_URI_GETS}`,options);
    return await fetch(req);
}
async function viderLePanier() {
    const options = {
        method: 'POST',
        mode: "cors",
        caches: "no-cache",
        credentials: "same-origin",
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Headers': 'Origin, Content-Type, Accept'
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
        body: "" ,
    };
    return await fetch(`${BASE_URI_VIDER}`,options);
}
async function checkoutPanier(req, res, next) {}
/*
moveQ.addEventListener('click',async()=>{    
    if(qQ.value > 1 && qQ.value !== 1)  {
        qQ.value--;
    }else {
        qQ.value = qQ.value;
    }
    //Modification du total
    total.value = changeTotalPrice(price.value,qQ.value);
    setTotals(total.value);
    //Appel du service de gestion du panier
    try {
        
    } catch (error) {
        console.log(`Produit_Session: ${error.message}`);
    }
    console.log('move'+' qQ-- = '+qQ.value);
});
addQ.addEventListener('click',()=>{
    qQ.value++;
    //Modification du total
    total.value = changeTotalPrice(price.value,qQ.value);
    setTotals(total.value);
    try {
        removeQuantity("delete",{slug:"product"}).then((data) => {
            console.log(data.json());
        });
    } catch (error) {
        console.log(`Produit_Session: ${error.message}`);
    }
});

*
document.querySelectorAll('a').forEach(current => {
    current.addEventListener('click', async (element)=>{
        //id du lien clicker
        let id = element.target.id;
        let slug = element.target.getAttribute('data-slug');
    });
});
async function logsd(url = "", data = {}) {
    const options = {
        method: 'POST',//GET,PUT,DELETE,
        mode: "cors",
        caches: "no-cache",
        credentials: "same-origin",
        headers: {
            'Content-Type': 'application/json',//'application/x-www-form-urlencoded,
            
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
        body: JSON.stringify(data),
    };
    const res = await fetch(BASE_URI,options);
    return res.json();
}
logsd("https://example.com/product",{name:"product",price:"100",quantity:"10"}).then((data) => {
    console.log(data.json());
});*/
