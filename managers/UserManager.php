<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findById(?int $id): ?User
    {
        $query = $this->db->prepare('SELECT * FROM User WHERE id= :id');
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['pseudo'],
            $result['email'],
            $result['mot_de_passe'],
            $result['avatar'],
            $result['role'],
            $result['date_inscription'],
            $result['id']
        );
    }


    public function findByEmail(string $email): ?User
    {
        $query = $this->db->prepare('SELECT * FROM User WHERE email = :email');
        $parameters = [
            'email' => $email

        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['pseudo'],
            $result['email'],
            $result['mot_de_passe'],
            $result['avatar'],
            $result['role'],
            $result['date_inscription'],
            $result['id']
        );
    }

    public function findByPseudo(string $pseudo): ?User
    {
        $query = $this->db->prepare('SELECT * FROM User WHERE pseudo = :pseudo');
        $parameters = [
            'pseudo' => $pseudo
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['pseudo'],
            $result['email'],
            $result['mot_de_passe'],
            $result['avatar'],
            $result['role'],
            $result['date_inscription'],
            $result['id']
        );
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM User');
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = new User(
                $result['pseudo'],
                $result['email'],
                $result['mot_de_passe'],
                $result['avatar'],
                $result['role'],
                $result['date_inscription'],
                $result['id']
            );
        }
        return $users;
    }

    public function create(User $user): void
    {
        $query = $this->db->prepare('INSERT INTO User (pseudo, email, mot_de_passe,avatar, role, date_inscription)
            VALUES(:pseudo, :email, :mot_de_passe,:avatar,:role,:date_inscription)');
        $parameters = [
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'mot_de_passe' => $user->getPassword(),
            'avatar' => $user->getAvatar(),
            'role' => $user->getRole(),
            'date_inscription' => $user->getDate()
        ];
        $query->execute($parameters);
        $user->setId((int)$this->db->lastInsertId());
    }

    public function update(User $user): User
    {
        $query = $this->db->prepare('UPDATE User
             SET pseudo = :pseudo, email = :email, mot_de_passe = :mot_de_passe, avatar = :avatar, role = :role, date_inscription = :date_inscription
            WHERE id = :id');
        $parameters = [
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'mot_de_passe' => $user->getPassword(),
            'avatar' => $user->getAvatar(),
            'role' => $user->getRole(),
            'date_inscription' => $user->getDate()
        ];
        $query->execute($parameters);
        return $user;
    }

    public function delete(User $user): void
    {
        $query = $this->db->prepare('DELETE FROM User WHERE id = :id');
        $parameters = [
            'id' => $user->getId()
        ];
        $query->execute($parameters);
    }

    public function findVip(int $limit = 3) : array {
        $query = $this->db->prepare("SELECT * FROM User WHERE role = 'vip' LIMIT :limit");
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $users = [];

    foreach($results as $result) {
        $users[] = new User($result['pseudo'], $result['email'], $result['mot_de_passe'],
            $result['avatar'], $result['role'], $result['date_inscription'], $result['id']);
    }
    return $users;
    }

    public function countAll() : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM User');
    $query->execute();
    return (int)$query->fetchColumn();
}
}
