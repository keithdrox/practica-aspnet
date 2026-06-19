<?php
// repositories/ProductoRepositoryInterface.php

interface ProductoRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function create(string $nombre, float $precio, ?string $descripcion, int $stock): bool;
    public function update(int $id, string $nombre, float $precio, ?string $descripcion, int $stock): bool;
    public function delete(int $id): bool;
}