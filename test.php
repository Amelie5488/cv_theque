
<?php 
if (isset($_POST["age"])){
    $today = getdate();
    $annee = explode('-', $_POST["naissance"]);
    $firstString = $annee[0];
    $age = ($today['year'] - $firstString);
}
?>
<form method="post">

<button type="submit" name="age"> donne ta date</button>
<input type="date" name="naissance" class="form-control" id="naissance1">
</form>

