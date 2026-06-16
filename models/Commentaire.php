<?php

class Commentaire {

public function __construct(private string $content, private bool $is_valid, private string $created_at,
private ?int $id = null, private ?int $review_id = null, private ?int $user_id = null)
{
}

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getReviewId() : ?int { return $this->review_id; }
public function setReviewId(?int $review_id) : void { $this->review_id = $review_id; }

public function getUserId() : ?int { return $this->user_id; }
public function setUserId(?int $user_id) : void { $this->user_id = $user_id; }

public function getContent() : string { return $this->content; }
public function setContent(string $content) : void { $this->content = $content; }

public function getIsValid() : bool { return $this->is_valid; }
public function setIsValid(bool $is_valid) : void { $this->is_valid = $is_valid; }

public function isValid() : bool { return $this->is_valid === true; }

public function getCreatedAt() : string { return $this->created_at; }
public function setCreatedAt(string $created_at) : void { $this->created_at = $created_at; }
}
