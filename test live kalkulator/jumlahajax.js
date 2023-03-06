var satu = document.getElementById('1');
var dua = document.getElementById('2');
var container = document.getElementById('hasil');

satu.addEventListener('keyup',function() {
    console.log('tombol masuk');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200){
                container.innerHTML=xhr.responseText;
                                                  }


                                     }

    xhr.open('GET','hasil.php?satu='+satu.value+'&dua='+dua.value,'true');
    xhr.send();
                                                })


var container = document.getElementById('hasil');

dua.addEventListener('keyup',function() {
    console.log('tombol masuk');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200){
                container.innerHTML=xhr.responseText;
                                                  }


                                     }

    xhr.open('GET','hasil.php?satu='+satu.value+'&dua='+dua.value,'true');
    xhr.send();
                                                })

