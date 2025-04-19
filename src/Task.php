<?php

namespace App;

class Task
{
  private string $status = "pending";
  private string $createdAt;
  private string $updatedAt;

  public function __construct
  (
    private string $id,
    private string $description,
  )
  {
      $this->createdAt = date("y-m-d");
      $this->updatedAt = date("y-m-d");
  }

  /**
   * create new task
   * @return array
   */
  public function create():array
  {
    return [
      "id"          => $this->id,
      "description" => $this->description,
      "status"      => $this->status,
      "createdAt"   => $this->createdAt,
      "updatedAt"   => $this->updatedAt
    ];
  }

}