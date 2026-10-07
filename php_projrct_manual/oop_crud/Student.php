<?php

class Student
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAll(): array
    {
        $result = $this->conn->query("SELECT * FROM students ORDER BY id DESC");

        if (!$result) {
            return [];
        }

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        return $students;
    }

    public function findById($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO students (name, email, address, phone) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssss",
            $data['name'],
            $data['email'],
            $data['address'],
            $data['phone']
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update($id, array $data): bool
    {
        $stmt = $this->conn->prepare("UPDATE students SET name = ?, email = ?, address = ?, phone = ? WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssssi",
            $data['name'],
            $data['email'],
            $data['address'],
            $data['phone'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete($id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM students WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
