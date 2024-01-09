<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

interface ManagerInterface
{
    public function fetchBook(string $title): Book;
}
