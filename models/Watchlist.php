<?php

class Watchlist {

public function __construct(private ?int $series_id, private string $status, private string $added_at,
private ?int $id = null, private ?int $user_id = null)
{
}

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getUserId() : ?int { return $this->user_id; }
public function setUserId(?int $user_id) : void { $this->user_id = $user_id; }

public function getSeriesId() : ?int { return $this->series_id; }
public function setSeriesId(?int $series_id) : void { $this->series_id = $series_id; }

public function getStatus() : string { return $this->status; }
public function setStatus(string $status) : void { $this->status = $status; }

public function getAddedAt() : string { return $this->added_at; }
public function setAddedAt(string $added_at) : void { $this->added_at = $added_at; }

public function isWatch() : bool { return $this->status === 'vu'; }
}
