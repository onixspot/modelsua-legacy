<?php

$pdo = new PDO('pgsql:dbname=modelsua host=postgres.modelsua', 'postgres', 'postgres');

$statement = $pdo->prepare('select * from user_data where birthday is not null');
$statement->execute();

array_map(function ($userData) use ($pdo) {
    $d = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $userData['birthday']);


    // $s = $pdo->prepare('update user_data set dob_day = :day, dob_month = :month, dob_year = :year');
    // $s->execute([
    //     'day' => $birthday
    // ]);
}, $statement->fetchAll());