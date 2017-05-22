$(function() {
   var elems = document.querySelectorAll('[id]');
    var dock = {};
    var duplicate=[];
    elems.forEach(elem=>{
      if(dock[elem.id]!=undefined){
        dock[elem.id].push(elem);
        duplicate.push(elem.id);
      }else{
        dock[elem.id]=[elem]
      }
    })
    duplicate.forEach(id=>{
      var dels=dock[id];
      console.error('#'+id+' has '+dels.length+' duplicates',dels)
    })
})
