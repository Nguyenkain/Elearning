/* 
 * Enter your Custom Javascript Functions here
*/

jQuery(document).ready(function($){
	 $(window).scroll(function(event){
    var st = $(this).scrollTop();
    if($('header').hasClass('fix')){
      var headerheight=$('header').height();
      if(st > headerheight){
        $('header').addClass('fixed');
      }else{
        $('header').removeClass('fixed');
      }
    }
  });

    $(".do-animate-bounce").mouseenter(function(event) {
        $(this).addClass("animated bounce");
    }).on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function(event) {
        $(this).removeClass("animated bounce");
    }).mouseleave(function(event) {
        $(this).removeClass("animated bounce");
    });


    $('.box_scroll_right_1').hover(
        function(){
            $(this).css({'right' : '-30px'});
        }, function(){
            $(this).css({'right' : '-136px'});
        }
    );

    $('.box_scroll_right_2').hover(
        function(){
            $(this).css({'right' : '-30px'});
        }, function(){
            $(this).css({'right' : '-123px'});
        }
    );
    $('.tree-toggle').click(function () {
        $(this).parent().children('ul.tree').toggle(200);
    });
    $(function(){
        $('.tree-toggle').parent().children('ul.tree').toggle(200);
    })
});