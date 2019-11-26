#!/usr/bin/env php
<?php

define('APP_CONSOLE', true);

const AGENCY_CONTACTS_SCHEME = [
    'link'           => '',
    'another_person' => '0',
    'website'        => '',
    'phone'          => '',
    'email'          => '',
    'skype'          => '',
    'icq'            => '',
    'address'        => '',
    'person'         => 0,
];

class Console
{
    /** @var array */
    private $configuration;

    /** @var PDO */
    private $pdo;

    /**
     * @return Console
     */
    public static function create()
    {
        $config     = require __DIR__.'/../config/parent.php';
        $masterDb   = $config['databases']['master'];
        $pdoDsn     = vsprintf('pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s', [
            $masterDb ['host'],
            isset($masterDb['port']) ? $masterDb['port'] : 5432,
            $masterDb['dbname'],
            $masterDb['user'],
            $masterDb['password'],
        ]);
        $pdoOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $db         = new PDO($pdoDsn, null, null, $pdoOptions);


        return (new self())
            ->configure($db);
    }

    /**
     * @return $this
     */
    public function configure()
    {
        $args                = func_get_args();
        $this->configuration = $args;

        foreach ($args as $arg) {
            if ($arg instanceof PDO) {
                $this->pdo = $arg;
            }
        }

        return $this;
    }

    public function run($command, $args = [])
    {
        $statement        = $this->pdo->query('select * from agency where id = 88');
        $agencies         = $statement->fetchAll();
        $countFetchedRows = count($agencies);
        $countHandledRows = 0;

        foreach ($agencies as $agency) {
            $contacts = unserialize($agency['contacts']);

            if (isset($contacts['phone2']) && empty($contacts['phone2'])) {
                unset($contacts['phone2']);

                $statement = $this->pdo->prepare('update agency set contacts = :contacts where id = :id');
                $contacts = serialize($contacts);
                $statement->execute([
                    'id'       => $agency['id'],
                    'contacts' => $contacts,
                ]);
                $countHandledRows++;

                echo vsprintf("[\e[32mOK\e[0m] ID: %s DEBUG: %s\n", [
                    $agency['id'],
                    $contacts,
                ]);
            }
        }

        echo vsprintf("RESULT:\n\tFetched: %s rows\n\tHandled: %s rows\n", [
            $countFetchedRows,
            $countHandledRows,
        ]);
    }
}

Console::create()->run($argv[0], array_slice($argv, 1));
