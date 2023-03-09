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
            logs=JSON.parse(data)
            console.log(logs);
            if (logs[0] == "logged in") {
                console.log("logged in");
                window.location.href = "index.php";
            }
        }

    });
}

      
        
          
