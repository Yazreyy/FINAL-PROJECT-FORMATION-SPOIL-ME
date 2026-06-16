<?php

class Review {

public function __construct(private string $content, private bool $is_official, private string $created_at,
private ?int $id = null, private ?int $series_id = null, private ?int $user_id = null)
{
}

public function getContent() : string { return $this->content; }
public function setContent(string $content) : void { $this->content = $content; }

public function getIsOfficial() : bool { return $this->is_official; }
public function setIsOfficial(bool $is_official) : void { $this->is_official = $is_official; }

public function getCreatedAt() : string { return $this->created_at; }
public function setCreatedAt(string $created_at) : void { $this->created_at = $created_at; }

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getSeriesId() : ?int { return $this->series_id; }
public function setSeriesId(?int $series_id) : void { $this->series_id = $series_id; }

public function getUserId() : ?int { return $this->user_id; }
public function setUserId(?int $user_id) : void { $this->user_id = $user_id; }

public function isOfficial() : bool { return $this->is_official === true; }
}
