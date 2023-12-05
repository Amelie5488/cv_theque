document.addEventListener("DOMContentLoaded", () => {

    function toggleCon() {
        let switchCon = document.getElementById('toggle')
        let registerForm = document.getElementById('register')
        let loginForm = document.getElementById('login')
        switchCon.setAttribute('event', 'true');
        registerForm.style.display = "none";
            switchCon.addEventListener("click", function () {
                hide_Show("toggle");
                if (switchCon.getAttribute("event") == "true") {
                    loginForm.style.display = "";
                    registerForm.style.display = "none";  
                } else {
                    registerForm.style.display = "";
                    loginForm.style.display = "none"; 
                }
            });
    }
    function hide_Show(param) {
        var x = document.getElementById(param);
        if (x.getAttribute("event") == "true") {
            x.setAttribute('event', 'false');
        } else {
            x.setAttribute('event', 'true');
        }
    }

        toggleCon();


});