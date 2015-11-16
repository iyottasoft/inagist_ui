function sortByFreshness(){
  var stories = $("#stories .sortable_story");
  stories.sort(function(a,b){
    var ai = $(a).attr("id").split("_")[0];
    var bi = $(b).attr("id").split("_")[0];
    if (ai == bi)
      return 0;
    else if (ai > bi)
      return -1;
    else
      return 1;
  });
  for (i=0;i<stories.length;i++){
    $("#stories tbody").append(stories[i]);
  }
}


function sortByStrength(){
  var stories = $("#stories .sortable_story");
  stories.sort(function(a,b){
    var ac = parseInt($(a).attr("id").split("_")[1]);
    var bc = parseInt($(b).attr("id").split("_")[1]);
    if (ac == bc){
      var ai = $(a).attr("id").split("_")[0];
      var bi = $(b).attr("id").split("_")[0];
      if (ai == bi)
        return 0;
      else if (ai > bi)
        return -1;
      else
        return 1;
    } else if (ac > bc)
      return -1;
    else
      return 1;
  });
  for (i=0;i<stories.length;i++){
    $("#stories tbody").append(stories[i]);
  }
}

