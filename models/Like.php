<?php

class Like {

public function __construct(private string $created_at, private ?int $id = null,
private ?int $user_id = null, private ?int $review_id = null, private ?int $comment_id = null)
{
}

public function getCreatedAt() : string { return $this->created_at; }
public function setCreatedAt(string $created_at) : void { $this->created_at = $created_at; }

public function getId() : ?int { return $this->id; }
public function setId(?int $id) : void { $this->id = $id; }

public function getUserId() : ?int { return $this->user_id; }
public function setUserId(?int $user_id) : void { $this->user_id = $user_id; }

public function getReviewId() : ?int { return $this->review_id; }
public function setReviewId(?int $review_id) : void { $this->review_id = $review_id; }

public function getCommentId() : ?int { return $this->comment_id; }
public function setCommentId(?int $comment_id) : void { $this->comment_id = $comment_id; }
}
