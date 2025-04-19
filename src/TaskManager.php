<?php

namespace App;

class TaskManager
{

  private array $tasks;
  private int | null $nextId;
  private string $taskStore = "./store/task.json";

  public function __construct()
  {
    $this->tasks = $this->getTaskFile();
    $this->nextId = $this->getLastTaskId();
  }

  public function addTask(string $description)
  {
    $task = new Task($this->nextId, $description);
    $this->tasks[] = $task->create();
    $this->updateTaskFile($this->tasks);

    echo 'Ajout réussi ✅🚀';
    return $this->getAllTasks();
  }

  public function getAllTasks()
  {
    $tasks = json_decode( json_encode($this->tasks),true);
    echo "\n + task liste \n";
    foreach ($tasks as $task) {
      echo "\n # ".$task['id']." ". $task['description']." ". $task['status']." \n";
    }
    return $this->tasks;
  }

  /**
   * get all tasks
   * @return array tasks
   */
  public function getTaskFile()
  {
    $this->createTaskFileIfNotExit();
    return json_decode(file_get_contents($this->taskStore, true));
  }
  
  /**
   * check file exit and create file if not exit
   * @return void
   */
  public function createTaskFileIfNotExit():void
  {
    if (!file_exists($this->taskStore) || filesize($this->taskStore) === 0) {
      file_put_contents($this->taskStore, json_encode([]));
    }
  } 

  /**
   * update file task store
   * @param array $tasks
   * @return void
   */
  public function updateTaskFile(array $tasks):void
  {
    file_put_contents($this->taskStore,json_encode($tasks));
  }

  /**
   * validate id function
   * @param int $taskId
   * @return int
   */
  function getValideId(int $taskId) : int 
  {
    if(0 >= $taskId) {
      echo "\n Entrez une Id valide 😉 \n";
      return -1;
    }

    foreach ($this->tasks as $index => $task) {
      return $index;
    }
    
    echo "Le tache ave l'id $taskId n'exit pas 😥";  
    return -1;
  }

  /**
   * get last id task
   * @return int | null
   */
  public function getLastTaskId():int | null
  {
    $lastTask = json_decode(json_encode(end($this->tasks)), true);
    return $lastTask['id'] + 1  ?? null;
  }

}