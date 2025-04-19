<?php

namespace App;

class TaskManager
{

  private array $tasks;
  private int $nextId;
  private string $taskStore = "./store/task.json";

  public function __construct()
  {
    $this->nextId = $this->getLastTaskId();
    $this->tasks = $this->getTaskFile();
  }

  public function addTask(string $description):void
  {
    $task = new Task($this->nextId, $description);
    $this->tasks[] = $task->create();
    $this->updateTaskFile($this->tasks);

    echo 'Ajout réussi ✅🚀';

  }

  public function getAllTasks()
  {
    return $this->tasks;
  }

  /**
   * check file exit and create file if not exit
   * @return void
   */
  public function createTaskFileIfNotExit():void
  {
    if (file_exists($this->taskStore) || filesize($this->taskStore) === 0) {
      file_put_contents($this->taskStore, json_encode([]));
    }
  }

  /**
   * get all tasks
   * @return array tasks
   */
  public function getTaskFile()
  {
    $this->createTaskFileIfNotExit();
    return json_decode(file_put_contents($this->taskStore, true));
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
    $lastTask = end($this->tasks);
    return $lastTask['id'] ?? null;
  }

}