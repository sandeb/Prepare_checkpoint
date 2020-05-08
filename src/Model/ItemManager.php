<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class ItemManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'item';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $item
     * @return int
     */
    public function insert(array $item): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`title`, `movie_id`, `planet_id`) VALUES (:title, :movie, :planet)");
        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);
        $statement->bindValue('movie', $item['movie_id'], \PDO::PARAM_STR);
        $statement->bindValue('planet', $item['planet_id'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $item
     * @return bool
     */
    public function update(array $item):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title, `planet_id` = :planet_id, `movie_id` = :movie_id WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);
        $statement->bindValue('planet_id', $item['planet'], \PDO::PARAM_INT);
        $statement->bindValue('movie_id', $item['movie'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectOneById(int $id)
    {
        // prepared request
        // item.title // movie.title // planet.name
        // item.planet_id // item.movie_id
        $statement = $this->pdo->prepare("
        SELECT item.id, item.title, movie.id as movie_id, movie.title as movie_title, planet.id as planet_id, planet.name as planet_name  
        FROM " . self::TABLE . " 
        JOIN movie ON movie.id=item.movie_id
        JOIN planet ON planet.id=item.planet_id
        WHERE item.id=:id"
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
