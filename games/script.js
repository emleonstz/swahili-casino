$(document).ready(function () {
  $('#uba').hide();
  $('#depo').hide();
  $('#regi').hide();
  $('#logi').hide();
  $('#prof').hide();
    initialize_logic();
    function initialize_logic(){
        $.ajax({
          url:"main.php",
          dataType:"json",
          type:"POST",
          success: function(response){
            console.log(response.login_status);
            if(response.login_status == "logedin"){
              const balance = response.user_balance;
              const token = response.toekn;
              const accountSatauts = response.accont_status;
              if(accountSatauts != "active"){
                if(accountSatauts == "blocked"){
                  swal({
                    title: "Error code #E108",
                    text: "Your account has been blocked. This is because we have received reports that you have violated our terms of service. If f you believe that this is a mistake, please contact our admin In the meantime, your account will remain blocked.",
                    icon: "info",
                    button: "Back to Lobby",
                  });
                }else{
                  swal({
                    title: "Error code #E108",
                    text: "your account is not yet activated. please click on the activation button below",
                    icon: "info",
                    button: "Back to Lobby",
                  });
                }
              }else{
                pad = token;
                $('#uba a').text(tohela(balance));
                $('#uba').show();
                $('#depo').show();
                $('#regi').hide();
                $('#logi').hide();
                $('#prof').show();
              }
            }else{
              $('#uba').hide();
              $('#depo').hide();
              $('#regi').show();
              $('#logi').show();
              $('#prof').hide();
            }
            
          },
          error:function(){
            
          }
        });
    }
    function tohela(number) {
        const formattedNumber =
          "Tsh " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return formattedNumber;
    }
    $('#logi button').click(function(){
        login();
    });
    function login() {
        var password = "";
        var phone = "";
        Swal.fire({
          title: 'Weka nambari yako ya simu',
          input: 'text',
          inputPlaceholder: '0xxxxxxxxx',
          showCancelButton: true,
          confirmButtonText: 'Endelea',
          cancelButtonText: 'Sitisha'
        }).then((result) => {
          if (result.value) {
            phone = result.value;
            if(phone != ""){
              Swal.fire({
                title: 'Weka nenosiri lako',
                input: 'password',
                inputPlaceholder: 'nenosiri',
                showCancelButton: true,
                confirmButtonText: 'Endelea',
                cancelButtonText: 'Sitisha'
              }).then((result) => {
                if (result.value) {
                  password = result.value;
                  if( phone != "" && password != ""){
                    if (password != "") {
                      $.ajax({
                        type: "POST",
                        url: "login.php",
                        data: {
                          simu: phone,
                          pass: password,
                        },
                        success: function(data) {
                          console.log(data.login);
                          if (data.login === "pass") {
                            initialize_logic();
                          } else if(data.login === "notverified") {
                            verify();
                          }else if(data.login === "blocked") {
                            Swal.fire({
                              title: "Kosa",
                              text: "Umezuiliwa kutumia huduma hii tafadhali wasiliana na msimamizi",
                              icon: "info",
                            });
                          }else if(data.login === "invalidpassword") {
                            Swal.fire({
                              title: "Kosa",
                              text: "nenosiri batili",
                              icon: "info",
                            });
                          }else if(data.login === "invalidfphone") {
                            Swal.fire({
                              title: "Kosa",
                              text: "nambari ya simu batili",
                              icon: "info",
                            });
                          }else if(data.login === "missingparams") {
                            Swal.fire({
                              title: "Onyo",
                              text: "hujaidhinishwa kutuma ombi hili tafadhali wasiliana na msimamizi",
                              icon: "info",
                            });
                          }else if(data.login === "invalidRequest") {
                            Swal.fire({
                              title: "Onyo",
                              text: "haijaidhinishwa kutuma ombi hili",
                              icon: "info",
                            });
                          }else if(data.login === "notauthorized") {
                            Swal.fire({
                              title: "Onyo",
                              text: "haijaidhinishwa kutuma ombi hili",
                              icon: "info",
                            });
                          }else if(data.login === "alreadylogin") {
                            Swal.fire({
                              title: "Taarifa",
                              text: "Mtumiaji tayari ameingia.",
                              icon: "info",
                            });
                          }
                        },
                        error: function(message) {
                          console.log(message.responseText);
                          Swal.fire({
                            title: "Tatizo",
                            text: "Kulikuwa na hitilafu wakati wa kuingia. Tafadhali jaribu tena.",
                            icon: "error",
                          });
                        },
                      });
                    }
                  }
                }
              });
            }
          }
        });
        
    }
    $('#regi button').click(function(){
      register();
  });
  function register() {
      var password = "";
      var phone = "";
      Swal.fire({
        title: 'Tengeneza Account',
        input: 'text',
        inputPlaceholder: 'Weka namba ya simu',
        showCancelButton: true,
        confirmButtonText: 'Endelea',
        cancelButtonText: 'Sitisha'
      }).then((result) => {
        if (result.value) {
          phone = result.value;
          if(phone != ""){
            Swal.fire({
              title: 'Weka neno la siri',
              input: 'password',
              inputPlaceholder: 'Nywila',
              showCancelButton: true,
              confirmButtonText: 'Endelea',
            }).then((result) => {
              if (result.value) {
                password = result.value;
                if( phone != "" && password != ""){
                  if (password != "") {
                    $.ajax({
                      type: "POST",
                      url: "register.php",
                      data: {
                        simu: phone,
                        pass: password,
                      },
                      success: function(data) {
                        console.log(data.login);
                        if (data.login === "pass") {
                          initialize_logic();
                        } else if(data.login === "notverifed") {
                          verify();
                        }else if(data.login === "blocked") {
                          Swal.fire({
                            title: "Kosa",
                            text: "Umezuiliwa kutumia huduma hii tafadhali wasiliana na msimamizi",
                            icon: "info",
                          });
                        }else if(data.login === "invalidpassword") {
                          Swal.fire({
                            title: "Kosa",
                            text: "nenosiri batili",
                            icon: "info",
                          });
                        }else if(data.login === "invalidfphone") {
                          Swal.fire({
                            title: "Kosa",
                            text: "nambari ya simu batili",
                            icon: "info",
                          });
                        }else if(data.login === "missingparams") {
                          Swal.fire({
                            title: "Onyo",
                            text: "hujaidhinishwa kutuma ombi hili tafadhali wasiliana na msimamizi",
                            icon: "info",
                          });
                        }else if(data.login === "invalidRequest") {
                          Swal.fire({
                            title: "Onyo",
                            text: "haijaidhinishwa kutuma ombi hili",
                            icon: "info",
                          });
                        }else if(data.login === "notauthorized") {
                          Swal.fire({
                            title: "Onyo",
                            text: "haijaidhinishwa kutuma ombi hili",
                            icon: "info",
                          });
                        }else if(data.login === "alreadylogin") {
                          Swal.fire({
                            title: "Taarifa",
                            text: "Mtumiaji tayari ameingia.",
                            icon: "info",
                          });
                        }else if(data.login === "alredyexist") {
                          Swal.fire({
                            title: "Taarifa",
                            text: "Mtumiaji tayari anakaunti tafadhali inigia kupiaia akounti yako ili kundelea.",
                            icon: "info",
                          });
                        }
                      },
                      error: function(message) {
                        console.log(message.responseText);
                        Swal.fire({
                          title: "Tatizo",
                          text: "Kulikuwa na hitilafu wakati wa kuingia. Tafadhali jaribu tena.",
                          icon: "error",
                        });
                      },
                    });
                  }
                }
              }
            });
          }
        }
      });
      
  }
  function verify() {
    window.location.href = "validate.php";
  }
  $(document).on("ajaxSend", function() {
    
  });

  // Hide the loading spinner when the Ajax request is complete.
  $(document).on("ajaxComplete", function() {
    
  });
      
  });
  
