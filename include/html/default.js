
var eveTimeRefreshRate = 60;

function getTime(zone, success) {
    var url = 'http://json-time.appspot.com/time.json?tz=' + zone,
        ud = 'json' + (+new Date());
    window[ud]= function(o){
        success && success(o.datetime);
    };
    document.getElementsByTagName('head')[0].appendChild((function(){
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = url + '&callback=' + ud;
        return s;
    })());
}

function tableFix(){
  $('.table > .tableRow:first-child > div:only-child').parent().css("display","table-caption").css("caption-side","top");
  $('.table > .tableRow:nth-last-child(2) > div:only-child').parent().css("display","table-caption").css("caption-side","bottom");
}

function updateTime(){
  getTime('GMT', function(time){
    $('#eveTime').text(time);
  });
  setTimeout('updateTime()', eveTimeRefreshRate * 1000);
}
