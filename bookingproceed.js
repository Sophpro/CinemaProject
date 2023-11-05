function place_order(action,codes){
	var promotion = document.getElementById("movie-page-promotion").value;
	//如果填写了promotion code但是（数据库当前没有优惠码 或者 优惠码中不包含当前填的这个）
	if (promotion !== "" && ((codes === "") || (codes.indexOf(promotion) === -1)))
	{
		alert(promotion + " is not a valid promotion code! Please try again!");
	}
	else{
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

	
}

const selectedSeatIds = [];
container.addEventListener('click',e=>{

    if(e.target.classList.contains('seat')&&
    !e.target.classList.contains('occupied')&&
	e.target.classList.contains('selected')){
        const seatId = e.target.id;
		const index = selectedSeatIds.indexOf(seatId);
		if (index === -1) {
			// If not already in the array, add it
			selectedSeatIds.push(seatId);
		}
    }
	else if(e.target.classList.contains('seat')&&
    !e.target.classList.contains('occupied')&&
	!e.target.classList.contains('selected')){
	 	const seatId = e.target.id;
	 	const index = selectedSeatIds.indexOf(seatId);
		if (index != -1) {
			// If already in the array, remove it
	 		selectedSeatIds.splice(index, 1);
		}
	}
})
