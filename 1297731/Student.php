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
        $result = $this->conn->query("SELECT * FROM producet_list ORDER BY id DESC");

        if (!$result) {
            return [];
        }

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    public function findById($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM producet_list WHERE id = ?");
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
        $stmt = $this->conn->prepare("INSERT INTO producet_list (Produect_name, Descrivtion, Quantity) VALUES (?, ?, ?)");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssi", $data['Produect_name'], $data['Descrivtion'], $data['Quantity']);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update($id, array $data): bool
    {
        $stmt = $this->conn->prepare("UPDATE producet_list SET Produect_name = ?, Descrivtion = ?, Quantity = ? WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssii", $data['Produect_name'], $data['Descrivtion'], $data['Quantity'], $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete($id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM producet_list WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
