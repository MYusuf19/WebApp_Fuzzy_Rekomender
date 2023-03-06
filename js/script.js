var inputcari = document.getElementById('cari');
var container = document.getElementById('isi');

inputcari.addEventListener('keyup',function() {
    console.log('tombol masuk');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function(){
            if(xhr.readyState==4 && xhr.status==200){
                container.innerHTML=xhr.responseText;
                                                  }


                                     }

    xhr.open('GET','cariajax.php?cari='+inputcari.value,'true');
    xhr.send();
                                                })
