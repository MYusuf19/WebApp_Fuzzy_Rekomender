var inputcari = document.getElementById('caribarang');
var container = document.getElementById('isi');

inputcari.addEventListener('keyup',function() {
    console.log('tombol masuk');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200){
                container.innerHTML=xhr.responseText;
                                                  }


                                     }

    xhr.open('GET','hasil-ajax/hasil-carimenu.php?cari='+inputcari.value,'true');
    xhr.send();
                                                })



