#!/src/bin/env php
<?php

use App\TaskManager;

require __DIR__ . '/../vendor/autoload.php';

$taskManager = new TaskManager();

$command = $argv[1] ?? null; 
$arg1 = $argv[2] ?? null; 
$arg2 = $argv[3] ?? null;

match ($command) {
  "add" => $taskManager->addTask($arg1) ,
  "list"=> $taskManager->getAllTasks(),
  "update"=> $taskManager->updateTaskById($arg1, $arg2),
  "update:status"=> $taskManager->updateTaskStatusById($arg1, $arg2),
  "delete"=> $taskManager->deleteTask($arg1),
  default => print "❌ Invalid command"
};
