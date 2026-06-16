<?php

class User{

public function __construct(private string $username, private string $email,
private string $password,private string $role, private string $created_at, private ?string $avatar= null,
private ?int $id = null)
{

}

public function getId() : ?int {
    return $this->id;
}
public function setId(int $id) : void {
    $this->id = $id;
}

public function getUsername() : string {
    return $this->username;
}
public function setUsername(string $username) : void {
    $this->username = $username;
}

public function getEmail() : string {
    return $this->email;
}
public function setEmail(string $email) : void {
    $this->email = $email;
}

public function getPassword() : string {
    return $this->password;
}
public function setPassword(string $password) : void {
    $this->password = $password;
}

public function getAvatar() : ?string {
    return $this->avatar;
}
public function setAvatar(?string $avatar) : void {
    $this->avatar = $avatar;
}

public function getRole() : string {
    return $this->role;
}
public function setRole(string $role) : void {
    $this->role = $role;
}

public function getCreatedAt() : string {
    return $this->created_at;
}
public function setCreatedAt(string $created_at) : void {
    $this->created_at = $created_at;
}

public function isAdmin() : bool {
    return $this->role === 'admin';
}

public function getAvatarUrl() : string {
    return $this->avatar ?? 'uploads/avatar/default.jpg';
}

public function isVip() : bool {
    return $this->role === 'vip';
}
}
