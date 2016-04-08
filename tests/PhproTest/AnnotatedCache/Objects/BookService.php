<?php

namespace PhproTest\AnnotatedCache\Objects;

use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Annotation\CacheUpdate;

class BookService
{
    /**
     * @Cacheable(pools="books", key="isbn")
     */
    public function getBookByIsbn($isbn)
    {
        return new Book();
    }

    /**
     * @CacheUpdate(pools="books", key="book.isbn")
     */
    public function saveBook(Book $book)
    {
        return $book;
    }

    /**
     * @CacheEvict(pools="books", key="book.isbn")
     */
    public function removeBook(Book $book)
    {

    }
}
