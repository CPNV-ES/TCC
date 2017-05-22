function ee(r){
  var c=document.querySelector('.background');
  var imgs=['/css/images/ee/1.jpg','/css/images/ee/2.gif','/css/images/ee/3.jpg'];
  if(r==0) if(c!=null) c.style.backgroundImage='url('+imgs[Math.floor(Math.random() * imgs.length)]+')';
}
function ee2(){
  $('body').css('filter','hue-rotate('+Math.ceil(Math.random()*360)+'deg)')
}
window.addEventListener('load', function(e) {
  var r=Math.ceil(Math.random() * 2000);
  ee(r);
});
var lasteec=new Date();
var eecount=0;
window.addEventListener('keypress',function(e){
  var code =e.keycode||e.which;
  if(code == 105){
    eecount++;
    var now = new Date();
    if(now.getTime()-lasteec.getTime()<500){
      if(eecount>3)
        ee2()
    }else{
      eecount=0;
    }
    lasteec=now;
  }
})
