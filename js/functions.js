function login(id, password) {
    
    $.ajax({
        url: "include/functions.php",
        type: "POST",

     
        data: {
            id: id,
            password: password,
            action: "login"
        },
        success: function(data) {
            console.log(data);
            logs=JSON.parse(data)
            console.log(logs);
            if (logs[0] == "logged in") {
                console.log("logged in");
                window.location.href = "index.php";
            }
        }

    });
}
function logout(){
    $.ajax({
        url: "include/functions.php",
        type: "POST",
        data: {
            action: "logout"
        },
        success: function(data) {
            console.log(data);
            window.location.href = "login.php";
        }

    });
    
}
function change_password(){
    $.ajax({
        url: "include/functions.php",
        type: "POST",
        data: {
            action: "change_password"
        },
        success: function(data) {
            console.log(data);
            // window.location.href = "login.php";
        }

    });


}




      
        
          
