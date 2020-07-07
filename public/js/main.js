var url = 'http://localhost:8000';
window.addEventListener("load", function(){
  //boton de like
  var btnLike = $('.btn-like');
  var btnDislike = $('.btn-dislike');

  btnLike.css('cursor','pointer');
  btnDislike.css('cursor','pointer');

  function like(){
    $('.btn-like').unbind('click').click(function(){
      console.log("like.");
      $(this).addClass('btn-dislike').removeClass('btn-like');
      $(this).attr('src', url+'/img/heart-red.png');
      $.ajax({
        url: url+'/like/'+$(this).data('id'),
        type: 'GET',
        success: function(response){
          if(response.like){
            console.log("Diste like en la publicación ");
          }else{
            console.log("Error. No se logró dar like");
          }
        }
      });
      dislike();
    });
  }
  like();
  function dislike(){
    $('.btn-dislike').unbind('click').click(function(){
      console.log("dislike.");
      $(this).addClass('btn-like').removeClass('btn-dislike');
      $(this).attr('src', url+'/img/heart-black.png');
      $.ajax({
        url: url+'/dislike/'+$(this).data('id'),
        type: 'GET',
        success: function(response){
          if(response.like){
            console.log("Diste dislike en la publicación ");
          }else{
            console.log("Error. No se logró dar dislike");
          }
        }
      });
      like();
    });
  }
  dislike();

  //Search
  $('#buscador').submit(function(){
    $(this).attr('action', url+'/people/'+$('#buscador #search').val());

  });
})
