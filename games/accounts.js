window.onload=function () {
    const input = document.getElementById("exampleInputEmail");
    
    
    
    const sp = document.getElementById("spiner");
    sp.style.display = "none";

    function validateOTP(otp) {
        if (otp.length > 6) {
          // Prevent the user from entering more characters
          const event = new Event("change");
          event.stopImmediatePropagation();
        }
      
        return true;
      }
      
      // Attach the validation function to the input field
      document.getElementById("exampleInputEmail").addEventListener("change", function() {
        validateOTP(this.value);
      });
      input.addEventListener("keydown", function(event) {
        // Prevent the user from entering more than 6 digits
        if (event.keyCode >= 48 && event.keyCode <= 57) {
          if (input.value.length === 6) {
            event.preventDefault();
          }
        }
      });
      input.addEventListener("input", function() {
        // Prevent the user from entering more than 6 digits
        if (input.value.length > 6) {
          input.value = input.value.substring(0, 6);
        }
      });
      
      const form = document.getElementById("otpform");
     

      form.addEventListener("submit", function (event) {
        // Prevent the form from submitting
        event.preventDefault();

        // Check if the input contains 6 digits
        if (input.value.length !== 6) {
            Swal.fire(
                'Kosa!',
                'Ingizo lazima liwe na tarakimu 6',
                'error'
              );
          return;
        }

        // Submit the form
        form.submit();
      });


      

};
