(function(){ var b=document.getElementById('promo-banner'); if(!b) return;
      if(localStorage.getItem('pomdrPromoDismissed')==='1') b.classList.add('dismissed');
      var x=b.querySelector('.promo-dismiss');
      if(x) x.addEventListener('click', function(){ b.classList.add('dismissed'); try{localStorage.setItem('pomdrPromoDismissed','1');}catch(e){} });
    })();
