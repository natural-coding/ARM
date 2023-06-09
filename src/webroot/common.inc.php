<?php
/**
 * This is a common include file
 * PDO Library Management example application
 * @author Dennis Popel
 * @author nobody
 */

try {
   // Create the connection object
   $conn = createConnection();
   $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
   echo $e->getMessage();
}

/**
 * Создаёт соединение БД
 * Исключения обрабатываются в вызывающем коде
 */
function createConnection() : PDO
{
   // DB connection string and username/password
   $connStr = 'mysql:host=localhost;dbname=pdo';
   $user    = 'root';
   $pass    = '';

   return new PDO($connStr, $user, $pass);
}

/**
 * This function will render the header on every page,
 * including the opening html tag,
 * the head section and the opening body tag.
 * It should be called before any output of the
 * page itself.
 * @param string $title the page title
 */
function showHeader($title)
{
    ?>

<html>
<head>
   <title><?=htmlspecialchars($title)?></title>
   <style>
      .add-record-anchor, .add-record-anchor:visited {
         color: black;
         text-decoration: none;
      }
      .add-record-anchor {
         display: inline-block;
         margin-top: 0.5rem;
         padding: 1rem;
         background-color: #e6e6e6;
      }
      
   </style>
   
</head>
<body>
<h1><?=htmlspecialchars($title)?></h1>


<?php
}
/**
 * This function will 'close' the body and html
 * tags opened by the showHeader() function
 */
function showFooter()
{
    ?>


</body>
</html>


<?php
}