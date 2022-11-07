<?php

$newcity = filter_input(INPUT_POST, 'newcity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$countrycode = filter_input(INPUT_POST, 'countrycode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$newpopulation = filter_input(INPUT_POST, 'population', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$city = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDO Tutorial</title>
  <link rel="stylesheet" href="./css/main.css">
</head>

<body>
  <main>
    <header>
      <h1>PHP PDO Tutorial</h1>
    </header>

    <?php
    if (isset($deleted)) {
      echo 'Record Deleted.<br><br>';
    } else if (isset($updated)) {
      echo 'Record Updated.<br><br>';
    }

    ?>
    <?php

    if (!$city && !$newcity) {  ?>
      <section>
        <h2>Select Data / Reda Data</h2>
        <form action="." method="GET">
          <label for="city">City Name:</label>
          <input type="text" id="city" name="city" required>
          <button>Submit</button>
        </form>
      </section>
      <section>
        <h2>Insert Data / Create Data</h2>
        <form action="." method="POST">

          <label for="newcity">City Name:</label>
          <input type="text" id="newcity" name="newcity" required>

          <label for="countrycode">Country Code:</label>
          <input type="text" id="countrycode" name="countrycode" maxlength="3" required>

          <label for="district">District:</label>
          <input type="text" id="district" name="district" required>

          <label for="pupulation">Population:</label>
          <input type="text" id="pupulation" name="pupulation" required>

          <button>Submit</button>

        </form>
      </section>
    <?php } else { ?>
      <?php require('./database.php'); ?>

      <?php
      if ($newcity) {
        $query = "INSERT INTO city
        (Name,CountryCode,District,Population)
          VALUES
        (:newcity, :countrycode, :district, :newpopulation)";

        $statement = $db->prepare($query);
        $statement->bindValue(':newcity', $newcity);
        $statement->bindValue(':countrycode', $countrycode);
        $statement->bindValue(':district', $district);
        $statement->bindValue(':newpopulation', $newpopulation);
        $statement->execute();
        $statement->closeCursor();
      }

      if ($city || $newcity) {
        $query = 'SELECT * FROM city
                        WHERE Name = :city
                        ORDER BY Population DESC';

        $statement = $db->prepare($query);
        if ($city) {
          $statement->bindValue(':city', $city);
        } else {
          $statement->bindValue(':city', $newcity);
        }
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
      }
      ?>
      <?php if (!empty($results)) { ?>
        <section>
          <h2>Update or Delete Data</h2>
          <?php foreach ($results as $result) {
            $id = $result['ID'];
            $city = $result['Name'];
            $countrycode = $result['CountryCode'];
            $district = $result['District'];
            $population = $result['Population'];

          ?>
            <form class="update" action="update_record.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <label for="city-<?php echo $id ?>">City Name:</label>
              <input type="text" id="city-<?php echo $id ?>" name="=city" value="<?php echo $city ?>" required>

              <label for="countrycode-<?php echo $id ?>">Country Code:</label>
              <input type="text" id="countrycode-<?php echo $id ?>" name="=countrycode" value="<?php echo $countrycode ?>" required>

              <label for="district-<?php echo $id ?>">District:</label>
              <input type="text" id="district-<?php echo $id ?>" name="=district" value="<?php echo $district ?>" required>

              <label for="population-<?php echo $id ?>">Population:</label>
              <input type="text" id="population-<?php echo $id ?>" name="=population" value="<?php echo $population ?>" required>

              <button>Update</button>
            </form>

            <form class="delete" action="delete_record.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $id; ?>">
              <button class="red">Delete</button>
            </form>
          <?php } ?>

        </section>
      <?php } else { ?>
        <p>Sorry no results</p>
      <?php } ?>
      <a href=".">Go to Request Forms</a>

    <?php } ?>
  </main>
</body>

</html>