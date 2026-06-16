<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findById(?int $id): ?User
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['username'],
            $result['email'],
            $result['password'],
            $result['role'],
            $result['created_at'],
            $result['avatar'],
            $result['id']
        );
    }

    public function findByEmail(string $email): ?User
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE email = :email');
        $query->execute(['email' => $email]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['username'],
            $result['email'],
            $result['password'],
            $result['role'],
            $result['created_at'],
            $result['avatar'],
            $result['id']
        );
    }

    public function findByUsername(string $username): ?User
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE username = :username');
        $query->execute(['username' => $username]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return new User(
            $result['username'],
            $result['email'],
            $result['password'],
            $result['role'],
            $result['created_at'],
            $result['avatar'],
            $result['id']
        );
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM user');
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = new User(
                $result['username'],
                $result['email'],
                $result['password'],
                $result['role'],
                $result['created_at'],
                $result['avatar'],
                $result['id']
            );
        }
        return $users;
    }

    public function create(User $user): void
    {
        $query = $this->db->prepare('INSERT INTO user (username, email, password, avatar, role, created_at)
            VALUES (:username, :email, :password, :avatar, :role, :created_at)');
        $query->execute([
            'username'   => $user->getUsername(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'avatar'     => $user->getAvatar(),
            'role'       => $user->getRole(),
            'created_at' => $user->getCreatedAt()
        ]);
        $user->setId((int)$this->db->lastInsertId());
    }

    public function update(User $user): User
    {
        $query = $this->db->prepare('UPDATE user
            SET username = :username, email = :email, password = :password,
                avatar = :avatar, role = :role, created_at = :created_at
            WHERE id = :id');
        $query->execute([
            'id'         => $user->getId(),
            'username'   => $user->getUsername(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'avatar'     => $user->getAvatar(),
            'role'       => $user->getRole(),
            'created_at' => $user->getCreatedAt()
        ]);
        return $user;
    }

    public function delete(User $user): void
    {
        $query = $this->db->prepare('DELETE FROM user WHERE id = :id');
        $query->execute(['id' => $user->getId()]);
    }

    public function findVip(int $limit = 3): array
    {
        $query = $this->db->prepare("SELECT * FROM user WHERE role = 'vip' LIMIT :limit");
        $query->bindValue('limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = new User(
                $result['username'],
                $result['email'],
                $result['password'],
                $result['role'],
                $result['created_at'],
                $result['avatar'],
                $result['id']
            );
        }
        return $users;
    }

    public function countAll(): int
    {
        $query = $this->db->prepare('SELECT COUNT(*) FROM user');
        $query->execute();
        return (int)$query->fetchColumn();
    }
}
