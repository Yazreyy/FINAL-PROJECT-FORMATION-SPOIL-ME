<?php

class User{
    
public function __construct(private ?int $id = null, private string $pseudo, private string $email,
private string $password, private string $avatar, private string $role, private string $date_inscription)
{
    
}

public function getId() : ?int {
    return $this->id;
}
public function setId(int $id) : void {
    $this->id = $id;
}

public function getPseudo() : string {
    return $this->pseudo;
}
public function setPseudo(string $pseudo) : void {
    $this->pseudo = $pseudo;
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

public function getAvatar() : string {
    return $this->avatar;
}
public function setAvatar(string $avatar) : void {
    $this->avatar = $avatar;
}

public function getRole() : string {
    return $this->role;
}
public function setRole(string $role) : void {
    $this->role = $role;
}

public function getDate() : string {
    return $this->date_inscription;
}
public function setDate(string $date_inscription) : void {
    $this->date_inscription = $date_inscription;
}

public function isAdmin() : bool {
    //Verifie si le role est bien admin//
   return $this->role === 'admin'; 
}
public function getAvatarUrl() : string {
    //Si $this->avatar === null alors il retourne la valeur par defaut//
    return $this->avatar ?? 'uploads/avatar/default.jpg';
}



}