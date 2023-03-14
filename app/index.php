<?php
// whow just text, intentionally no html mean simple way to achieve main goal
header('Content-Type: text/plain');

// use php PDO to connect mysql
try {
    // MySQL connection don't trush envs, sanitize them
    $dsn = 'mysql:host=' 
    . filter_var(getenv('MYSQL_HOST'), FILTER_SANITIZE_STRING) 
    . ';dbname=' . filter_var(getenv('MYSQL_DATABASE'), FILTER_SANITIZE_STRING);
    echo $dsn;

    // connect to mysql
    $pdo = new PDO($dsn, filter_var(getenv('MYSQL_USER'), FILTER_SANITIZE_STRING), 
    filter_var(getenv('MYSQL_PASSWORD'), FILTER_SANITIZE_STRING));

    // create table users if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    // generate random email suffix
    $rand_email_suffix = substr(md5(rand()), 0, 7);
    // insert some data to mysql table users
    $pdo->exec("INSERT INTO `users` (`name`, `email`) VALUES ('John Doe', '$rand_email_suffix-test@example.com')");
    
    // second random email suffix
    $rand_email_suffix = substr(md5(rand()), 0, 7);
    // insert some data to mysql table users
    $pdo->exec("INSERT INTO `users` (`name`, `email`) VALUES ('John Doe', '$rand_email_suffix-test@example.com')");

    // read some data from mysql table users
    $stmt = $pdo->query("SELECT * FROM `users`");
    print "Found rows:\n";
    while ($row = $stmt->fetch()) {
        print_r($row);
    }
} catch (PDOException $e) {
    print "Problem with mysql : " . $e->getMessage();
}

print "\n---\n";

// catch any errors and display them
try {
    // MongoDB connection
    $manager = new MongoDB\Driver\Manager('mongodb://'
        . filter_var(getenv('MONGO_HOST'), FILTER_SANITIZE_STRING).':27017'
        . '/' . filter_var(getenv('MONGO_INITDB_DATABASE'), FILTER_SANITIZE_STRING));

    // use mongo db manager and write some data to mongo db
    $bulk = new MongoDB\Driver\BulkWrite;

    // generate random email suffix and x, insert data to MongoDB
    $rand_email_suffix = substr(md5(rand()), 0, 7);
    $rand_x = rand(1, 999999);
    $bulk->insert(['x' => $rand_x, 'name' => 'John Doe', 'email' => $rand_email_suffix . '-test@example.com']);

    // generate second random email suffix and x, insert data to MongoDB
    $rand_email_suffix = substr(md5(rand()), 0, 7);
    $rand_x = rand(1, 999999);
    $bulk->insert(['x' => $rand_x, 'name' => 'John Boe', 'email' => $rand_email_suffix . '-test@example.com']);

    $manager->executeBulkWrite('test.users', $bulk);

    // use mongo db manager and read some data from mongo db
    $filter = ['x' => ['$gt' => 0]];
    $options = [
        'projection' => ['_id' => 0],
        'sort' => ['x' => -1],
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('test.users', $query);
    print "Found documents:\n";
    print_r($cursor->toArray());
}
catch (Exception $e) {
    print "Problem with mongo : " . $e->getMessage();
}