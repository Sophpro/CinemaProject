const container=document.querySelector(".container")

const movie=document.querySelector('#movie')
const count=document.querySelector(".count")
const total=document.querySelector(".total")
// console.log(seats);


//获取总价和数量
function updateSelectedCount(){
    const selected = document.querySelectorAll('.container .selected')
    // console.log(selected.length*+movie.value);
    count.innerHTML=selected.length
    total.innerHTML=(selected.length*+movie.value).toFixed(2)
}

//选电影座位
container.addEventListener('click',e=>{

    // console.dir();
    if(e.target.classList.contains('seat')&&
    !e.target.classList.contains('occupied')){
        e.target.classList.toggle('selected')
        // console.log(e.target.classList);


    }
    updateSelectedCount()
})

//选电影的场次
movie.addEventListener('change',e=>{
    updateSelectedCount()
})

updateSelectedCount()
