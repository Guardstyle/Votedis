// Get add and delete button element
let addBtn = document.getElementById("addBtn");
let delBtn = document.getElementById("delBtn");

// Initially hide delete button
delBtn.style.display = "none";

// Get choice 3, 4, and 5
let choice = [];
choice[0] = document.getElementById("insertrow3");
choice[1] = document.getElementById("insertrow4");
choice[2] = document.getElementById("insertrow5");

// Hide choice 3, 4, and 5
choice[0].style.display = "none";
choice[1].style.display = "none";
choice[2].style.display = "none";

// Get hidden input element
let pollNum = document.getElementById("pollNum");

// Index to see how many choice have been added
let idx = 0;

addBtn.addEventListener("click", function(){
    choice[idx].style.display = "block";
    idx++;

    // Change hidden input value
    pollNum.value = idx;
    
    // Hide add button if index is equal to 3 (choices numbers to maximum)
    if(idx === 3){
        addBtn.style.display = "none";
    }

    // Display delete button if index is 1
    if(idx === 1){
        delBtn.style.display = "inline-block";
    }
});

delBtn.addEventListener("click", function(){
    idx--;
    choice[idx].style.display = "none";

    // Change hidden input value
    pollNum.value = idx;

    // Hide del button if index is 0
    if(idx === 0){
        delBtn.style.display = "none";
    }

    // Display add button if index is 2
    if(idx === 2){
        addBtn.style.display = "inline-block";
    }
});
