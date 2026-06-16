<?php

class Note{

public function __construct(private int $value, private string $created_at,
private ?int $id = null, private ?int $user_id = null, private ?int $series_id = null)
{
}

public function getValue() : int { return $this->value; }
public function setValue(int $value) : void { $this->value = $value; }

public function getCreatedAt() : string { return $this->created_at; }
public function setCreatedAt(string $created_at) : void { $this->created_at = $created_at; }

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getUserId() : ?int { return $this->user_id; }
public function setUserId(?int $user_id) : void { $this->user_id = $user_id; }

public function getSeriesId() : ?int { return $this->series_id; }
public function setSeriesId(?int $series_id) : void { $this->series_id = $series_id; }
}
