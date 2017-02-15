window.addEventListener('load', function(e) {
  var r=Math.floor(Math.random() * 2000);
  var c=document.querySelector('.background');
  var imgs=['/css/images/ee/1.jpg','/css/images/ee/2.gif','/css/images/ee/3.jpg'];
  if(r==0) if(c!=null) c.style.backgroundImage='url('+imgs[Math.floor(Math.random() * imgs.length)]+')';
});
