
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

function parseDump(sender){
    var items = sender.value.split("\n");
    for(var x =0;x< items.length;x++){
        var item = items[x].split("\t");
        if(document.getElementsByName(item[0].replace(/\s|-/gi,'')).length > 0){
            document.getElementsByName(item[0].replace(/\s|-/gi,''))[0].value = item[1].replace(',','');
        }
    }
}

var selectedItems = "";
var currentQuery;
var ajaxQueryInterval;

function lookForItem(txt){
    currentQuery = txt;
    clearInterval(ajaxQueryInterval);
    if(txt.value.length>2){
        var ajaxQueryInterval=self.setInterval('execQuery()',2000);
    }
}

function execQuery(){
    clearInterval(ajaxQueryInterval);
    var txt = currentQuery;
	$.ajax({
        url: 'index.php?action=getItemList&ajax&q=' + txt.value,
        success: function(data){$('#ajaxItemList').html(data);}
    });

}

function addItem(selection){
    //$(selection).animate({background-color:yellow;});
    var item = selection.innerHTML;
    var dbore = selection.name;
    //$(selection).animate({background-color:none;});
    if(selectedItems.split(',').indexOf(item) == -1 ){
        var print = $('#selectedItemList').html() + '<div>Add <input type="text" size="5" name="' + dbore + '" value="0">' + item + '</div>';
		$('#selectedItemList').html(print);
        if(selectedItems.length == 0){
            selectedItems = item;
        } else {
            selectedItems += ',' + item;
        }
    }
}