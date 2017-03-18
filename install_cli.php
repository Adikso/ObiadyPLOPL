<?php
$stdin = fopen('php://stdin', 'r');

echo "System Obiadowy - Installer 0.1\n\n";

$isMysqlConfigured = false;

while (!$isMysqlConfigured) {
    echo "## MySQL Configuration ##\n";

    echo 'Host: ';
    $host = trim(fgets($stdin));

    echo 'DBName: ';
    $dbname = trim(fgets($stdin));

    echo 'Username: ';
    $username = trim(fgets($stdin));

    echo 'Password: ';
    $password = trim(fgets($stdin));

    echo "\nTesting connection..\n";

    try {
        $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $dbname), $username, $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $isMysqlConfigured = true;
    } catch (PDOException $ex) {
        echo "Connection failed\nError:\n ".$ex->getMessage()."\n\n";
        echo "Try again \n\n";
    }
}

echo "Successfully connected to MySQL\n";
echo "Continue install (y/N)? ";
$continue = trim(fgets($stdin));

if (strtolower($continue) !== 'y'){
    echo "Installation aborted by user";
    die();
}

echo "Creating tables..\n";

try {

    echo "Creating `authorization` table..\n";
    $pdo->query("
    CREATE TABLE authorization
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        expire DATETIME,
        type ENUM('REMEMBERME', 'FACEBOOK', 'APP', 'PASSWORD_REMINDER') NOT NULL,
        userId INT(11) NOT NULL,
        authKey VARCHAR(255) NOT NULL,
        useragent VARCHAR(500) NOT NULL,
        devicename VARCHAR(50) NOT NULL,
        last DATETIME NOT NULL,
        additional VARCHAR(255)
    );
    ");

    echo "Creating `classes` table..\n";
    $pdo->query("
    CREATE TABLE classes
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        year INT(11) NOT NULL,
        class VARCHAR(2) NOT NULL,
        email VARCHAR(255) NOT NULL,
        owner ENUM('LICEUM', 'GIMNAZJUM', 'HIDE') DEFAULT 'LICEUM' NOT NULL
    );
    ");

    echo "Creating `menu` table..\n";
    $pdo->query("
    CREATE TABLE menu
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        date DATE NOT NULL,
        description VARCHAR(255) NOT NULL,
        type ENUM('MEAT', 'VEGE', 'PIZZA') NOT NULL,
        status ENUM('LOCKED', 'UNLOCKED')
    );
    ");

    echo "Creating `messages` table..\n";
    $pdo->query("
    CREATE TABLE messages
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        description VARCHAR(500) NOT NULL,
        target VARCHAR(20) DEFAULT 'ALL' NOT NULL,
        expire DATETIME,
        url VARCHAR(255),
        sort INT(11) DEFAULT '0' NOT NULL
    );
    ");

    echo "Creating `orders` table..\n";
    $pdo->query("
    CREATE TABLE orders
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        date DATETIME NOT NULL,
        dish INT(11) NOT NULL,
        userId INT(11) NOT NULL,
        pizza INT(11)
    );
    ");

    $pdo->query("CREATE UNIQUE INDEX id ON orders (id);");

    echo "Creating `permissions` table..\n";
    $pdo->query("
    CREATE TABLE permissions
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        name ENUM('school_level', 'control_orders', 'add_menu') NOT NULL,
        value VARCHAR(255) NOT NULL,
        user INT(11) NOT NULL
    );
    ");

    echo "Creating `pizza` table..\n";
    $pdo->query("
    CREATE TABLE pizza
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        ingredients VARCHAR(255)
    );
    ");

    echo "Creating `rules` table..\n";
    $pdo->query("
    CREATE TABLE rules
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        target VARCHAR(25) NOT NULL,
        type ENUM('orders') NOT NULL,
        value TEXT
    );
    ");

    echo "Creating `users` table..\n";
    $pdo->query("
    CREATE TABLE users
    (
        id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        login VARCHAR(20) NOT NULL,
        email VARCHAR(80),
        password VARCHAR(100),
        firstname VARCHAR(30) NOT NULL,
        secondname VARCHAR(30) NOT NULL,
        balance DOUBLE DEFAULT '0' NOT NULL,
        classId SMALLINT(6),
        role ENUM('ADMIN', 'GLOBAL', 'CLASS', 'USER', 'SUSPENDED', 'REMOVED'),
        lastlogin TIMESTAMP,
        icon VARCHAR(25)
    );
    ");

    $pdo->query("CREATE INDEX id ON users (id);");

}catch (PDOException $e){
    echo "Tables creation failed! Installation aborted.\nError:\n".$e->getMessage();
    die();
}

echo "Tables created succesfully!\n\n";

echo "## Admin Account ##\n";
echo "Login: ";
$login = trim(fgets($stdin));

echo "Password: ";
$password = trim(fgets($stdin));
$password = password_hash($password, PASSWORD_BCRYPT);

echo "First Name: ";
$firstname = trim(fgets($stdin));

echo "Second Name: ";
$secondname = trim(fgets($stdin));

$bind = [
    ':login' => $login,
    ':password' => $password,
    ':firstname' => $firstname,
    ':secondname' => $secondname,
    ':role' => 'ADMIN'
];

echo "Creating account...\n";

try{
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO users (login, password, firstname, secondname, role) VALUES (:login, :password, :firstname, :secondname, :role)");
    $stmt->execute($bind);

    $id = $pdo->lastInsertId();
    $pdo->commit();

    $stmt = $pdo->prepare("INSERT INTO permissions (name, value, user) VALUES ('control_orders', 'ALLOW', :id)");
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare("INSERT INTO permissions (name, value, user) VALUES ('add_menu', 'ALLOW', :id)");
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare("INSERT INTO permissions (name, value, user) VALUES ('school_level', 'ALL', :id)");
    $stmt->execute([':id' => $id]);

}catch (PDOException $e){
    echo "User account creation failed! Installation aborted.\nError:\n".$e->getMessage();
    die();
}

echo "Admin account created successfully!\n\n";

echo "Create default ordering rules (Y/n)? ";
$continue = trim(fgets($stdin));

if (strtolower($continue) !== 'n'){

    try {
        $pdo->query("INSERT INTO rules (target, type, value) VALUES ('GIMNAZJUM', 'orders', 'a:1:{s:8:\"Saturday\";a:2:{s:4:\"days\";a:5:{i:0;s:6:\"monday\";i:1;s:7:\"tuesday\";i:2;s:9:\"wednesday\";i:3;s:8:\"thursday\";i:4;s:6:\"friday\";}s:4:\"time\";s:8:\"10:00:00\";}}');");
        $pdo->query("INSERT INTO rules (target, type, value) VALUES ('LICEUM', 'orders', 'a:4:{s:6:\"Monday\";a:2:{s:4:\"days\";a:1:{i:0;s:9:\"wednesday\";}s:4:\"time\";s:8:\"10:00:00\";}s:7:\"Tuesday\";a:2:{s:4:\"days\";a:1:{i:0;s:8:\"thursday\";}s:4:\"time\";s:8:\"10:00:00\";}s:9:\"Wednesday\";a:2:{s:4:\"days\";a:1:{i:0;s:6:\"friday\";}s:4:\"time\";s:8:\"10:00:00\";}s:8:\"Saturday\";a:2:{s:4:\"days\";a:2:{i:0;s:6:\"monday\";i:1;s:7:\"tuesday\";}s:4:\"time\";s:8:\"10:00:00\";}}');");

    }catch (PDOException $e){
        echo "Adding default rules failed. Aborting!";
        die();
    }

}

@$pdo->query("INSERT INTO messages (description, target) VALUES ('System obiadowy został zainstalowany', 'ALL');");

echo "Installation finished!\n";