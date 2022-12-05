function sendOTP(){
    $(".error").html("").hide();
    var number= $("#mobile").val();
    if (number.length==10 && number !=null){
        var input={
            "mobile_number": number,
            "action":"send_otp"
        };
        $.ajax({
            url:'controller.php',
            type:'POST',
            data : input,
            success: function(respnsive){
                $(".container").html(response);
            }
        });
    }
    else{
        $(".error").html('Please Enter a Valid Number!')
        $(".error").show();
    }
}

function verifyOTP(){
    $(".error").html("").hide();
    $(".success").html("").hide();
    var otp=$("#mobileOtp").val();
    var input={
        "otp":otp,
    }
}