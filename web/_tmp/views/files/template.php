<?php
?>
<div class="d1">
  <input id="file" type="file" >
</div>
<span id="fileName"></span>

<script type="text/javascript">
  var i = document.getElementById('file');
  i.onchange = function() {
    var path = i.value;
    var fileName = '';
    if (path) {
      var a = path.split(/[\\\/]/);
      if (a.length > 0) {
        fileName = a[a.length - 1];
      }
      var label = document.getElementById('fileName');
      label.innerHTML = fileName;
    }
  }
</script>

