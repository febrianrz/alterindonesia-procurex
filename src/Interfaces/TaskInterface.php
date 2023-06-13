<?php
namespace Alterindonesia\Procurex\Interfaces;
interface TaskInterface {
    public function type(): string;
    public function payload(): array;
}
