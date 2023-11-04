function place_order(action){
	if (selectedSeatIds.length > 0){
		document.getElementById("seats").value = selectedSeatIds;
		document.getElementById("seatnum").value = selectedSeatIds.length;
		document.getElementById("bookingselection").action = action;
		document.getElementById("bookingselection").submit();
	}
	else{
		alert("You have not select your seats!");
	}
	
}

const selectedSeatIds = [];
container.addEventListener('click',e=>{

    if(e.target.classList.contains('seat')&&
    !e.target.classList.contains('occupied')){
        const seatId = e.target.id;
		const index = selectedSeatIds.indexOf(seatId);
		if (index === -1) {
			// If not already in the array, add it
			selectedSeatIds.push(seatId);
		}
    }
})
