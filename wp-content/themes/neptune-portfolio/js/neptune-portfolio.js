jQuery(document).ready(function($){


var $container = $('.boxer').imagesLoaded( function() {
//var $container = $('.masonry');
  $container.imagesLoaded(function(){
    $container.masonry({
    // options
    //columnWidth: '.grid-sizer',
    itemSelector: 'article',
    //percentPosition: true,
    //columnWidth: 200
    });
  });
});
});