/* AJAX for Choice on poll section */

// Loop over all polls
for(let poll = 0; document.getElementById("voting" + String(poll)); poll++){
    // Get secret div element
    let sec = document.getElementById("secret" + String(poll));
    
    // Get pollId
    let pollId = sec.innerHTML;

    // Get poll box html element
    let pollBox = document.getElementById("voting" + String(poll));

    // Declare choice array
    let choice = [];
    let counter = [];

    // Get element of each poll choices and counter
    for(let i = 0; ; i++){
        let currId = "poll" + String(poll) + "choice" + String(i);
        let currIdLabel = "poll" + String(poll) + "count" + String(i);
        if(document.getElementById(currId) == null) break;
        choice[i] = document.getElementById(currId);
        counter[i] = document.getElementById(currIdLabel);
    }

    // get parent(voting) element
    let voteBox = document.getElementById("voting" + String(poll));

    // Eventlistener and ajax for each choices
    for(let i = 0; i < choice.length; i++){
        // Using event delegation
        voteBox.addEventListener('click', function(event){
            // String for target matches
            let targetMatch = "button#poll"+String(poll)+"choice"+String(i);

            for (var target = event.target; target && target != this; target = target.parentNode) {
                if (target.matches(targetMatch)) {
                    // Create ajax object
                    let xhr = new XMLHttpRequest();

                    // Check ajax readiness
                    xhr.onreadystatechange = function(){
                        if(xhr.readyState == 4 && xhr.status == 200){
                            pollBox.innerHTML = xhr.responseText;
                        }
                    };
        
                    // Execute ajax
                    xhr.open("GET", "ajax/submitVote.php?pollId=" + String(pollId) + "&choice=" + String(i) + "&idx=" + String(poll), true);
                    xhr.send();
                    break;
                }
            }
        }, false);
    }
}

/* End of section for choice ajax */

/* AJAX for search bar */

// Get search bar element
let search_bar = document.getElementById("search_bar");

// Get main element
let main_section = document.getElementById("main_section");

// Event listener for search bar
search_bar.addEventListener("keyup", function(){
    // Create ajax object
    let xhr = new XMLHttpRequest();

    // Check ajax readiness
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            main_section.innerHTML = xhr.responseText;
        }
    }

    // Execute ajax
    xhr.open("GET", "ajax/pollfind.php?keyword=" + search_bar.value, true);
    xhr.send();

});



/* End of section for search AJAX */