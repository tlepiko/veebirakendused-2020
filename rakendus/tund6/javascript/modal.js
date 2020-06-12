let modal;
let modalImg;
let captionText;
let photoId;
let photoDir = "../../uploadNormalPhoto/";

window.onload = function(){
	modal = document.getElementById("modalArea");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("modalCaption");
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	for (let i = 0; i < allThumbs.length; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.getElementById("modalClose").addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
	document.getElementById("storeRating").addEventListener("click", storeRating);
}

function openModal(e){
	document.getElementById("avgRating").innerHTML = "";
	for(let i = 1; i < 6; i ++){
		document.getElementById("rate" + i).checked = false;
	}
	modalImg.src = photoDir + e.target.dataset.fn;
	photoId = e.target.dataset.id;
	modalImg.alt = e.target.alt;
	captionText.innerHTML = e.target.alt;
	modal.style.display = "block";
}

function closeModal(){
	modal.style.display = "none";
}

function storeRating(){
	let rating = 0;
	for (let i = 1; i < 6; i ++){
		if(document.getElementById("rate" + i).checked){
			rating = document.getElementById("rate" + i).value;
		}
	}
	if(rating > 0){
		//AJAX
		let webRequest = new XMLHttpRequest();
		webRequest.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				//mis teha javascriptis, kui AJAX on edukas
				document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
			}
		};
		//    storePhotoRating.php?rating=5&photoId=25
		webRequest.open("GET", "storePhotoRating.php?rating=" + rating + "&photoid=" + photoId, true);
		webRequest.send();
		
		//AJAX lõppeb
	}
}