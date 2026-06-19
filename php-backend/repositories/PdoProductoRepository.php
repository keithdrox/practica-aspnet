<?php
// repositories/PdoProductoRepository.php
require_once __DIR__ . '/ProductoRepositoryInterface.php';

class PdoProductoRepository implements ProductoRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT "Id", "Nombre", "Precio", "Descripcion", "Stock" FROM "Productos" ORDER BY "Id"');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT "Id", "Nombre", "Precio", "Descripcion", "Stock" FROM "Productos" WHERE "Id" = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(string $nombre, float $precio, ?string $descripcion, int $stock): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO "Productos" ("Nombre", "Precio", "Descripcion", "Stock") VALUES (:nombre, :precio, :descripcion, :stock)'
        );
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update(int $id, string $nombre, float $precio, ?string $descripcion, int $stock): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE "Productos" SET "Nombre" = :nombre, "Precio" = :precio, "Descripcion" = :descripcion, "Stock" = :stock WHERE "Id" = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM "Productos" WHERE "Id" = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}