<?php
$newcity = filter_input(INPUT_POST, "newcity", FILTER_SANITIZE_STRING);
$countrycode = filter_input(INPUT_POST, "countrycode", FILTER_SANITIZE_STRING);
$district = filter_input(INPUT_POST, "district", FILTER_SANITIZE_STRING);
$population = filter_input(INPUT_POST, "population", FILTER_SANITIZE_STRING);

$city = filter_input(INPUT_GET, "city", FILTER_SANITIZE_STRING);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDO Tutorial</title>
  <link rel="stylesheet" href="css/main.css">
</head>

<body>


  <main>
    <header>
      <h1>PHP PDO Tutorial</h1>
    </header>

    <?php
    if (!$city && !$newcity) { ?>
      <section>
        <h2>Select Data / Read Data</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
          <label for="city">City Name:</label>
          <input type="text" id="city" name="city" required>
          <button>Submit</button>
        </form>
      </section>
      <section>
        <h2>
          Insert Data / Create Data </h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <label for="newcity">City Name:</label>
          <input type="text" id="newcity" name="newcity" required>
          <label for="countrycode">Country Code:</label>
          <input type="text" id="countrycode" name="countrycode" maxlength="3" required>
          <label for="district">District:</label>
          <input type="text" id="district" name="district" required>
          <label for="population">Population:</label>
          <input type="text" id="population" name="population" required>
          <button>Submit</button>
        </form>

      </section>
    <?php } else { ?>
      <?php require("database.php"); ?>

      <?php
      if ($newcity) {
        $query = "INSERT INTO city
                        (Name,CountryCode,District,Population)
                    VALUES(:newcity, :countrycode, :district, :newpopulation)";
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
      <?php
      if (!empty($results)) { ?>
        <section>
          <h2>Update or delete data</h2>
          <?php foreach ($results as $result) {
            $id = $result['ID'];
            $city = $result['Name'];
            $country = $result['CountryCode'];
            $district = $result['District'];
            $population = $result['Population'];
          }
          ?>
        </section>
      <?php } else { ?>
        <p>Sorry no results</p>
      <?php } ?>
    <?php } ?>


  </main>

</body>

</html>