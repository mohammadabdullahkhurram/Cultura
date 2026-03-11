<?php
?>
    <script>
      (function(){
        try {
          var el = document.getElementById('yr');
          if (el) el.textContent = new Date().getFullYear();
        } catch(e){}
      })();
    </script>

    <!-- jQuery + jQuery UI (must load before autocomplete.js) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="" crossorigin="anonymous"></script>

    <!-- Your autocomplete initializer -->
    <script src="/includes/autocomplete.js"></script>
</body>
</html>
