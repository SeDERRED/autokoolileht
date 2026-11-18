<?php
// model/UserModel.php

class UserModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(string $username, string $password, string $role = 'user'): bool {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $role]);
    }

    public function verifyPassword(string $username, string $password): ?array {
        // Тестовый администратор admin / admin123
        if ($username === 'admin' && $password === 'admin123') {
            $user = $this->findByUsername('admin');
            if (!$user) {
                $hash = password_hash('admin123', PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES ('admin', ?, 'admin')");
                $stmt->execute([$hash]);
                return $this->findByUsername('admin');
            }
            return $user;
        }

        // Тестовый обычный пользователь user / user123
        if ($username === 'user' && $password === 'user123') {
            $user = $this->findByUsername('user');
            if (!$user) {
                $hash = password_hash('user123', PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES ('user', ?, 'user')");
                $stmt->execute([$hash]);
                return $this->findByUsername('user');
            }
            return $user;
        }

        $user = $this->findByUsername($username);
        if ($user) {
            if (password_verify($password, $user['password']) || $user['password'] === $password) {
                return $user;
            }
        }

        return null;
    }
}
