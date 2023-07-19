$(document).ready(function () {
  
  var audio_win = document.getElementById("pata");
  var audio_miss = document.getElementById("kosa");
  var audio_error = document.getElementById("kosa");
  var spin = document.getElementById("spining");
  var mziki = document.getElementById("biti");
  spin.volume = 0.4;
  mziki.volume = 0.5;
  spin.loop = true;
  mziki.loop = true;
  var isplay=true;
  
  var audio_place = document.getElementById("insert");
  var pad = "";
  initgame();
  $('#play-pause').click(function(){
    if(! isplay){
      isplay = true;
      mziki.play();
      $('#play-pause i').removeClass('fa fa-volume-off');
      $('#play-pause i').addClass('fa fa-volume-up');
    }else{
      isplay = false;
      mziki.pause();
      $('#play-pause i').removeClass('fa fa-volume-up');
      $('#play-pause i').addClass('fa fa-volume-off');
    }
  });
  function zimamziki(){
    mziki.pause();
    spin.play();
    var intervalId = setInterval(function(){
      spin.pause();
      if(isplay){
        mziki.play();
      }
    },6000);
    setTimeout(function() {
      clearInterval(intervalId);
      var spn = document.getElementById('spin-btn');
      spn.removeAttribute('disabled');
    }, 7000);
  }
 function disable_reels(){
  $(".reel").attr('disabled', 'disabled');
 }
  $("#spin-btn").click(function () {
    var spn = document.getElementById('spin-btn');
    disable_reels();
    spn.setAttribute('disabled', 'disabled');
    zimamziki();
    $.ajax({
      url: "init.php",
      type: "POST",
      dataType: "json",
      success: function (response) {
        var audio_win = document.getElementById("pata");
        var audio_miss = document.getElementById("kosa");
        var audio_error = document.getElementById("kosa");
        
        const thereel = response.namba;
        const balance = response.balnace;
        const status = response.status;
        const bonuns = response.bonus;
        const won = response.won;
        console.log(status);
        
        $(".reel").removeClass("glow-orange glow-green");
        $("#reel-" + thereel).addClass("glow-orange");
        animateReels(thereel);
        if (status == "sclectbetfirts") {
          Swal.fire(
            'Chagua matunda!',
            'Tafadhali chagua angalau Tunda moja',
            'error'
          );
          $("span").text("");
        } else if (status == "lost") {
          setTimeout(function() {
            $("span").text("");
            $('#gift').text("no win");
            $('#gift').css('color', 'red');
            
            audio_miss.play();
            
          }, 7000);
        } else if (status == "won") {
          setTimeout(function() {
            $("span").text("");
            $("#h").text(tohela(balance));
            $('#gift').addClass("animate__backInDown");
            $('#gift').text(tohela(won));
            $('#gift').css('color', 'green');
            
            audio_win.play(); 

        }, 7000);
          
        } else if (status == "nobetselected") {
          $("span").text("");
          Swal.fire(
            'Chagua matunda!',
            'Tafadhali chagua angalau Tunda moja',
            'error'
          );
          audio_error.play();
          
        }else if (status == "invalidRequest"){
          unkownReel();
          
        }else if (status == "notlogin"){
          swalNotlogin();
          
        }else if (status == "failtoplacebet"){
          
          Swal.fire({
            title: 'Tatizo',
            text: 'imeshindwa kuanzisha mchezo',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Sawa',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }else{
          
          Swal.fire({
            title: 'Tatizo',
            text: 'hitilafu imetokea wakati wa kuchakata ombi lako',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        
        swalError();
      },
    });
  });
  //reel-1 click
  $("#reel-1").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Banana",
        pl:"1",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-1 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-1 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function (message) {
        console.log(message);
        swalError();
      },
    });
  });

  //reel-2 click
  $("#reel-2").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Lemon",
        pl:"2",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-2 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-2 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });
 
   //reel-3 click
   $("#reel-3").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Orange",
        pl:"3",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-3 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-3 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

   //reel-4 click
   $("#reel-4").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Cherry",
        pl:"4",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-4 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-4 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

   //reel-5 click
   $("#reel-5").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Grape",
        pl:"5",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-5 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-5 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

   //reel-6 click
   $("#reel-6").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Pear",
        pl:"6",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-6 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-6 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });
   //reel-7 click
   $("#reel-7").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Apple",
        pl:"7",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-7 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-7 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

   //reel-8 click
   $("#reel-8").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Strawberry",
        pl:"8",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-8 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-8 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

  //reel-9 click
  $("#reel-9").click(function () {
    $(".reel").removeClass("glow-orange glow-green");
    clearGitftbox();
    $.ajax({
      url: "processor.php",
      type: "POST",
      dataType: "json",
      data:{
        bet: "Watermelon",
        pl:"9",
        gamepad: pad
      },
      success: function (response) {
        const balance = tohela(response.balance);
        const times = response.time;
        $(".reel").removeClass("glow-orange glow-green");
        if (response.bet == "placed") {
          $("#h").text(balance);
          $("#reel-9 span").text("x" + times);
          audio_place.play();
        } else if (response.bet == "insuficientBalance") {
          alertInsuficientBalance();
        } else if (response.bet == "deductBalanceError") {
          deductionError();
        } else if (response.bet == "unkownReel") {
          $("#reel-9 span").text("error");
          unkownReel();
        }else if (response.bet == "unAuthorised"){
          notAuth();
        }else if (response.bet == "block"){
          accountBlocked(response.bet == "unAuthorised");
        }else if (response.bet == "notlogin"){
          swalNotlogin();
        }else{
          Swal.fire({
            title: 'Tatizo',
            text: 'Imeshindwa kuweka dau',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Sawa',
            cancelButtonText: 'Rudi nyumbani',
            allowOutsideClick: true,
            allowEscapeKey: true
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "../";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              window.location.href = "../";
            }
          });
        }
      },
      error: function () {
        swalError();
      },
    });
  });

  function alertInsuficientBalance() {
    Swal.fire({
      title: 'Hakuna salio',
      text: "Salio la akaunti yako kwa sasa halitoshi kuendelea na dau uliloomba. Tafadhali weka amana ili kuendelea kucheza mchezo huu",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Weka pesa'
    }).then((result) => {
      if (result.isConfirmed) {
        //to deposit page
        window.location.href = "../depoist.php";
      }
    });
  }
 
  function deductionError() {
    Swal.fire({
      title: 'Tatizo',
      text: "Imeshindwa kuchakata salio la mtumiaji",
      icon: 'warning',
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Rurudi nyumbani'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      }
    });
  }
  function clearGitftbox(){
    $('#gift').addClass("animate__backOutDown");
    $('#gift').text("0");
    $('#gift').css('color', 'white');
    
  }
  function unkownReel() {
    Swal.fire({
      title: 'Tatizo',
      text: "Tafadhali tumia mfumo huu kwa uaminifu. Ikiwa utaendelea na ukiukaji, akaunti yako itafutwa au kufutwa kabisa.Tunaheshimu haki zako za kutumia mfumo huu. Ukiukaji wa sheria za mfumo huu unaweza kusababisha adhabu kali, ikiwa ni pamoja na kufutwa kwa akaunti yako.Tunaomba uelewe kwamba tunachukua suala hili kwa uzito sana. Tunataka kuhakikisha kwamba mfumo huu ni salama na wa kuaminika kwa kila mtu. ",
      icon: 'warning',
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sawa',
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      }
    });
    
  }
  function swalError(){
    Swal.fire({
      title: 'Tatizo',
      text: 'Samahani, kumekuwa na hitilafu katika kusindika ombi lako. Tafadhali bonyeza kitufe ili kurudi nyuma. Asante.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ondoka',
      cancelButtonText: 'Sawa',
      allowOutsideClick: true,
      allowEscapeKey: true
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        
      }
    });
  }
  function swalNotlogin(){
    Swal.fire({
      title: 'Ingia Katika Akaunti',
      text: 'Tafadhali ingia kaitika akaunti yako ili kucheza',
      icon: 'warning',
      showCancelButton: false,
      confirmButtonText: 'Sawa',
      cancelButtonText: 'Sawa',
      allowOutsideClick: true,
      allowEscapeKey: true
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        window.location.href = "../";
      }
    });
  }
  function tohela(number) {
    const formattedNumber =
      "Tsh " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return formattedNumber;
  }
  function notAuth(){
    Swal.fire({
      title: 'Tatizo',
      text: 'hujaidhinishwa kufanya ombi hili',
      icon: 'warning',
      showCancelButton: false,
      confirmButtonText: 'Sawa',
      cancelButtonText: 'Rudi nyumbani',
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        window.location.href = "../";
      }
    });
  }
  function accountBlocked(){
    Swal.fire({
      title: 'Tatizo',
      text: 'Your account has been blocked. This is because we have received reports that you have violated our terms of service. If f you believe that this is a mistake, please contact our admin In the meantime, your account will remain blocked.',
      icon: 'error',
      showCancelButton: false,
      confirmButtonText: 'Sawa',
      cancelButtonText: 'Rudi nyumbani',
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../";
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        window.location.href = "../";
      }
    });
  }
  function animateReels(randomNumber) {
    var reelIndex = 1;
    var interval = setInterval(function () {
      if (reelIndex > 10) {
        clearInterval(interval);
        $("#reel-" + randomNumber)
          .removeClass("glow-orange")
          .addClass("glow-green");
      } else {
        $("#reel-" + reelIndex).addClass("glow-orange");
        reelIndex++;
      }
    }, 500);
  }
  function initgame(){
    $.ajax({
      url:"../main.php",
      dataType:"json",
      type:"POST",
      success: function(response){
        if(response.login_status == "logedin"){
          const token = response.toekn;
          pad = token;
          const balance = response.user_balance;
          $("#h").text(tohela(balance));
        }
      },
      error: function(){
        swalError();
      }
    });
  }
  
});
