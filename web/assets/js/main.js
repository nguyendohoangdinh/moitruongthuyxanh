jQuery(document).ready(function ($) {

 
    function getScrollBarWidth() {
      var inner = document.createElement('p');
      inner.style.width = "100%";
      inner.style.height = "200px";
  
      var outer = document.createElement('div');
      outer.style.position = "absolute";
      outer.style.top = "0px";
      outer.style.left = "0px";
      outer.style.visibility = "hidden";
      outer.style.width = "200px";
      outer.style.height = "150px";
      outer.style.overflow = "hidden";
      outer.appendChild(inner);
  
      document.body.appendChild(outer);
      var w1 = inner.offsetWidth;
      outer.style.overflow = 'scroll';
      var w2 = inner.offsetWidth;
      if (w1 == w2) w2 = outer.clientWidth;
  
      document.body.removeChild(outer);
  
      return (w1 - w2);
    }
 
    if ($('#top-bar-option-nav').length > 0) {
        var htmktopbar = $('#top-bar-option-nav').html();
        $('#header #top-bar .nav-top-bar').html(htmktopbar);

    }



  
    $(".header-button-join #menuopen-button").on("click", function (e) { 
        $('.mainmenu').toggleClass('actived');
    });
    // $(".mainmenu .button-close-menu").on("click", function (e) { 
    //     $('.mainmenu').removeClass('actived');
    // }); 
    $(window).scroll(function() {
        if( $(window).scrollTop() > 50 ) { 
            if(!$('#header').hasClass('fixed')){
            $('#header').addClass('fixed');
            } 
        }else{
            $('#header').removeClass('fixed');
        }   
    });  



    $(".accordian-wapper .itemtab-accordian-wapper .accordian-nav-item").on("click", function (e) { 
        var tabactived = $(this).data('tab');

        $(".accordian-wapper .itemtab-accordian-wapper .accordian-nav-item").removeClass('actived');
        $(this).addClass('actived');

        $(".accordian-wapper .itemtab-accordian-wapper-content .accordian-item").removeClass('actived');
        $(this).parents('.accordian-wapper').find('.itemtab-accordian-wapper-content .accordian-content-item-' + tabactived).addClass('actived');
            
    }); 
   
    $('.contact-form .freeform-column .zipcode').each(function( index ) {
        $(this).parent().addClass('row-zipcode');  
      });

      const slideshowImages = document.querySelectorAll(".intro-slideshow img");

const nextImageDelay = 5000;
let currentImageCounter = 0; // setting a variable to keep track of the current image (slide)

// slideshowImages[currentImageCounter].style.display = "block";
slideshowImages[currentImageCounter].style.opacity = 1;

setInterval(nextImage, nextImageDelay);

function nextImage() {
  // slideshowImages[currentImageCounter].style.display = "none";
  slideshowImages[currentImageCounter].style.opacity = 0;

  currentImageCounter = (currentImageCounter+1) % slideshowImages.length;

  // slideshowImages[currentImageCounter].style.display = "block";
  slideshowImages[currentImageCounter].style.opacity = 1;
}
        
});