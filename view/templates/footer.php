<footer>
  <?php
  if(isset($_SESSION['ulogovan'])){
    echo '<a href = "index.php?stranica=logout">Izloguj se</a>';
  }
  ?>
  <div class="row">
	<div class = "at col-lg-4">
      <h4>Admin Team:</h4><a href = "https://github.com/aleksa22">Aleksa22</a>
      <a href = "https://github.com/knlju">Knlju</a>
	</div>
      <div class="ru col-lg-4">
        <p>Pravila možete pogledati <a href="?stranica=pomoc">ovde!</a><br>
           Pravila se moraju postovati <b>ili!</b></p>
      </div>
		<div class="sm col-lg-4">
		  <h4> Social Media: </h4>
		  <a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a>
		  <a href="www.instagram.com"> <i class="fa fa-instagram"></i></a>
		  <a href="www.twitter.com"> <i class="fa fa-twitter"></i></a>
		  <a href="www.youtube.com"> <i class="fa fa-youtube"></i></a>
		</div>
    </div>
  </div>
</footer>
</div>
</body>
<script src = "../view/js/script.js"></script>
<script src = '../ajax/functions.js'></script>
</html>
