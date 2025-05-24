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
      echo "\n -> ".$task['id']." ". $task['description']." (status: ". $task['status'].") \n";
    }
    return $this->tasks;
  }

  /**
   * update task by id
   * @param string $id
   * @param string $description
   * @return void
   */
  public function updateTaskById(string $id, string $description):void
  {
    $key = 'description';
    $this->updateTaskFile($this->updatedTask($id, $description, $key));
    echo 'Modification réussi !';
  }

  /**
   * update task by id
   * @param string $id
   * @param string $status
   * @return void
   */
  public function updateTaskStatusById(string $id, string $status):void
  {
    $key = 'status';
    $this->updateTaskFile($this->updatedTask($id, $status, $key));
    echo 'Status à jour.';
  }

  /**
   * deleted task by id
   * @param string $id
   * 
   */
  public function deleteTask(string $id): void
  {
    $tasks = json_decode(json_encode($this->tasks),true);
    foreach ($tasks as $key => $task) {
      if ($task['id'] === $id) {
        unset($this->tasks[$key]);
        $this->updateTaskFile($this->tasks);
        exit;
      }
      echo "+ Tâche $id supprimer";
    }
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
  public function getLastTaskId():int
  {
    $lastTask = json_decode(json_encode(end($this->tasks)), true);
    if (!$lastTask) {
      return 1;
    }
    return $lastTask['id'] + 1;
  }


  /**
   * filter task by id and changed values 
   * @param $id
   * @param $value
   * @param $key
   * @return array Tasks
   */
  private function updatedTask(string $id, string $value, string $key):array
  {
    $tasks = json_decode(json_encode($this->tasks),true);
    $isValideId = $this->getValideId($id);
    if ($isValideId === -1) {
      echo 'vérifier le id';
      exit;
    }
    foreach ($tasks as $index => $task) {
      if ($task['id'] === $id) {
        $task[$key] = $value;
        $task['updated_at'] = date('y-m-d', time());
        $this->tasks[$index]= $task;
      }
    }

    return $this->tasks;
  }

}